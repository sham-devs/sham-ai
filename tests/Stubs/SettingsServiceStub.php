<?php

declare(strict_types=1);

namespace Sham\AI\Tests\Stubs;

use Sham\Core\Contracts\Settings\SettingsServiceInterface;

/**
 * Standalone in-memory SettingsService for isolated tests (post-Phase-0).
 * Binds to SettingsServiceInterface via $app->bind() in TestCase.
 */
class SettingsServiceStub implements SettingsServiceInterface
{
    /** @var array<string, mixed> */
    protected array $store = [];

    /** @param array<string, mixed> $initial */
    public function __construct(array $initial = [])
    {
        $this->store = $initial;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->store[$key] ?? $default;
    }

    public function set(string $key, mixed $value): bool
    {
        $this->store[$key] = $value;

        return true;
    }

    public function getValuesForGroup(string $group): array
    {
        $result = [];

        foreach ($this->store as $key => $value) {
            if (str_starts_with($key, $group.'.')) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function updateMany(array $settings, string $prefix = ''): array
    {
        $updated = [];

        foreach ($settings as $key => $value) {
            $fullKey = $prefix !== '' ? $prefix.'.'.$key : $key;
            $this->store[$fullKey] = $value;
            $updated[$fullKey] = $value;
        }

        return $updated;
    }
}
