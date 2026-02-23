<?php

declare(strict_types=1);

namespace Sham\AI;

use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Get the package identifier.
     */
    protected function getPackageId(): string
    {
        return 'sham-ai';
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ai.php', $this->getPackageId());

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
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', $this->getPackageId());

        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path('vendor/' . $this->getPackageId()),
        ], $this->getPackageId() . '-translations');

        // Register AI settings provider via Sham registry
        if (class_exists(\App\Support\Sham::class) && method_exists(\App\Support\Sham::class, 'registerSettingsProvider')) {
            \App\Support\Sham::registerSettingsProvider(\Sham\AI\Settings\AISettingsProvider::class);
        } elseif (class_exists(\App\Support\Sham::class)) {
            // Fallback for older Sham implementation
            \App\Support\Sham::registerSettingsTab([
                'key' => $this->getPackageId(),
                'label' => $this->getPackageId() . '::settings.tab_label',
                'icon' => 'ic:outline-auto-awesome',
                'order' => 5,
                'permission' => 'manage settings',
                'actions' => ['test_connection'],
            ]);
        }
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Sham\AI\Console\Commands\AIScanCommand::class,
            ]);
        }
    }
}
