<?php

declare(strict_types=1);

namespace Sham\AI\Settings\Concerns;

/**
 * AI Settings Field Definitions
 */
trait AISettingsFields
{
    public function getFieldsDefinition(): array
    {
        $pkg = static::getPackageId() . '::';

        return [
            ['key' => static::getPackageId() . '.enabled', 'type' => 'boolean', 'input_type' => 'toggle', 'label' => $pkg . 'ai.enabled'],
            ['key' => static::getPackageId() . '.provider', 'type' => 'string', 'input_type' => 'select', 'label' => $pkg . 'ai.provider', 'options' => ['openai' => 'OpenAI', 'anthropic' => 'Anthropic', 'gemini' => 'Google Gemini']],
            ['key' => static::getPackageId() . '.model', 'type' => 'string', 'input_type' => 'text', 'label' => $pkg . 'ai.model'],
            ['key' => static::getPackageId() . '.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => $pkg . 'ai.api_key', 'is_encrypted' => true, 'is_sensitive' => true],
            ['key' => static::getPackageId() . '.temperature', 'type' => 'float', 'input_type' => 'number', 'label' => $pkg . 'ai.temperature'],
        ];
    }

    public function getValidationRules(array $data): array
    {
        $pkg = static::getPackageId();

        return [
            $pkg . '.enabled' => ['boolean'],
            $pkg . '.provider' => ['sometimes', 'required', 'string', 'in:openai,anthropic,gemini'],
            $pkg . '.model' => ['sometimes', 'required', 'string', 'max:255'],
            $pkg . '.api_key' => ['nullable', 'string', 'max:255'],
            $pkg . '.temperature' => ['numeric', 'min:0', 'max:1'],
        ];
    }

    /**
     * Prepare data for validation.
     *
     * Note: The controller already handles type conversions via prepareRequestDataForValidation()
     * before validation. This method is called AFTER validation with already-converted data.
     */
    public function prepareForValidation(array $data): array
    {
        // Data is already prepared by the controller (prepareRequestDataForValidation + undot)
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
        $pkg = static::getPackageId();

        return app(\App\Services\Settings\SettingsService::class)->getValuesForGroup($pkg);
    }

    public function getActions(): array
    {
        $pkg = static::getPackageId() . '::';

        return [
            ['key' => 'test_connection', 'label' => $pkg . 'ai.test_connection', 'route' => 'settings.ai.test', 'method' => 'POST'],
        ];
    }

    /**
     * Get default settings for seeding.
     *
     * @return array<int, array>
     */
    public function getDefaultSettings(): array
    {
        $pkg = static::getPackageId() . '::';
        $fullPkg = static::getPackageId();

        return [
            [
                'key' => $pkg . '.enabled',
                'value' => false,
                'type' => 'bool',
                'group' => $pkg,
                'input_type' => 'toggle',
                'display_order' => 1,
                'en' => ['label' => $pkg . 'settings.ai_enabled_label', 'description' => $pkg . 'settings.ai_enabled_desc'],
                'ar' => ['label' => $pkg . 'settings.ai_enabled_label', 'description' => $pkg . 'settings.ai_enabled_desc'],
            ],
            [
                'key' => $pkg . '.provider',
                'value' => 'openai',
                'type' => 'string',
                'group' => $pkg,
                'input_type' => 'select',
                'display_order' => 2,
                'validation_rules' => ['required', 'string', 'in:openai,anthropic,gemini'],
                'options' => ['openai' => 'OpenAI', 'anthropic' => 'Anthropic', 'gemini' => 'Google Gemini'],
                'en' => ['label' => $pkg . 'settings.ai_provider_label', 'description' => $pkg . 'settings.ai_provider_desc'],
                'ar' => ['label' => $pkg . 'settings.ai_provider_label', 'description' => $pkg . 'settings.ai_provider_desc'],
            ],
            [
                'key' => $pkg . '.model',
                'value' => 'gpt-4o',
                'type' => 'string',
                'group' => $pkg,
                'input_type' => 'text',
                'display_order' => 3,
                'validation_rules' => ['required', 'string', 'max:255'],
                'en' => ['label' => $pkg . 'settings.ai_model_label', 'description' => $pkg . 'settings.ai_model_desc'],
                'ar' => ['label' => $pkg . 'settings.ai_model_label', 'description' => $pkg . 'settings.ai_model_desc'],
            ],
            [
                'key' => $pkg . '.api_key',
                'value' => null,
                'type' => 'string',
                'group' => $pkg,
                'input_type' => 'password',
                'is_encrypted' => true,
                'is_sensitive' => true,
                'display_order' => 4,
                'validation_rules' => ['nullable', 'string', 'max:255'],
                'en' => ['label' => $pkg . 'settings.ai_api_key_label', 'description' => $pkg . 'settings.ai_api_key_desc'],
                'ar' => ['label' => $pkg . 'settings.ai_api_key_label', 'description' => $pkg . 'settings.ai_api_key_desc'],
            ],
            [
                'key' => $pkg . '.temperature',
                'value' => 0.3,
                'type' => 'float',
                'group' => $pkg,
                'input_type' => 'slider',
                'options' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                'display_order' => 6,
                'validation_rules' => ['required', 'numeric', 'min:0', 'max:1'],
                'en' => ['label' => $pkg . 'settings.ai_temperature_label', 'description' => $pkg . 'settings.ai_temperature_desc'],
                'ar' => ['label' => $pkg . 'settings.ai_temperature_label', 'description' => $pkg . 'settings.ai_temperature_desc'],
            ],
        ];
    }
}
