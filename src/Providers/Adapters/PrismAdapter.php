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
     * Custom providers that are not in the Provider enum.
     */
    protected const CUSTOM_PROVIDERS = [
        'zhipu',
        'huggingface-nllb',
        'huggingface-opus-mt',
        'huggingface-llama',
        'huggingface-qwen',
        'huggingface-mistral',
        'huggingface-flux',
        'huggingface-sd',
        'huggingface-sdxl',
    ];

    /**
     * Build provider config from model config.
     *
     * @return array<string, mixed>
     */
    protected function buildProviderConfig(): array
    {
        $config = [];

        // Pass API key from model config
        if (! empty($this->model->config['api_key'])) {
            $config['api_key'] = $this->model->config['api_key'];
        }

        // Pass base URL if provided
        if (! empty($this->model->config['base_url'])) {
            $config['url'] = $this->model->config['base_url'];
        }

        return $config;
    }

    /**
     * Check if the provider is a custom provider (not in Provider enum).
     */
    protected function isCustomProvider(): bool
    {
        return in_array($this->model->provider, self::CUSTOM_PROVIDERS, true);
    }

    /**
     * {@inheritdoc}
     */
    public function send(PromptInterface $prompt): AIResponseInterface
    {
        try {
            $providerConfig = $this->buildProviderConfig();

            // Use string provider name for custom providers, enum for built-in
            $providerName = $this->isCustomProvider()
                ? $this->model->provider
                : Provider::from($this->model->provider);

            $response = Prism::text()
                ->using($providerName, $this->model->model, $providerConfig)
                ->withSystemPrompt($prompt->getSystemPrompt())
                ->withPrompt($prompt->getUserPrompt())
                ->asText();

            // We assume PrismResponse already exists and works with Prism's response
            return new PrismResponse($response);
        } catch (\Throwable $e) {
            return new PrismResponse(null, $this->mapError($e));
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

            $providerConfig = $this->buildProviderConfig();

            // Use string provider name for custom providers, enum for built-in
            $providerName = $this->isCustomProvider()
                ? $this->model->provider
                : Provider::from($this->model->provider);

            $response = Prism::text()
                ->using($providerName, $this->model->model, $providerConfig)
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
            return new TranslationResponse(successful: false, error: $this->mapError($e));
        }
    }

    protected function mapError(\Throwable $e): string
    {
        $code = $e->getCode();

        // Some HTTP exceptions might have getStatusCode()
        if (method_exists($e, 'getStatusCode')) {
            $code = $e->getStatusCode();
        }

        $messageKey = match ((int) $code) {
            401, 403 => 'sham-ai::sham-ai.settings.errors.permissions',
            402 => 'sham-ai::sham-ai.settings.errors.payment',
            429 => 'sham-ai::sham-ai.settings.errors.rate_limit',
            503 => 'sham-ai::sham-ai.settings.errors.unavailable',
            default => 'sham-ai::sham-ai.settings.errors.generic',
        };

        // Log the actual error for debugging
        Log::error('AI Provider Error mapped', [
            'provider' => $this->model->provider,
            'model' => $this->model->model,
            'code' => $code,
            'original_message' => $e->getMessage(),
        ]);

        return __($messageKey);
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
