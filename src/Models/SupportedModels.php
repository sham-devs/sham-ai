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
            'openai', 'google', 'openrouter' => [Capability::TEXT_GENERATION, Capability::TRANSLATION, Capability::SEO, Capability::IMAGE_GENERATION],
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
                'url' => 'https://platform.openai.com/models',
                'instructions' => __($pkg . 'openai.instructions'),
                'notes' => __($pkg . 'openai.notes'),
                'example' => 'gpt-4o',
            ],
            'anthropic' => [
                'url' => 'https://console.anthropic.com/settings/plans',
                'instructions' => __($pkg . 'anthropic.instructions'),
                'notes' => __($pkg . 'anthropic.notes'),
                'example' => 'claude-3-5-sonnet-latest',
            ],
            'google' => [
                'url' => 'https://aistudio.google.com/app/models',
                'instructions' => __($pkg . 'google.instructions'),
                'notes' => __($pkg . 'google.notes'),
                'example' => 'gemini-2.0-flash-exp',
            ],
            'huggingface-flux' => [
                'url' => 'https://huggingface.co/models?search=black-forest-labs%2FFLUX',
                'instructions' => __($pkg . 'huggingface-flux.instructions'),
                'notes' => __($pkg . 'huggingface-flux.notes'),
                'example' => 'black-forest-labs/FLUX.1-schnell',
            ],
            'huggingface-nllb' => [
                'url' => 'https://huggingface.co/models?search=nllb',
                'instructions' => __($pkg . 'huggingface-nllb.instructions'),
                'notes' => __($pkg . 'huggingface-nllb.notes'),
                'example' => 'facebook/nllb-200-distilled-600M',
            ],
            default => [
                'url' => '',
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
                ['model' => 'gpt-5.2', 'name' => 'GPT-5.2', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gpt-5.2-pro', 'name' => 'GPT-5.2 Pro', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gpt-4.1', 'name' => 'GPT-4.1', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gpt-4.1-mini', 'name' => 'GPT-4.1 Mini', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'gpt-4.1-nano', 'name' => 'GPT-4.1 Nano', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'gpt-4o', 'name' => 'GPT-4o', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gpt-4o-mini', 'name' => 'GPT-4o Mini', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'o3', 'name' => 'o3 (Reasoning)', 'capabilities' => ['text_generation', 'seo']],
                ['model' => 'o4-mini', 'name' => 'o4-mini (Reasoning)', 'capabilities' => ['text_generation', 'seo']],
                ['model' => 'dall-e-3', 'name' => 'DALL-E 3', 'capabilities' => ['image_generation']],
            ],
            'anthropic' => [
                ['model' => 'claude-opus-4-6', 'name' => 'Claude Opus 4.6', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'claude-sonnet-4-6', 'name' => 'Claude Sonnet 4.6', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'claude-haiku-4-5', 'name' => 'Claude Haiku 4.5', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'claude-sonnet-4-5', 'name' => 'Claude Sonnet 4.5', 'capabilities' => ['text_generation', 'translation', 'seo']],
            ],
            'google' => [
                ['model' => 'gemini-3.1-pro', 'name' => 'Gemini 3.1 Pro', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gemini-3-flash-preview', 'name' => 'Gemini 3 Flash (Preview)', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'gemini-3-pro-preview', 'name' => 'Gemini 3 Pro (Legacy)', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gemini-3.1-flash-image-preview', 'name' => 'Nano Banana 2 (Image)', 'capabilities' => ['image_generation', 'image_editing']],
                ['model' => 'gemini-2.5-pro', 'name' => 'Gemini 2.5 Pro', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'gemini-2.5-flash', 'name' => 'Gemini 2.5 Flash', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'gemini-2.5-flash-lite', 'name' => 'Gemini 2.5 Flash Lite', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'gemini-2.5-flash-image', 'name' => 'Nano Banana (Image)', 'capabilities' => ['image_generation', 'image_editing']],
                ['model' => 'imagen', 'name' => 'Imagen 4', 'capabilities' => ['image_generation']],
            ],
            'deepseek' => [
                ['model' => 'deepseek-chat', 'name' => 'DeepSeek V3.2', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'deepseek-reasoner', 'name' => 'DeepSeek R1', 'capabilities' => ['text_generation', 'seo']],
            ],
            'xai' => [
                ['model' => 'grok-4-1-fast', 'name' => 'Grok 4.1 Fast', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'grok-3', 'name' => 'Grok 3', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'grok-3-fast', 'name' => 'Grok 3 Fast', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'grok-3-mini', 'name' => 'Grok 3 Mini', 'capabilities' => ['text_generation', 'translation']],
            ],
            'mistral' => [
                ['model' => 'mistral-large-latest', 'name' => 'Mistral Large 3', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'mistral-medium-latest', 'name' => 'Mistral Medium 3.1', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'mistral-small-latest', 'name' => 'Mistral Small 3.2', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'codestral-latest', 'name' => 'Codestral 2', 'capabilities' => ['text_generation']],
                ['model' => 'pixtral-large-latest', 'name' => 'Pixtral Large', 'capabilities' => ['text_generation', 'seo']],
            ],
            'zhipu' => [
                ['model' => 'glm-5', 'name' => 'GLM-5', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'glm-4.7', 'name' => 'GLM-4.7', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'glm-4.7-flash', 'name' => 'GLM-4.7 Flash', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'glm-4.6', 'name' => 'GLM-4.6', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'cogview-3-plus', 'name' => 'CogView 3 Plus', 'capabilities' => ['image_generation']],
            ],
            'openrouter' => [
                ['model' => 'anthropic/claude-sonnet-4-6', 'name' => 'Claude Sonnet 4.6', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'openai/gpt-5.2', 'name' => 'GPT-5.2', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'google/gemini-3.1-pro', 'name' => 'Gemini 3.1 Pro', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'google/gemini-2.5-pro', 'name' => 'Gemini 2.5 Pro', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'google/gemini-2.5-flash', 'name' => 'Gemini 2.5 Flash', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'deepseek/deepseek-chat', 'name' => 'DeepSeek V3.2', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'deepseek/deepseek-reasoner', 'name' => 'DeepSeek R1', 'capabilities' => ['text_generation', 'seo']],
                ['model' => 'meta-llama/llama-4-maverick', 'name' => 'Llama 4 Maverick', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'meta-llama/llama-3.3-70b-instruct', 'name' => 'Llama 3.3 70B', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'qwen/qwen3.5-35b-a3b', 'name' => 'Qwen 3.5 35B', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'z-ai/glm-5', 'name' => 'GLM-5', 'capabilities' => ['text_generation', 'translation']],
            ],
            'huggingface' => [
                ['model' => 'black-forest-labs/FLUX.2-dev', 'name' => 'Flux 2 [dev]', 'capabilities' => ['image_generation']],
                ['model' => 'black-forest-labs/FLUX.1-schnell', 'name' => 'Flux.1 [schnell]', 'capabilities' => ['image_generation']],
                ['model' => 'stabilityai/stable-diffusion-3.5-large', 'name' => 'SD 3.5 Large', 'capabilities' => ['image_generation']],
                ['model' => 'Qwen/Qwen3.5-27B', 'name' => 'Qwen 3.5 27B', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'zai-org/GLM-5', 'name' => 'GLM-5', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'meta-llama/Llama-3.1-405B-Instruct', 'name' => 'Llama 3.1 405B', 'capabilities' => ['text_generation', 'translation']],
            ],
            'ollama' => [
                ['model' => 'llama4:maverick', 'name' => 'Llama 4 Maverick', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'llama3.3:70b', 'name' => 'Llama 3.3 70B', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'deepseek-r1:32b', 'name' => 'DeepSeek R1 32B', 'capabilities' => ['text_generation', 'seo']],
                ['model' => 'glm5:latest', 'name' => 'GLM-5', 'capabilities' => ['text_generation', 'translation', 'seo']],
                ['model' => 'qwen3:32b', 'name' => 'Qwen 3 32B', 'capabilities' => ['text_generation', 'translation']],
                ['model' => 'mistral-large:latest', 'name' => 'Mistral Large 3', 'capabilities' => ['text_generation', 'translation', 'seo']],
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
