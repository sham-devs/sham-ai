<?php

declare(strict_types=1);

namespace Sham\AI;

use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AIService::class, function ($app) {
            return new AIService($app->make(\App\Services\Settings\SettingsService::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Settings Tab
        if (class_exists(\App\Support\Sham::class)) {
            \App\Support\Sham::registerSettingsTab([
                'key' => 'ai',
                'label' => 'AI Translation',
                'icon' => 'ic:outline-auto-awesome',
                'order' => 5,
                'permission' => 'manage settings',
                'actions' => ['test_connection'],
            ]);
        }
    }
}
