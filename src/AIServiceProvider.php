<?php

declare(strict_types=1);

namespace Sham\AI;

use App\Support\Plugins\PluginServiceProvider;

class AIServiceProvider extends PluginServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'sham-ai';
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'AI Configuration';
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsProviderClass(): ?string
    {
        return \Sham\AI\Settings\AISettingsProvider::class;
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ai.php', $this->getId());

        $this->app->singleton(AIService::class, function ($app) {
            return new AIService(function (string $key, $default = null) use ($app) {
                if ($app->bound(\App\Services\Settings\SettingsService::class)) {
                    return $app->make(\App\Services\Settings\SettingsService::class)->get($key, $default);
                }

                return config($key, $default);
            });
        });
    }

    /**
     * Bootstrap services.
     */
    protected function packageBoot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Sham\AI\Console\Commands\AIScanCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path('vendor/' . $this->getId()),
        ], $this->getId() . '-translations');
    }
}
