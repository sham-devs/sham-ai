<?php

declare(strict_types=1);

namespace Sham\AI;

use App\Support\Plugins\PluginServiceProvider;

class AIServiceProvider extends PluginServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function getPlugin(): \App\Support\Plugins\PluginInterface
    {
        return new AIPackage();
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $plugin = $this->getPlugin();
        $this->mergeConfigFrom(__DIR__.'/../config/ai.php', $plugin->getId());

        $this->app->singleton(AIService::class, function ($app) use ($plugin) {
            return new AIService(function (string $key, $default = null) use ($app, $plugin) {
                if ($app->bound(\App\Services\Settings\SettingsService::class)) {
                    return $app->make(\App\Services\Settings\SettingsService::class)->get($key, $default);
                }

                return config($plugin->getId() . '.' . $key, $default);
            });
        });
    }

    /**
     * Bootstrap services.
     */
    protected function packageBoot(\App\Support\Plugins\PluginInterface $plugin): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Sham\AI\Console\Commands\AIScanCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path('vendor/' . $plugin->getId()),
        ], $plugin->getId() . '-translations');
    }
}
