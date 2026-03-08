<?php

declare(strict_types=1);

namespace Sham\AI\Models;

use Sham\AI\Enums\Capability;

class SupportedModels
{
    /**
     * Get the list of supported providers.
     *
     * @return array<string, string>
     */
    public static function getProviders(): array
    {
        return [
            // Prism Built-in
            'openai' => 'OpenAI',
            'anthropic' => 'Anthropic',
            'google' => 'Google',
            'deepseek' => 'DeepSeek',
            'xai' => 'xAI (Grok)',
            'mistral' => 'Mistral',
            'ollama' => 'Ollama (Local)',

            // Custom
            'zhipu' => 'Zhipu (GLM)',

            // HuggingFace Families
            'huggingface-nllb' => 'HuggingFace NLLB',
            'huggingface-opus-mt' => 'HuggingFace Opus-MT',
            'huggingface-llama' => 'HuggingFace Llama',
            'huggingface-qwen' => 'HuggingFace Qwen',
            'huggingface-mistral' => 'HuggingFace Mistral',
            'huggingface-flux' => 'HuggingFace FLUX',
            'huggingface-sd' => 'HuggingFace Stable Diffusion',
            'huggingface-sdxl' => 'HuggingFace SDXL',
        ];
    }

    /**
     * Get the list of capabilities for a specific provider.
     *
     * @return array<Capability>
     */
    public static function getProviderCapabilities(string $provider): array
    {
        return match ($provider) {
            'openai', 'google' => [Capability::TEXT_GENERATION, Capability::TRANSLATION, Capability::SEO, Capability::IMAGE_GENERATION],
            'anthropic', 'deepseek', 'xai', 'mistral', 'zhipu' => [Capability::TEXT_GENERATION, Capability::TRANSLATION, Capability::SEO],
            'ollama' => [Capability::TEXT_GENERATION, Capability::TRANSLATION],
            'huggingface-nllb', 'huggingface-opus-mt' => [Capability::TRANSLATION],
            'huggingface-llama', 'huggingface-qwen', 'huggingface-mistral' => [Capability::TEXT_GENERATION, Capability::TRANSLATION],
            'huggingface-flux', 'huggingface-sd', 'huggingface-sdxl' => [Capability::IMAGE_GENERATION],
            default => [],
        };
    }

