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
     * Get the default capabilities for a specific provider.
     * Used as a fallback when a model has no user-selected capabilities.
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
     * Get model configuration info and instructions for a specific provider.
     * Used in the settings UI to guide users on how to find the correct Model ID.
     */
    public static function getProviderModelInfo(string $provider): array
    {
        $pkg = 'sham-ai::sham-ai.settings.provider_instructions.';

        $base = [
            'how_to_find' => __($pkg.'how_to_find'),
            'example_label' => __($pkg.'example'),
        ];

        return array_merge($base, match ($provider) {
            'openai' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/openai.html',
                'instructions' => __($pkg.'openai.instructions'),
                'notes' => __($pkg.'openai.notes'),
                'example' => 'gpt-4o',
            ],
            'anthropic' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/anthropic.html',
                'instructions' => __($pkg.'anthropic.instructions'),
                'notes' => __($pkg.'anthropic.notes'),
                'example' => 'claude-3-7-sonnet-latest',
            ],
            'google' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/google.html',
                'instructions' => __($pkg.'google.instructions'),
                'notes' => __($pkg.'google.notes'),
                'example' => 'gemini-2.5-flash',
            ],
            'deepseek' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/deepseek.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => 'deepseek-chat',
            ],
            'xai' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/xai.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => 'grok-3-latest',
            ],
            'mistral' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/mistral.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => 'mistral-large-latest',
            ],
            'zhipu' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/zhipu.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => 'glm-4-plus',
            ],
            'ollama' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/ollama.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => 'llama3.2',
            ],
            'huggingface-flux' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-flux.html',
                'instructions' => __($pkg.'huggingface-flux.instructions'),
                'notes' => __($pkg.'huggingface-flux.notes'),
                'example' => 'black-forest-labs/FLUX.1-schnell',
            ],
            'huggingface-nllb' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-nllb.html',
                'instructions' => __($pkg.'huggingface-nllb.instructions'),
                'notes' => __($pkg.'huggingface-nllb.notes'),
                'example' => 'facebook/nllb-200-distilled-600M',
            ],
            'huggingface-opus-mt' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-opus-mt.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => 'Helsinki-NLP/opus-mt-en-ar',
            ],
            'huggingface-llama' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-llama.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => 'meta-llama/Llama-3.2-3B-Instruct',
            ],
            'huggingface-qwen' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-qwen.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => 'Qwen/Qwen2.5-72B-Instruct',
            ],
            'huggingface-mistral' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-mistral.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => 'mistralai/Mistral-7B-Instruct-v0.3',
            ],
            'huggingface-sd' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-sd.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => 'runwayml/stable-diffusion-v1-5',
            ],
            'huggingface-sdxl' => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/huggingface-sdxl.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => 'stabilityai/stable-diffusion-xl-base-1.0',
            ],
            default => [
                'url' => 'https://sham-packages.github.io/sham-ai/providers/index.html',
                'instructions' => __($pkg.'default.instructions'),
                'notes' => __($pkg.'default.notes'),
                'example' => '',
            ],
        });
    }
}
