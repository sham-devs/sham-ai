<?php

declare(strict_types=1);

namespace Sham\AI\Prism\Providers\HuggingFace;

use Illuminate\Support\Facades\Http;
use Prism\Prism\Providers\Provider;

abstract class BaseHuggingFaceProvider extends Provider
{
    protected string $baseUrl = 'https://api-inference.huggingface.co/models/';

    protected array $defaultOptions = [];

    /**
     * The key to use for options in the payload.
     * NLLB uses 'options', others use 'parameters'.
     */
    protected string $optionsKey = 'parameters';

    /**
     * @param  array{api_key?: string, url?: string, options?: array<string, mixed>}  $config
     */
    public function __construct(array $config)
    {
        if (isset($config['api_key'])) {
            $this->apiKey = (string) $config['api_key'];
        }

        if (isset($config['url'])) {
            $this->baseUrl = rtrim((string) $config['url'], '/').'/';
        }

        if (isset($config['options'])) {
            $this->defaultOptions = (array) $config['options'];
        }
    }

    /**
     * Build the payload for the API request.
     *
     * @param  array<string, mixed>  $runtimeOptions  Options passed at runtime (from Request)
     * @return array<string, mixed>
     */
    protected function buildPayload(string $prompt, array $runtimeOptions = []): array
    {
        $payload = ['inputs' => $prompt];

        // Merge: defaults < runtime (runtime overrides defaults)
        $options = array_merge($this->defaultOptions, $runtimeOptions);

        if (! empty($options)) {
            $payload[$this->optionsKey] = $this->formatOptions($options);
        }

        return $payload;
    }

    /**
     * Format options for the API.
     * Override in subclass if special formatting is needed.
     *
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    protected function formatOptions(array $options): array
    {
        return $options;
    }

    /**
     * Send a request and return JSON response.
     *
     * @return array<string, mixed>
     */
    public function sendRequest(string $model, array $payload): array
    {
        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl.$model, $payload);

        if (! $response->successful()) {
            throw new \RuntimeException("HuggingFace API Error: {$response->status()} - {$response->body()}");
        }

        return $response->json();
    }

    /**
     * Send a request and return raw response body.
     */
    public function sendRawRequest(string $model, array $payload): string
    {
        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl.$model, $payload);

        if (! $response->successful()) {
            throw new \RuntimeException("HuggingFace API Error: {$response->status()} - {$response->body()}");
        }

        return $response->body();
    }

    /**
     * Extract language code from locale.
     * Converts 'ar_SA' -> 'ar', 'en_US' -> 'en'
     */
    protected function extractLangCode(string $locale): string
    {
        return substr($locale, 0, 2);
    }

    /**
     * Parse size string to width and height.
     * Supports '1024x1024' format.
     *
     * @return array{width: int, height: int}
     */
    protected function parseSize(string $size): array
    {
        $parts = explode('x', $size);

        if (count($parts) === 2) {
            return [
                'width' => (int) $parts[0],
                'height' => (int) $parts[1],
            ];
        }

        // Default to 1024x1024
        return ['width' => 1024, 'height' => 1024];
    }
}
