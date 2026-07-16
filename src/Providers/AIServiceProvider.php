<?php

declare(strict_types=1);

namespace Sham\AI\Providers;

use Sham\AI\AIPackage;
use Sham\AI\AIService;
use Sham\AI\Providers\HuggingFace\FluxProvider;
use Sham\AI\Providers\HuggingFace\LlamaProvider;
use Sham\AI\Providers\HuggingFace\MistralProvider;
use Sham\AI\Providers\HuggingFace\NllbProvider;
use Sham\AI\Providers\HuggingFace\OpusMtProvider;
use Sham\AI\Providers\HuggingFace\QwenProvider;
use Sham\AI\Providers\HuggingFace\SDProvider;
use Sham\AI\Providers\HuggingFace\SdxlProvider;
use Sham\Core\Contracts\Plugins\PluginInterface;
use Sham\Core\Contracts\Settings\SettingsServiceInterface;
use Sham\Core\Plugins\PluginServiceProvider;

class AIServiceProvider extends PluginServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function getPlugin(): PluginInterface
    {
        return new AIPackage;
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $plugin = $this->getPlugin();

        $this->app->singleton(AIService::class, function ($app) use ($plugin) {
            return new AIService(function (string $key, $default = null) use ($app, $plugin) {
                if ($app->bound(SettingsServiceInterface::class)) {
                    return $app->make(SettingsServiceInterface::class)->get($key, $default);
                }

                return config($plugin->getId().'.'.$key, $default);
            });
        });
    }

    /**
     * Bootstrap services.
     */
    protected function packageBoot(PluginInterface $plugin): void
    {
        if ($this->app->runningInConsole()) {
            // AIScanCommand: removed — auto-registered by nawah core
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
        $this->app->make('prism-manager')->extend('zhipu', fn ($app, $config) => new ZhipuProvider($config['api_key']));

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
            $this->app->make('prism-manager')->extend($name, fn ($app, $config) => new $class($config));
        }
    }
}
