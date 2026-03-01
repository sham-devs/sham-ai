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
            ['key' => $id.'.enabled', 'type' => 'boolean', 'input_type' => 'toggle', 'label' => $pkg.'settings.field.enabled.label'],
            [
                'key' => $id.'.models',
                'type' => 'array',
                'input_type' => 'collection_list',
                'label' => $pkg.'settings.field.models.label',
                'options' => [
                    'item_label' => 'name',
                    'item_description' => 'model',
                    'icon' => 'ic:outline-smart-toy',
                    'can_add' => true,
                    'can_delete' => true,
                    'modal_title' => $pkg.'settings.models.label',
                    'add_label' => $pkg.'settings.models.add',
                    'edit_label' => $pkg.'settings.models.edit',
                    'id_key' => 'id',
                ],
            ],
        ];
    }

    public function getValidationRules(array $data): array
    {
        $id = $this->getId();

        return [
            $id.'.enabled' => ['boolean'],
            $id.'.models' => ['nullable', 'array'],
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
        $id = $this->getId();

        // Handle 'enabled' separately or via dot
        if (isset($validated[$id]['enabled'])) {
            $settingsService->set($id.'.enabled', (bool) $validated[$id]['enabled']);
        }

        // Handle 'models' - use AIService to handle encryption
        if (isset($validated[$id]['models'])) {
            app(\Sham\AI\AIService::class)->updateModels($validated[$id]['models']);
        }
    }

    public function getValues(): array
    {
        return app(\App\Services\Settings\SettingsService::class)->getValuesForGroup($this->getId());
    }

    public function getActions(): array
    {
        $pkg = $this->transPrefix();

        return [
            ['key' => 'test_connection', 'label' => $pkg.'messages.test_connection', 'route' => 'settings.ai.test', 'method' => 'POST'],
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
                'key' => $id.'.enabled',
                'value' => false,
                'type' => 'bool',
                'group' => $id,
                'input_type' => 'toggle',
                'display_order' => 1,
                'en' => ['label' => $pkg.'settings.field.enabled.label', 'description' => $pkg.'settings.field.enabled.desc'],
                'ar' => ['label' => $pkg.'settings.field.enabled.label', 'description' => $pkg.'settings.field.enabled.desc'],
            ],
            [
                'key' => $id.'.models',
                'value' => [],
                'type' => 'array',
                'group' => $id,
                'input_type' => 'collection_list',
                'display_order' => 2,
                'options' => [
                    'item_label' => 'name',
                    'item_description' => 'model',
                    'icon' => 'ic:outline-smart-toy',
                    'can_add' => true,
                    'can_delete' => true,
                    'modal_title' => $pkg.'settings.models.label',
                    'add_label' => $pkg.'settings.models.add',
                    'edit_label' => $pkg.'settings.models.edit',
                    'id_key' => 'id',
                ],
                'en' => ['label' => $pkg.'settings.field.models.label', 'description' => $pkg.'settings.field.models.desc'],
                'ar' => ['label' => $pkg.'settings.field.models.label', 'description' => $pkg.'settings.field.models.desc'],
            ],
        ];
    }
}
