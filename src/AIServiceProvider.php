<?php

declare(strict_types=1);

namespace Sham\AI;

use App\Support\Plugins\PluginServiceProvider;

use Sham\AI\Prism\Providers\ZhipuProvider;
use Sham\AI\Prism\Providers\HuggingFace\NllbProvider;
use Sham\AI\Prism\Providers\HuggingFace\OpusMtProvider;
use Sham\AI\Prism\Providers\HuggingFace\LlamaProvider;
use Sham\AI\Prism\Providers\HuggingFace\QwenProvider;
use Sham\AI\Prism\Providers\HuggingFace\MistralProvider;
use Sham\AI\Prism\Providers\HuggingFace\FluxProvider;
use Sham\AI\Prism\Providers\HuggingFace\SDProvider;
use Sham\AI\Prism\Providers\HuggingFace\SdxlProvider;

class AIServiceProvider extends PluginServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function getPlugin(): \App\Support\Plugins\PluginInterface
    {
        return new AIPackage;
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

                return config($plugin->getId().'.'.$key, $default);
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
            __DIR__.'/../resources/lang' => lang_path('vendor/'.$plugin->getId()),
        ], $plugin->getId().'-translations');

        // Register Custom Prism Providers
        $this->registerPrismProviders();
    }

    protected function registerPrismProviders(): void
    {
        // Zhipu
        $this->app->make('prism-manager')->extend('zhipu', fn($app, $config) => new ZhipuProvider($config['api_key']));

        // HuggingFace
        $providers = [
            'huggingface-nllb' => NllbProvider::class,
            'huggingface-opus-mt' => OpusMtProvider::class,
            'huggingface-llama' => LlamaProvider::class,
            'huggingface-qwen' => QwenProvider::class,
            'huggingface-mistral' => MistralProvider::class,
            'huggingface-flux' => FluxProvider::class,
            'huggingface-sd' => SDProvider::class,
            'huggingface-sdxl' => SdxlProvider::class,
        ];

        foreach ($providers as $name => $class) {
            $this->app->make('prism-manager')->extend($name, fn($app, $config) => new $class($config['api_key']));
        }
    }
}
