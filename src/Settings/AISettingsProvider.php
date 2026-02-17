<?php

declare(strict_types=1);

namespace Sham\AI\Settings;

use App\Contracts\HasSettingsStructure;
use App\Contracts\SettingsProviderInterface;
use Sham\AI\AIService;
use Sham\AI\Settings\Concerns\AISettingsCards;
use Sham\AI\Settings\Concerns\AISettingsFields;

/**
 * AI Settings Provider
 *
 * Field definitions and cards sections are in separate concern files.
 */
class AISettingsProvider implements HasSettingsStructure, SettingsProviderInterface
{
    use AISettingsCards;
    use AISettingsFields;

    public function __construct(
        protected AIService $aiService
    ) {}

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
            'label' => $pkg . '::settings.tab_label',
            'title' => $pkg . '::settings.settings_title',
            'description' => $pkg . '::settings.settings_description',
            'icon' => 'ic:outline-auto-awesome',
            'order' => 5,
            'permission' => 'manage settings',
        ];
    }
}
