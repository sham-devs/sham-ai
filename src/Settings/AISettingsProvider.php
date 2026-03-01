<?php

declare(strict_types=1);

namespace Sham\AI\Settings;

use App\Support\Settings\Concerns\HasSettingsActions;
use App\Support\Settings\DefinesActionsInterface;
use App\Support\Settings\HasSettingsStructure;
use Sham\AI\AIService;
use Sham\AI\Settings\Concerns\AISettingsCards;
use Sham\AI\Settings\Concerns\AISettingsFields;

/**
 * AI Settings Provider
 *
 * Field definitions and cards sections are in separate concern files.
 * Implements DefinesActionsInterface for flexible action handling.
 */
class AISettingsProvider extends \App\Support\Settings\BaseSettingsProvider implements DefinesActionsInterface, HasSettingsStructure
{
    use AISettingsCards;
    use AISettingsFields;
    use HasSettingsActions;

    public function __construct(
        protected AIService $aiService
    ) {
        $this->bootActions();
    }

    /**
     * Initialize action definitions.
     */
    protected function bootActions(): void
    {
        $pkg = $this->transPrefix();

        // Section-level save action
        $this->defineAction(
            \App\Support\Settings\ValueObjects\SettingsActionDefinition::save('settings.action.save_section', 'section')
                ->withVariant('primary')
                ->withIcon('save')
        );

        // Section-level reset action
        $this->defineAction(
            \App\Support\Settings\ValueObjects\SettingsActionDefinition::reset('settings.action.reset_defaults', 'section')
                ->withVariant('ghost')
                ->withIcon('refresh')
                ->withConfirm('settings.action.confirm_reset')
        );

        // Custom action: Test AI connection
        $this->defineAction(
            \App\Support\Settings\ValueObjects\SettingsActionDefinition::custom('test_connection', 'settings.messages.test_connection', 'custom', 'section')
                ->withVariant('secondary')
                ->withIcon('wifi')
        );
    }

    public function getId(): string
    {
        return 'sham-ai';
    }

    public function getTabDefinition(): array
    {
        $pkg = $this->transPrefix();

        return [
            'key' => $this->getId(),
            'label' => $pkg.'settings.tab.label',
            'title' => $pkg.'settings.tab.title',
            'description' => $pkg.'settings.tab.description',
            'icon' => 'ic:outline-auto-awesome',
            'order' => 5,
            'permission' => 'manage settings',
        ];
    }

    public function getStructure(): array
    {
        $id = $this->getId();

        return [
            'sections' => [
                'general' => array_merge([
                    'key' => 'general',
                    'icon' => 'ic:outline-settings',
                    'settings_keys' => [$id.'.enabled'],
                ], $this->getTranslationAttributes('settings.sections.general.title', 'title'),
                    $this->getTranslationAttributes('settings.sections.general.description', 'description')),

                'models' => array_merge([
                    'key' => 'models',
                    'icon' => 'ic:outline-smart-toy',
                    'settings_keys' => [$id.'.models'],
                ], $this->getTranslationAttributes('settings.sections.models.title', 'title'),
                    $this->getTranslationAttributes('settings.sections.models.description', 'description')),
            ],
        ];
    }

