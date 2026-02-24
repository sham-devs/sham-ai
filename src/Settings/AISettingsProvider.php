<?php

declare(strict_types=1);

namespace Sham\AI\Settings;

use App\Support\Settings\DefinesActionsInterface;
use App\Support\Settings\HasSettingsStructure;
use App\Support\Settings\SettingsProviderInterface;
use App\Support\Settings\Concerns\HasSettingsActions;
use Sham\AI\AIService;
use Sham\AI\Settings\Concerns\AISettingsCards;
use Sham\AI\Settings\Concerns\AISettingsFields;

/**
 * AI Settings Provider
 *
 * Field definitions and cards sections are in separate concern files.
 * Implements DefinesActionsInterface for flexible action handling.
 */
class AISettingsProvider implements DefinesActionsInterface, HasSettingsStructure, SettingsProviderInterface
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
        $pkg = static::getPackageId();

        // Section-level save action
        $this->defineAction(
            \App\Support\Settings\ValueObjects\SettingsActionDefinition::save($pkg . '.settings.save_section', 'section')
                ->withVariant('primary')
                ->withIcon('save')
        );

        // Section-level reset action
        $this->defineAction(
            \App\Support\Settings\ValueObjects\SettingsActionDefinition::reset($pkg . '.settings.reset_defaults', 'section')
                ->withVariant('ghost')
                ->withIcon('refresh')
                ->withConfirm($pkg . '.settings.confirm_reset')
        );

        // Custom action: Test AI connection
        $this->defineAction(
            \App\Support\Settings\ValueObjects\SettingsActionDefinition::custom('test_connection', $pkg . '.settings.test_connection', 'custom', 'section')
                ->withVariant('secondary')
                ->withIcon('wifi')
        );
    }

    public static function getPackageId(): string
    {
        return 'sham-ai';
    }

    public function getProviderId(): string
    {
        return static::getPackageId();
    }

    public function getTabDefinition(): array
    {
        $pkg = static::getPackageId();

        return [
            'key' => $pkg,
            'label' => $pkg . '.settings.tab_label',
            'title' => $pkg . '.settings.settings_title',
            'description' => $pkg . '.settings.settings_description',
            'icon' => 'ic:outline-auto-awesome',
            'order' => 5,
            'permission' => 'manage settings',
        ];
    }

    /**
     * Get metadata for frontend rendering.
     *
     * Extends parent to include action definitions.
     */
    public function getMetadata(): array
    {
        $metadata = [
            'pattern' => 'basic',
        ];

        // Add action definitions
        $metadata['actions'] = $this->getActionDefinitionsForMetadata();

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
