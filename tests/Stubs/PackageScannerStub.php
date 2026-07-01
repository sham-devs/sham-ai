<?php

declare(strict_types=1);

namespace Sham\AI\Tests\Stubs;

use Sham\Core\Contracts\Localization\PackageScannerInterface;
use Sham\Core\Contracts\Plugins\PluginInterface;

/**
 * Standalone PackageScanner for isolated tests (post-Phase-0).
 */
class PackageScannerStub implements PackageScannerInterface
{
    public function scan(PluginInterface $plugin): array
    {
        return [];
    }

    public function scanWithViolations(PluginInterface $plugin): array
    {
        return [];
    }

    public function detectViolations(array $foundKeys, string $expectedNamespace): array
    {
        return [];
    }

    public function findUnusedKeys(PluginInterface $plugin): array
    {
        return [];
    }

    public function purgeUnusedKeys(PluginInterface $plugin, bool $dryRun = true): array
    {
        return [];
    }
}
