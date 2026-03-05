<?php

declare(strict_types=1);

namespace Sham\AI\Prism\Providers\HuggingFace;

use Prism\Prism\Providers\Provider;
use Illuminate\Support\Facades\Http;

abstract class BaseHuggingFaceProvider extends Provider
{
    protected string $baseUrl = 'https://api-inference.huggingface.co/models/';

    public function __construct(protected string $apiKey) {}

    public function sendRequest(string $model, array $payload): array
    {
        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl . $model, $payload);

        if (!$response->successful()) {
            throw new \RuntimeException("HuggingFace API Error: {$response->status()} - {$response->body()}");
        }

        return $response->json();
    }

    public function sendRawRequest(string $model, array $payload): string
    {
        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl . $model, $payload);

        if (!$response->successful()) {
            throw new \RuntimeException("HuggingFace API Error: {$response->status()} - {$response->body()}");
        }

        return $response->body();
    }
}
