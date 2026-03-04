<?php

declare(strict_types=1);

namespace Sham\AI\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModelSyncService
{
    /**
     * Sync models for a specific provider.
     */
    public function sync(string $provider, array $config = [], ?string $search = null): array
    {
        return match ($provider) {
            'openrouter' => $this->syncOpenRouter($search),
            'huggingface' => $this->syncHuggingFace($search),
            'openai', 'deepseek', 'xai', 'mistral', 'zhipu' => $this->syncOpenAICompatible($provider, $config),
            default => [],
        };
    }

    /**
     * Sync models from OpenRouter.
     */
    public function syncOpenRouter(?string $search = null): array
    {
        try {
            $response = Http::timeout(10)->get('https://openrouter.ai/api/v1/models');
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];

                $models = collect($data)->map(fn ($m) => [
                    'model' => $m['id'],
                    'name' => $m['name'],
                    'capabilities' => $this->inferCapabilities($m),
                    'context_length' => $m['context_length'] ?? 0,
                    'input_modalities' => $m['architecture']['input_modalities'] ?? [],
                    'pricing' => $m['pricing'] ?? [],
                    'status' => $this->inferOpenRouterStatus($m),
                ]);

                if ($search) {
                    $search = strtolower($search);
                    $models = $models->filter(function ($m) use ($search) {
                        return str_contains(strtolower($m['model']), $search) || 
                               str_contains(strtolower($m['name']), $search);
                    });
                }

                return $models->toArray();
            }
        } catch (\Throwable $e) {
            Log::error('OpenRouter Sync Failed: '.$e->getMessage());
        }

        return [];
    }

    public function syncHuggingFace(?string $search = null): array
    {
        try {
            // Fetch popular text-generation models
            $params = [
                'pipeline_tag' => 'text-generation',
                'sort' => 'downloads',
                'direction' => -1,
                'limit' => $search ? 100 : 50,
                'inference' => 'warm',
            ];

            if ($search) {
                $params['search'] = $search;
            }

            $response = Http::timeout(10)->get('https://huggingface.co/api/models', $params);

            if ($response->successful()) {
                $data = $response->json();
                $textModels = collect($data)->map(fn ($m) => [
                    'model' => $m['modelId'],
                    'name' => $m['modelId'],
                    'capabilities' => $this->inferCapabilities($m),
                    'status' => ($m['private'] ?? false) || in_array('gated', $m['tags'] ?? []) ? 'gated' : 'usable',
                ]);

                // Fetch popular text-to-image models
                $imgResponse = Http::timeout(10)->get('https://huggingface.co/api/models', [
                    'pipeline_tag' => 'text-to-image',
                    'sort' => $search ? null : 'trendScore',
                    'direction' => -1,
                    'limit' => $search ? 50 : 20,
                    'inference' => 'warm',
                    'search' => $search,
                ]);

                if ($imgResponse->successful()) {
                    $imgModels = collect($imgResponse->json())->map(fn ($m) => [
                        'model' => $m['modelId'],
                        'name' => $m['modelId'],
                        'capabilities' => $this->inferCapabilities($m),
                        'status' => ($m['private'] ?? false) || in_array('gated', $m['tags'] ?? []) ? 'gated' : 'usable',
                    ]);
                    $textModels = $textModels->concat($imgModels);
                }

                return $textModels->toArray();
            }
        } catch (\Throwable $e) {
            Log::error('Hugging Face Sync Failed: '.$e->getMessage());
        }

        return [];
    }

    /**
     * Infer status for OpenRouter models.
     */
    protected function inferOpenRouterStatus(array $m): string
    {
        $promptPrice = (float) ($m['pricing']['prompt'] ?? 0);

        return $promptPrice > 0 ? 'payment_required' : 'usable';
    }

    /**
     * Infer capabilities based on model metadata.
     */
    protected function inferCapabilities(array $m): array
    {
        $caps = ['text_generation'];
        $desc = strtolower($m['description'] ?? '');
        $id = strtolower($m['modelId'] ?? $m['id'] ?? '');
        $tags = $m['tags'] ?? [];
        $pipeline = $m['pipeline_tag'] ?? '';

        // Hugging Face specific tags mapping
        if ($pipeline === 'text-to-image' || in_array('text-to-image', $tags)) {
            $caps[] = 'image_generation';
        }

        if ($pipeline === 'image-to-image' || $pipeline === 'image-editing') {
            $caps[] = 'image_editing';
        }

        // OpenRouter / General Modalities
        $modalities = $m['architecture']['input_modalities'] ?? [];
        if (in_array('image', $modalities) || $pipeline === 'image-text-to-text') {
            $caps[] = 'seo';
        }

        if (str_contains($desc, 'translate') || str_contains($desc, 'multilingual') || in_array('translation', $tags) || $pipeline === 'translation') {
            $caps[] = 'translation';
        }

        if (str_contains($desc, 'vision') || str_contains($id, 'vision')) {
            if (! in_array('seo', $caps)) {
                $caps[] = 'seo';
            }
        }

        if (str_contains($desc, 'image generation') || str_contains($id, 'dall-e') || str_contains($id, 'flux')) {
            if (! in_array('image_generation', $caps)) {
                $caps[] = 'image_generation';
            }
        }

        return array_unique($caps);
    }

    /**
     * Sync models from OpenAI-compatible endpoints.
     */
    public function syncOpenAICompatible(string $provider, array $config): array
    {
        $baseUrl = $config['base_url'] ?? null;
        $apiKey = $config['api_key'] ?? null;

        if (! $baseUrl || ! $apiKey) {
            return [];
        }

        try {
            $response = Http::timeout(10)
                ->withToken($apiKey)
                ->get(rtrim($baseUrl, '/').'/models');

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];

                return collect($data)->map(fn ($m) => [
                    'model' => $m['id'],
                    'name' => $m['id'], // Use ID as name if not provided
                    'capabilities' => ['text_generation', 'translation'], // Assume baseline
                ])->toArray();
            }
        } catch (\Throwable $e) {
            Log::error("{$provider} Sync Failed: ".$e->getMessage());
        }

        return [];
    }
}
