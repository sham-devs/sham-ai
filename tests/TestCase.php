<?php

declare(strict_types=1);

namespace Sham\AI\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use Prism\Prism\PrismServiceProvider;
use Sham\AI\Providers\AIServiceProvider;
use Sham\AI\Tests\Stubs\PackageScannerStub;
use Sham\AI\Tests\Stubs\SettingsServiceStub;
use Sham\Core\Contracts\Localization\PackageScannerInterface;
use Sham\Core\Contracts\Settings\SettingsServiceInterface;
use Sham\Core\Plugins\PluginRegistry;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            PrismServiceProvider::class,
            AIServiceProvider::class,
        ];
    }

    /**
     * @param  array<string, mixed>  $initialSettings
     */
    protected function bindSettings($app, array $initialSettings = []): SettingsServiceStub
    {
        $stub = new SettingsServiceStub($initialSettings);

        $app->singleton(SettingsServiceInterface::class, fn () => $stub);

        return $stub;
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('logging.default', 'single');
        $app['config']->set('logging.channels.single', [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ]);

        // Post-Phase-0 isolation: bind Sham\Core contracts to standalone stubs.
        $app->singleton(SettingsServiceInterface::class, fn () => new SettingsServiceStub);
        $app->singleton(PackageScannerInterface::class, fn () => new PackageScannerStub);
        $app->singleton(PluginRegistry::class, fn () => new PluginRegistry);
    }
}
