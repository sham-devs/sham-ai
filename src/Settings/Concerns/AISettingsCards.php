<?php

declare(strict_types=1);

namespace Sham\AI\Settings\Concerns;

/**
 * AI Settings Cards Sections
 */
trait AISettingsCards
{
    public function getMetadata(): array
    {
        return [
            'pattern' => 'basic',
        ];
    }

    public function handleAction(\App\Support\Settings\ValueObjects\SettingsAction $action): array
    {
        return match ($action->actionType) {
            'save' => $this->handleSave($action->payload),
            'toggle' => $this->handleToggle($action->payload),
            default => [
                'success' => false,
                'message' => "Unknown action: {$action->actionType}",
            ],
        };
    }

    protected function handleSave(array $payload): array
    {
        try {
            $preparedData = $this->prepareForValidation($payload);
            $rules = $this->getValidationRules($preparedData);

            $validator = \Illuminate\Support\Facades\Validator::make($preparedData, $rules);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'errors' => $validator->errors()->toArray(),
                ];
            }

            $validated = $validator->validated();
            $this->save($validated);

            return [
                'success' => true,
                'data' => $validated,
            ];
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('AISettingsProvider::handleSave error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while saving settings.',
            ];
        }
    }

    protected function handleToggle(array $payload): array
    {
        return $this->handleSave($payload);
    }

    public function validateStructure(): array
    {
        $errors = [];
        $warnings = [];

        if (empty($this->getFieldsDefinition())) {
            $errors[] = 'AI provider must have at least one field';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    public function getStructureInfo(): array
    {
        $fields = $this->getFieldsDefinition();

        return [
            'groups_count' => empty($fields) ? 0 : 1,
            'patterns' => empty($fields) ? [] : ['basic'],
        ];
    }
}
