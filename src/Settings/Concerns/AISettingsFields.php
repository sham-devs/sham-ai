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
        $pkg = $this->transPrefix();
        $id = $this->getId();

        return [
            ['key' => $id . '.enabled', 'type' => 'boolean', 'input_type' => 'toggle', 'label' => $pkg . 'ai.messages.enabled'],
            ['key' => $id . '.provider', 'type' => 'string', 'input_type' => 'select', 'label' => $pkg . 'ai.messages.provider', 'options' => ['openai' => 'OpenAI', 'anthropic' => 'Anthropic', 'gemini' => 'Google Gemini']],
            ['key' => $id . '.model', 'type' => 'string', 'input_type' => 'text', 'label' => $pkg . 'ai.messages.model'],
            ['key' => $id . '.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => $pkg . 'ai.messages.api_key', 'is_encrypted' => true, 'is_sensitive' => true],
            ['key' => $id . '.temperature', 'type' => 'float', 'input_type' => 'number', 'label' => $pkg . 'ai.messages.temperature'],
        ];
    }

    public function getValidationRules(array $data): array
    {
        $id = $this->getId();

        return [
            $id . '.enabled' => ['boolean'],
            $id . '.provider' => ['sometimes', 'required', 'string', 'in:openai,anthropic,gemini'],
            $id . '.model' => ['sometimes', 'required', 'string', 'max:255'],
            $id . '.api_key' => ['nullable', 'string', 'max:255'],
            $id . '.temperature' => ['numeric', 'min:0', 'max:1'],
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
        return app(\App\Services\Settings\SettingsService::class)->getValuesForGroup($this->getId());
    }

    public function getActions(): array
    {
        $pkg = $this->transPrefix();

        return [
            ['key' => 'test_connection', 'label' => $pkg . 'ai.messages.test_connection', 'route' => 'settings.ai.test', 'method' => 'POST'],
        ];
    }

    /**
     * Get default settings for seeding.
     *
     * @return array<int, array>
     */
    public function getDefaultSettings(): array
    {
        $pkg = $this->transPrefix();
        $id = $this->getId();

        return [
            [
                'key' => $id . '.enabled',
                'value' => false,
                'type' => 'bool',
                'group' => $id,
                'input_type' => 'toggle',
                'display_order' => 1,
                'en' => ['label' => $pkg . 'ai.settings.ai_enabled_label', 'description' => $pkg . 'ai.settings.ai_enabled_desc'],
                'ar' => ['label' => $pkg . 'ai.settings.ai_enabled_label', 'description' => $pkg . 'ai.settings.ai_enabled_desc'],
            ],
            [
                'key' => $id . '.provider',
                'value' => 'openai',
                'type' => 'string',
                'group' => $id,
                'input_type' => 'select',
                'display_order' => 2,
                'validation_rules' => ['required', 'string', 'in:openai,anthropic,gemini'],
                'options' => ['openai' => 'OpenAI', 'anthropic' => 'Anthropic', 'gemini' => 'Google Gemini'],
                'en' => ['label' => $pkg . 'ai.settings.ai_provider_label', 'description' => $pkg . 'ai.settings.ai_provider_desc'],
                'ar' => ['label' => $pkg . 'ai.settings.ai_provider_label', 'description' => $pkg . 'ai.settings.ai_provider_desc'],
            ],
            [
                'key' => $id . '.model',
                'value' => 'gpt-4o',
                'type' => 'string',
                'group' => $id,
                'input_type' => 'text',
                'display_order' => 3,
                'validation_rules' => ['required', 'string', 'max:255'],
                'en' => ['label' => $pkg . 'ai.settings.ai_model_label', 'description' => $pkg . 'ai.settings.ai_model_desc'],
                'ar' => ['label' => $pkg . 'ai.settings.ai_model_label', 'description' => $pkg . 'ai.settings.ai_model_desc'],
            ],
            [
                'key' => $id . '.api_key',
                'value' => null,
                'type' => 'string',
                'group' => $id,
                'input_type' => 'password',
                'is_encrypted' => true,
                'is_sensitive' => true,
                'display_order' => 4,
                'validation_rules' => ['nullable', 'string', 'max:255'],
                'en' => ['label' => $pkg . 'ai.settings.ai_api_key_label', 'description' => $pkg . 'ai.settings.ai_api_key_desc'],
                'ar' => ['label' => $pkg . 'ai.settings.ai_api_key_label', 'description' => $pkg . 'ai.settings.ai_api_key_desc'],
            ],
            [
                'key' => $id . '.temperature',
                'value' => 0.3,
                'type' => 'float',
                'group' => $id,
                'input_type' => 'slider',
                'options' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                'display_order' => 6,
                'validation_rules' => ['required', 'numeric', 'min:0', 'max:1'],
                'en' => ['label' => $pkg . 'ai.settings.ai_temperature_label', 'description' => $pkg . 'ai.settings.ai_temperature_desc'],
                'ar' => ['label' => $pkg . 'ai.settings.ai_temperature_label', 'description' => $pkg . 'ai.settings.ai_temperature_desc'],
            ],
        ];
    }
}
