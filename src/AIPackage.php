<?php

declare(strict_types=1);

namespace Sham\AI;

class AIPackage extends \Sham\Core\Plugins\BasePlugin
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
        return \Sham\AI\Settings\AISettingsProvider::class;
    }
}