    /**
     * Get the model info/instructions for a specific provider.
     */
    public static function getProviderModelInfo(string $provider): array
    {
        $pkg = 'sham-ai::sham-ai.settings.provider_instructions.';

        $base = [
            'how_to_find' => __($pkg . 'how_to_find'),
            'example_label' => __($pkg . 'example'),
        ];

        return array_merge($base, match ($provider) {
            'openai' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/openai.html',
                'instructions' => __($pkg . 'openai.instructions'),
                'notes' => __($pkg . 'openai.notes'),
                'example' => 'gpt-4o',
            ],
            'anthropic' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/anthropic.html',
                'instructions' => __($pkg . 'anthropic.instructions'),
                'notes' => __($pkg . 'anthropic.notes'),
                'example' => 'claude-3-7-sonnet-latest',
            ],
            'google' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/google.html',
                'instructions' => __($pkg . 'google.instructions'),
                'notes' => __($pkg . 'google.notes'),
                'example' => 'gemini-2.0-flash',
            ],
            'deepseek' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/deepseek.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => 'deepseek-chat',
            ],
            'xai' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/xai.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => 'grok-2-latest',
            ],
            'mistral' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/mistral.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => 'mistral-large-latest',
            ],
            'zhipu' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/zhipu.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => 'glm-4-plus',
            ],
            'ollama' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/ollama.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => 'llama3.2',
            ],
            'huggingface-flux' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-flux.html',
                'instructions' => __($pkg . 'huggingface-flux.instructions'),
                'notes' => __($pkg . 'huggingface-flux.notes'),
                'example' => 'black-forest-labs/FLUX.1-schnell',
            ],
            'huggingface-nllb' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-nllb.html',
                'instructions' => __($pkg . 'huggingface-nllb.instructions'),
                'notes' => __($pkg . 'huggingface-nllb.notes'),
                'example' => 'facebook/nllb-200-distilled-600M',
            ],
            'huggingface-opus-mt' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-opus-mt.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => 'Helsinki-NLP/opus-mt-en-ar',
            ],
            'huggingface-llama' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-llama.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => 'meta-llama/Llama-3.2-3B-Instruct',
            ],
            'huggingface-qwen' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-qwen.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => 'Qwen/Qwen2.5-72B-Instruct',
            ],
            'huggingface-mistral' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-mistral.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => 'mistralai/Mistral-7B-Instruct-v0.3',
            ],
            'huggingface-sd' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-sd.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => 'runwayml/stable-diffusion-v1-5',
            ],
            'huggingface-sdxl' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-sdxl.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => 'stabilityai/stable-diffusion-xl-base-1.0',
            ],
            default => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/index.html',
                'instructions' => __($pkg . 'default.instructions'),
                'notes' => __($pkg . 'default.notes'),
                'example' => '',
            ],
        });
    }

    /**
     * Runtime storage for synced models.
     *
     * @var array<string, array>
     */
    protected static array $dynamicModels = [];

    /**
     * Register dynamic models (e.g. from sync) for a provider.
     */
    public static function registerDynamicModels(string $provider, array $models): void
    {
        self::$dynamicModels[$provider] = $models;
    }

    /**
     * Get the supported models for a specific provider.
     *
     * @return array<array{model: string, name: string, capabilities: array<string>, is_custom?: bool, status?: string}>
     */
    public static function getModelsForProvider(string $provider): array
    {
        $hardcoded = match ($provider) {
            'openai' => [
                ['model' => 'gpt-5.4', 'name' => 'GPT-5.4 (Latest Frontier)', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gpt-5.4-mini', 'name' => 'GPT-5.4 Mini', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gpt-5.1', 'name' => 'GPT-5.1 (Personalized)', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gpt-5.1-mini', 'name' => 'GPT-5.1 Mini', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'gpt-4o', 'name' => 'GPT-4o (Legacy Stable)', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gpt-image-1', 'name' => 'GPT Image 1', 'capabilities' => ['image_generation']],
                ['model' => 'dall-e-3', 'name' => 'DALL-E 3', 'capabilities' => ['image_generation']],
            ],
            'anthropic' => [
                ['model' => 'claude-opus-4-5', 'name' => 'Claude Opus 4.5', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'claude-sonnet-4-5', 'name' => 'Claude Sonnet 4.5', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'claude-sonnet-4', 'name' => 'Claude Sonnet 4', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'claude-haiku-4-5', 'name' => 'Claude Haiku 4.5', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'claude-3-7-sonnet-latest', 'name' => 'Claude 3.7 Sonnet', 'capabilities' => ['text_generation', 'translation', 'seo']],
            ],
            'google' => [
                // Stable 3.x
                ['model' => 'gemini-3.1-pro', 'name' => 'Gemini 3.1 Pro', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gemini-3.1-flash-lite', 'name' => 'Gemini 3.1 Flash-Lite', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gemini-3-flash', 'name' => 'Gemini 3 Flash', 'capabilities' => ['text_generation', 'translation', 'seo']],
                // Stable 2.x
                ['model' => 'gemini-2.5-pro', 'name' => 'Gemini 2.5 Pro', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gemini-2.5-flash', 'name' => 'Gemini 2.5 Flash', 'capabilities' => ['text_generation', 'translation']],
                // Image
                ['model' => 'nano-banana-2', 'name' => 'Nano Banana 2 (Image Gen)', 'capabilities' => ['image_generation']],
                ['model' => 'imagen-3.0-generate-002', 'name' => 'Imagen 3', 'capabilities' => ['image_generation']],
            ],
            'deepseek' => [
                ['model' => 'deepseek-chat', 'name' => 'DeepSeek V3.2 (Chat)', 'capabilities' => ['text_generation', 'translation', 'seo']],
            ],
            'xai' => [
                ['model' => 'grok-4', 'name' => 'Grok 4', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'grok-4-1-fast', 'name' => 'Grok 4.1 Fast', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'grok-3', 'name' => 'Grok 3', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'grok-3-fast', 'name' => 'Grok 3 Fast', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'grok-3-mini', 'name' => 'Grok 3 Mini', 'capabilities' => ['text_generation', 'translation']],
            ],
            'mistral' => [
                ['model' => 'mistral-large-latest', 'name' => 'Mistral Large 3', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'mistral-medium-latest', 'name' => 'Mistral Medium 3.1', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'mistral-small-latest', 'name' => 'Mistral Small 3.2', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'ministral-8b-latest', 'name' => 'Ministral 8B', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'ministral-3b-latest', 'name' => 'Ministral 3B', 'capabilities' => ['text_generation', 'translation']],
            ],
            'zhipu' => [
                ['model' => 'glm-5', 'name' => 'GLM-5', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'glm-4.7', 'name' => 'GLM-4.7', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'glm-4.6', 'name' => 'GLM-4.6', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'cogview-4', 'name' => 'CogView 4', 'capabilities' => ['image_generation']],
                ['model' => 'cogview-3-plus', 'name' => 'CogView 3 Plus', 'capabilities' => ['image_generation']],
            ],
            'ollama' => [
                ['model' => 'llama4:maverick', 'name' => 'Llama 4 Maverick', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'llama4:scout', 'name' => 'Llama 4 Scout', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'llama3.3', 'name' => 'Llama 3.3', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'llama3.2', 'name' => 'Llama 3.2', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'llama3.1', 'name' => 'Llama 3.1', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'qwen3.5', 'name' => 'Qwen 3.5', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'qwen3', 'name' => 'Qwen 3', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gemma3', 'name' => 'Gemma 3', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'phi4', 'name' => 'Phi-4', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'mistral', 'name' => 'Mistral', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'mixtral', 'name' => 'Mixtral (MoE)', 'capabilities' => ['text_generation', 'translation', 'seo']],
            ],
            default => [],
        };

        $dynamic = self::$dynamicModels[$provider] ?? [];

        return array_merge($hardcoded, $dynamic);
    }

    /**
     * Get the information for a specific model.
     *
     * @return array{model: string, name: string, capabilities: array<string>, status?: string}|null
     */
    public static function getModelInfo(string $provider, string $model): ?array
    {
        $models = self::getModelsForProvider($provider);

        return collect($models)->firstWhere('model', $model);
    }
}
