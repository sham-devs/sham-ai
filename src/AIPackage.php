<?php

declare(strict_types=1);

namespace Sham\AI;

use Sham\AI\Settings\AISettingsProvider;
use Sham\Core\Plugins\BasePlugin;

class AIPackage extends BasePlugin
{
    public function getName(): string
    {
        return 'Sham AI';
    }

    public function getId(): string
    {
        return 'sham-ai';
    }

    public function getSettingsProviderClass(): ?string
    {
        return AISettingsProvider::class;
    }
}