    /**
     * Get metadata for frontend rendering.
     *
     * Extends parent to include action definitions.
     */
    public function getMetadata(): array
    {
        $pkg = $this->transPrefix();
        $id = $this->getId();

        $metadata = array_merge([
            'pattern' => 'basic',
        ], $this->getStructure());

        // Add action definitions
        $metadata['actions'] = $this->getActionDefinitionsForMetadata();

        // Add supported providers and models for the UI
        $metadata['available_providers'] = collect(\Sham\AI\Models\SupportedModels::getProviders())->map(fn ($label, $value) => ['value' => $value, 'label' => $label])->values()->toArray();
        $metadata['available_models'] = collect(\Sham\AI\Models\SupportedModels::getProviders())->mapWithKeys(function ($label, $provider) {
            return [$provider => collect(\Sham\AI\Models\SupportedModels::getModelsForProvider($provider))->map(fn ($m) => [
                'value' => $m['model'],
                'label' => $m['name'],
            ])->values()->toArray()];
        })->toArray();

        $pkg = $this->transPrefix();
        $id = $this->getId();

        $metadata['collection_schemas'] = [
            $id.'.models' => [
                'fields' => [
                    [
                        'key' => 'name',
                        'type' => 'string',
                        'input_type' => 'text',
                        'label' => $pkg.'settings.models.name',
                        'required' => true,
                        'default' => 'New Model',
                    ],
                    [
                        'key' => 'enabled',
                        'type' => 'boolean',
                        'input_type' => 'toggle',
                        'label' => $pkg.'settings.models.enabled',
                        'default' => true,
                    ],
                    [
                        'key' => 'provider',
                        'type' => 'string',
                        'input_type' => 'select',
                        'label' => $pkg.'settings.models.provider',
                        'required' => true,
                        'options_from_metadata' => 'available_providers',
                    ],
                    [
                        'key' => 'model',
                        'type' => 'string',
                        'input_type' => 'select',
                        'label' => $pkg.'settings.models.model',
                        'required' => true,
                        'options_dependent' => [
                            'on' => 'provider',
                            'metadata_key' => 'available_models',
                        ],
                    ],
                    [
                        'key' => 'capabilities',
                        'type' => 'array',
                        'input_type' => 'select_multiple',
                        'label' => $pkg.'settings.models.capabilities',
                        'options' => [
                            ['value' => 'translation', 'label' => $pkg.'settings.capabilities.translation'],
                            ['value' => 'content_generation', 'label' => $pkg.'settings.capabilities.content_generation'],
                            ['value' => 'seo', 'label' => $pkg.'settings.capabilities.seo'],
                        ],
                        'default' => ['content_generation'],
                    ],
                ],
                'provider_config_fields' => [
                    'openai' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.organization_id', 'type' => 'string', 'input_type' => 'text', 'label' => 'Organization ID'],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api.openai.com/v1'],
                    ],
                    'anthropic' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api.anthropic.com'],
                    ],
                    'google' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                    ],
                    'ollama' => [
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'http://localhost:11434', 'required' => true],
                    ],
                    'deepseek' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api.deepseek.com'],
                    ],
                    'zhipu' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://open.bigmodel.cn/api/paas/v4'],
                    ],
                ],
            ],
        ];

        return $metadata;
    }

    /**
     * Execute a custom action.
     *
     * @param  array<string, mixed>  $payload
     * @return array{success: bool, data?: array, errors?: array, message?: string, progress_id?: string}
     */
    public function executeAction(
        string $actionKey,
        array $payload,
        string $level,
        ?string $sectionKey = null,
        ?string $groupKey = null
    ): array {
        return match ($actionKey) {
            'save' => $this->executeSaveAction($payload, $sectionKey, $groupKey),
            'reset' => $this->executeResetAction($sectionKey, $groupKey),
            'test_connection' => $this->executeTestConnection(),
            default => [
                'success' => false,
                'message' => "Unknown action: {$actionKey}",
            ],
        };
    }

    /**
     * Execute test connection action.
     *
     * @return array{success: bool, message?: string}
     */
    protected function executeTestConnection(): array
    {
        try {
            $configured = $this->aiService->isConfigured();

            return [
                'success' => $configured,
                'message' => $configured ? 'AI connection successful' : 'AI not configured',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle an action for this provider (legacy support).
     *
     * @return array{success: bool, data?: array, errors?: array, message?: string}
     */
    public function handleAction(\App\Support\Settings\ValueObjects\SettingsAction $action): array
    {
        return match ($action->actionType) {
            'save' => $this->executeSaveAction($action->payload, null, null),
            'toggle' => $this->executeSaveAction($action->payload, null, null),
            default => [
                'success' => false,
                'message' => "Unknown action: {$action->actionType}",
            ],
        };
    }

    /**
     * Get the keys that should be treated as translatable.
     *
     * @return array<int, string>
     */
    public function getTranslatableKeys(): array
    {
        return [];
    }
}
