<?php

declare(strict_types=1);

namespace Sham\AI\Providers;

use Illuminate\Support\Facades\Log;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Sham\AI\Contracts\AIProviderInterface;
use Sham\AI\Contracts\AIResponseInterface;
use Sham\AI\Contracts\PromptInterface;
use Sham\AI\Responses\PrismResponse;

class PrismProvider implements AIProviderInterface
{
    protected string $model;

    protected string $apiKey;

    protected Provider $provider;

    public function __construct()
    {
        $this->configure(config('ai.providers.prism', []));
    }

    /**
     * Configure the provider with data.
     */
    public function configure(array $config): void
    {
        $this->model = $config['model'] ?? $this->model ?? 'gpt-4';

        if (isset($config['api_key'])) {
            $this->apiKey = (string) $config['api_key'];
        }

        $providerName = empty($this->apiKey)
            ? 'openai'
            : ($config['provider'] ?? $providerName ?? 'openai');

        $this->provider = Provider::from($providerName);
    }

    /**
     * {@inheritdoc}
     */
    public function send(PromptInterface $prompt): AIResponseInterface
    {
        try {
            $response = Prism::text()
                ->using($this->provider, $this->model)
                ->withSystemPrompt($prompt->getSystemPrompt())
                ->withPrompt($prompt->getUserPrompt())
                ->asText();

            return new PrismResponse($response);
        } catch (\Throwable $e) {
            Log::error('AI Provider Error', [
                'provider' => $this->getName(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return new PrismResponse(null, $e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'prism';
    }
}
