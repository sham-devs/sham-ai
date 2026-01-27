<?php

declare(strict_types=1);

namespace Sham\AI;

use Sham\AI\Contracts\AIProviderInterface;
use Sham\AI\Contracts\AIResponseInterface;
use Sham\AI\Contracts\PromptInterface;
use Sham\AI\Providers\PrismProvider;
use Illuminate\Support\Facades\App;

class AIService
{
    protected ?AIProviderInterface $provider = null;

    public function __construct(
        protected \App\Services\Settings\SettingsService $settingsService
    ) {}

    /**
     * Get the configured AI provider.
     */
    public function getProvider(): AIProviderInterface
    {
        if ($this->provider === null) {
            $this->provider = App::make($this->getProviderClass());

            // If it's a PrismProvider, we should ensure it has the latest key/model from settings
            if ($this->provider instanceof PrismProvider) {
                $this->provider->configure([
                    'provider' => $this->settingsService->get('ai.provider', 'openai'),
                    'model' => $this->settingsService->get('ai.model', 'gpt-4o'),
                    'api_key' => $this->settingsService->get('ai.api_key'),
                ]);
            }
        }

        return $this->provider;
    }

    /**
     * Send a prompt to the AI provider.
     *
     * @param  PromptInterface  $prompt  The prompt to send
     * @return AIResponseInterface The AI response
     */
    public function send(PromptInterface $prompt): AIResponseInterface
    {
        return $this->getProvider()->send($prompt);
    }

    /**
     * Translate texts from one locale to another.
     *
     * @param  array<string>  $texts  The texts to translate
     * @param  string  $from  Source locale
     * @param  string  $to  Target locale
     * @param  array<string, mixed>  $options  Additional options
     * @return array<string> Translated texts
     */
    public function translate(array $texts, string $from, string $to, array $options = []): array
    {
        if (! $this->settingsService->isAIEnabled()) {
            throw new \RuntimeException('AI features are disabled in settings.');
        }

        $options = array_merge([
            'temperature' => (float) $this->settingsService->get('ai.temperature', 0.3),
            'max_tokens' => (int) $this->settingsService->get('ai.max_tokens', 2000),
        ], $options);

        $prompt = new Prompts\TranslationPrompt($texts, $from, $to, $options);
        $response = $this->send($prompt);

        if (! $response->isSuccessful()) {
            throw new \RuntimeException('AI Translation failed: '.$response->getError());
        }

        return $this->parseTranslations($response->getText(), count($texts));
    }

    /**
     * Check if AI is configured and ready to use.
     */
    public function isConfigured(): bool
    {
        if (! $this->settingsService->isAIEnabled()) {
            return false;
        }

        return $this->getProvider()->isConfigured();
    }

    /**
     * Get the provider class from configuration.
     *
     * @return class-string<AIProviderInterface>
     */
    protected function getProviderClass(): string
    {
        $provider = $this->settingsService->get('ai.default_provider', 'prism');

        return match ($provider) {
            'prism' => PrismProvider::class,
            default => throw new \InvalidArgumentException("Unknown AI provider: {$provider}"),
        };
    }

    /**
     * Parse the translations from the AI response text.
     *
     * @param  string  $text  The AI response text
     * @param  int  $count  The expected number of translations
     * @return array<string>
     */
    protected function parseTranslations(string $text, int $count): array
    {
        $lines = array_filter(array_map('trim', explode("\n", $text)));

        $translations = [];
        foreach ($lines as $line) {
            // Remove numbered prefixes like "1. ", "2. ", etc.
            $cleanLine = preg_replace('/^\d+\.\s*/', '', $line);
            if (! empty($cleanLine)) {
                $translations[] = $cleanLine;
            }
        }

        // Ensure we have the correct number of translations
        while (count($translations) < $count) {
            $translations[] = '';
        }

        return array_slice($translations, 0, $count);
    }
}
