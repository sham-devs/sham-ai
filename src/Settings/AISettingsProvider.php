<?php

declare(strict_types=1);

namespace Sham\AI\Settings;

use App\Contracts\SettingsProviderInterface;
use Sham\AI\AIService;

class AISettingsProvider implements SettingsProviderInterface
{
    public function __construct(
        protected AIService $aiService
    ) {}

    public function getTabDefinition(): array
    {
        return [
            'key' => 'ai',
            'label' => 'sham-ai::ai.enabled',
            'icon' => 'ic:outline-auto-awesome',
            'order' => 5,
            'permission' => 'manage settings',
        ];
    }

    public function getFieldsDefinition(): array
    {
        return [
            ['key' => 'ai.enabled', 'type' => 'boolean', 'input_type' => 'toggle', 'label' => 'sham-ai::ai.enabled'],
            ['key' => 'ai.provider', 'type' => 'string', 'input_type' => 'select', 'label' => 'sham-ai::ai.provider', 'options' => ['openai' => 'OpenAI', 'anthropic' => 'Anthropic', 'gemini' => 'Google Gemini']],
            ['key' => 'ai.model', 'type' => 'string', 'input_type' => 'text', 'label' => 'sham-ai::ai.model'],
            ['key' => 'ai.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'sham-ai::ai.api_key', 'is_encrypted' => true, 'is_sensitive' => true],
            ['key' => 'ai.temperature', 'type' => 'float', 'input_type' => 'number', 'label' => 'sham-ai::ai.temperature'],
            ['key' => 'ai.translation.enabled', 'type' => 'boolean', 'input_type' => 'toggle', 'label' => 'sham-ai::ai.translation_enabled'],
        ];
    }

    public function getValidationRules(array $data): array
    {
        return [
            'ai.enabled' => ['boolean'],
            'ai.provider' => ['sometimes', 'required', 'string', 'in:openai,anthropic,gemini'],
            'ai.model' => ['sometimes', 'required', 'string', 'max:255'],
            'ai.api_key' => ['nullable', 'string', 'max:255'],
            'ai.temperature' => ['numeric', 'min:0', 'max:1'],
            'ai.translation.enabled' => ['boolean'],
        ];
    }

    public function prepareForValidation(array $data): array
    {
        $data = \Illuminate\Support\Arr::undot($data);

        foreach (['ai.enabled', 'ai.translation.enabled'] as $key) {
            $val = data_get($data, $key);
            if ($val !== null) {
                data_set($data, $key, filter_var($val, FILTER_VALIDATE_BOOLEAN));
            }
        }
        return $data;
    }

    public function save(array $validated): void
    {
        $settingsService = app(\App\Services\Settings\SettingsService::class);
        
        // Dot the array to easily check keys against Settings table
        $dotted = \Illuminate\Support\Arr::dot($validated);
        
        // Filter null sensitive fields
        $toUpdate = [];
        foreach ($dotted as $key => $value) {
            if ($value === null) {
                $setting = \App\Models\Setting::where('key', $key)->first();
                if ($setting && ($setting->is_sensitive || $setting->is_encrypted)) {
                    continue;
                }
            }
            $toUpdate[$key] = $value;
        }

        $settingsService->updateMany($toUpdate);
    }

    public function getValues(): array
    {
        return app(\App\Services\Settings\SettingsService::class)
            ->getByGroup('ai')
            ->keyBy('key')
            ->toArray();
    }

    public function getActions(): array
    {
        return [
            ['key' => 'test_connection', 'label' => 'sham-ai::ai.test_connection', 'route' => 'settings.ai.test', 'method' => 'POST'],
        ];
    }
}
