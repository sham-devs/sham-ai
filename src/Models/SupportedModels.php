<?php

declare(strict_types=1);

namespace Sham\AI\Models;

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
            'openai' => 'OpenAI',
            'anthropic' => 'Anthropic',
            'google' => 'Google Gemini',
            'deepseek' => 'DeepSeek',
            'ollama' => 'Ollama (Local)',
            'zhipu' => 'Zhipu AI (GLM)',
        ];
    }

    /**
     * Get the supported models for a specific provider.
     *
     * @return array<array{model: string, name: string, capabilities: array<string>}>
     */
    public static function getModelsForProvider(string $provider): array
    {
        return match ($provider) {
            'openai' => [
                ['model' => 'gpt-5.2', 'name' => 'GPT-5.2 (Flagship)', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'gpt-5.1', 'name' => 'GPT-5.1', 'capabilities' => ['translation', 'content_generation']],
                ['model' => 'gpt-4.1', 'name' => 'GPT-4.1', 'capabilities' => ['translation', 'content_generation']],
                ['model' => 'o3-mini', 'name' => 'O3 Mini (Reasoning)', 'capabilities' => ['content_generation', 'seo']],
                ['model' => 'o1', 'name' => 'O1 High-Reasoning', 'capabilities' => ['content_generation', 'seo']],
            ],
            'anthropic' => [
                ['model' => 'claude-4-6-opus', 'name' => 'Claude 4.6 Opus', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'claude-4-6-sonnet', 'name' => 'Claude 4.6 Sonnet', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'claude-4-5-haiku', 'name' => 'Claude 4.5 Haiku', 'capabilities' => ['translation', 'content_generation']],
                ['model' => 'claude-3-5-sonnet-latest', 'name' => 'Claude 3.5 Sonnet (Legacy)', 'capabilities' => ['translation', 'content_generation', 'seo']],
            ],
            'google' => [
                ['model' => 'gemini-3.1-pro', 'name' => 'Gemini 3.1 Pro (Agentic)', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'gemini-3-flash', 'name' => 'Gemini 3 Flash', 'capabilities' => ['translation', 'content_generation']],
                ['model' => 'gemini-3-pro', 'name' => 'Gemini 3 Pro', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'gemini-2.0-flash', 'name' => 'Gemini 2.0 Flash (Legacy)', 'capabilities' => ['translation', 'content_generation']],
            ],
            'deepseek' => [
                ['model' => 'deepseek-chat', 'name' => 'DeepSeek V3.2', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'deepseek-reasoner', 'name' => 'DeepSeek R1 (Advanced reasoning)', 'capabilities' => ['content_generation', 'seo']],
            ],
            'ollama' => [
                ['model' => 'llama3.3', 'name' => 'Llama 3.3', 'capabilities' => ['translation', 'content_generation']],
                ['model' => 'qwen2.5', 'name' => 'Qwen 2.5', 'capabilities' => ['translation', 'content_generation']],
                ['model' => 'mistral-small', 'name' => 'Mistral Small', 'capabilities' => ['translation', 'content_generation']],
            ],
            'zhipu' => [
                ['model' => 'glm-4', 'name' => 'GLM-4', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'glm-4-flash', 'name' => 'GLM-4 Flash', 'capabilities' => ['translation', 'content_generation']],
                ['model' => 'glm-3-turbo', 'name' => 'GLM-3 Turbo', 'capabilities' => ['translation', 'content_generation']],
            ],
            default => [],
        };
    }

    /**
     * Get the information for a specific model.
     *
     * @return array{model: string, name: string, capabilities: array<string>}|null
     */
    public static function getModelInfo(string $provider, string $model): ?array
    {
        $models = self::getModelsForProvider($provider);

        return collect($models)->firstWhere('model', $model);
    }
}
