<?php

declare(strict_types=1);

namespace Sham\AI\Providers\Adapters;

use Illuminate\Support\Facades\Log;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Sham\AI\Capabilities\Contracts\TranslationCapabilityInterface;
use Sham\AI\Capabilities\DTOs\TranslationRequest;
use Sham\AI\Capabilities\DTOs\TranslationResponse;
use Sham\AI\Contracts\AIResponseInterface;
use Sham\AI\Contracts\PromptInterface;
use Sham\AI\Responses\PrismResponse;

class PrismAdapter extends AbstractProviderAdapter implements TranslationCapabilityInterface
{
    /**
     * {@inheritdoc}
     */
    public function send(PromptInterface $prompt): AIResponseInterface
    {
        try {
            $response = Prism::text()
                ->using(Provider::from($this->model->provider), $this->model->model)
                ->withSystemPrompt($prompt->getSystemPrompt())
                ->withPrompt($prompt->getUserPrompt())
                ->asText();

            // We assume PrismResponse already exists and works with Prism's response
            return new PrismResponse($response);
        } catch (\Throwable $e) {
            Log::error('AI Provider Error (PrismAdapter)', [
                'provider' => $this->model->provider,
                'model' => $this->model->model,
                'error' => $e->getMessage(),
            ]);

            return new PrismResponse(null, $e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigured(): bool
    {
        return ! empty($this->model->config['api_key']);
    }

    /**
     * {@inheritdoc}
     */
    public function canTranslate(): bool
    {
        return $this->model->supportsCapability('translation');
    }

    /**
     * {@inheritdoc}
     */
    public function translate(TranslationRequest $request): TranslationResponse
    {
        if (! $this->canTranslate()) {
            return new TranslationResponse(successful: false, error: 'Model does not support translation capability');
        }

        try {
            // Prepare prompt for translation
            $systemPrompt = $this->getTranslationSystemPrompt($request);
            $userPrompt = json_encode($request->texts, JSON_UNESCAPED_UNICODE);

            $response = Prism::text()
                ->using(Provider::from($this->model->provider), $this->model->model)
                ->withSystemPrompt($systemPrompt)
                ->withPrompt($userPrompt)
                ->asText();

            $translatedTexts = json_decode($response->text, true);

            if (! is_array($translatedTexts)) {
                return new TranslationResponse(successful: false, error: 'Failed to decode translation response');
            }

            return new TranslationResponse(
                successful: true,
                translations: $translatedTexts,
                usage: $response->usage ?? [],
                modelUsed: $this->model->model
            );
        } catch (\Throwable $e) {
            Log::error('Translation Capability Error', [
                'provider' => $this->model->provider,
                'model' => $this->model->model,
                'error' => $e->getMessage(),
            ]);

            return new TranslationResponse(successful: false, error: $e->getMessage());
        }
    }

    protected function getTranslationSystemPrompt(TranslationRequest $request): string
    {
        return "You are a professional translator. Translate the following JSON object from {$request->fromLocale} to {$request->toLocale}. 
                Maintain the same JSON structure and keys. Only return the translated JSON object.
                Context: ".($request->options['context'] ?? 'general').'.
                Tone: '.($request->options['tone'] ?? 'formal').'.';
    }

    /**
     * Capability Interface Methods
     */
    public static function getCapabilityName(): string
    {
        return 'translation';
    }

    public static function getCapabilityLabel(): string
    {
        return 'Translation';
    }

    public static function getCapabilityDescription(): string
    {
        return 'Translate text across multiple languages.';
    }
}
