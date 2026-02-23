<?php

declare(strict_types=1);

namespace Sham\AI;

use App\Support\Translation\TranslatablePackageInterface;

class AIPackage implements TranslatablePackageInterface
{
    public function getPackageName(): string
    {
        return 'Sham AI';
    }

    public function getPackageId(): string
    {
        return 'sham-ai';
    }

    public function getTranslationNamespace(): string
    {
        return $this->getPackageId();
    }

    public function getScanPaths(): array
    {
        return ['src'];
    }

    public function getTranslationPath(): string
    {
        return 'resources/lang';
    }

    public function getPackageRoot(): string
    {
        return __DIR__.'/..';
    }

    public function getSupportedLocales(): array
    {
        return ['en', 'ar'];
    }

    public function getSettingsProviderClass(): ?string
    {
        return \Sham\AI\Settings\AISettingsProvider::class;
    }
}
