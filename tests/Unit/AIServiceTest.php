<?php

declare(strict_types=1);

namespace Sham\AI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sham\AI\AIService;

class AIServiceTest extends TestCase
{
    public function test_ai_service_can_load_models_from_settings(): void
    {
        $modelsData = [
            [
                'id' => 'm1',
                'name' => 'OpenAI',
                'provider' => 'openai',
                'model' => 'gpt-4o',
                'enabled' => true,
                'capabilities' => ['translation'],
                'config' => ['api_key' => 'secret-key'],
            ],
        ];

        $service = new AIService(function ($key, $default) use ($modelsData) {
            return $key === 'sham-ai.models' ? $modelsData : $default;
        });

        $models = $service->getModels();
        $this->assertCount(1, $models);
        $this->assertEquals('m1', $service->getModel('m1')->id);
        $this->assertEquals('secret-key', $service->getModel('m1')->config['api_key']);
    }

    public function test_ai_service_filters_by_capability(): void
    {
        $modelsData = [
            [
                'id' => 'm1',
                'name' => 'N1',
                'provider' => 'openai',
                'model' => 'gpt-4o',
                'enabled' => true,
                'capabilities' => ['translation'],
            ],
            [
                'id' => 'm2',
                'name' => 'N2',
                'provider' => 'huggingface-flux',
                'model' => 'flux-dev',
                'enabled' => true,
                'capabilities' => ['image_generation'],
            ],
        ];

        $service = new AIService(function ($key, $default) use ($modelsData) {
            return $key === 'sham-ai.models' ? $modelsData : $default;
        });

        $translationModels = $service->getModelsByCapability('translation');
        $this->assertCount(1, $translationModels);
        $this->assertEquals('m1', $translationModels->first()->id);
    }

    public function test_ai_service_returns_only_enabled_models(): void
    {
        $modelsData = [
            [
                'id' => 'm1',
                'name' => 'Enabled',
                'provider' => 'openai',
                'model' => 'gpt-4o',
                'enabled' => true,
                'capabilities' => ['translation'],
            ],
            [
                'id' => 'm2',
                'name' => 'Disabled',
                'provider' => 'openai',
                'model' => 'gpt-3.5-turbo',
                'enabled' => false,
                'capabilities' => ['translation'],
            ],
        ];

        $service = new AIService(function ($key, $default) use ($modelsData) {
            return $key === 'sham-ai.models' ? $modelsData : $default;
        });

        $enabledModels = $service->getEnabledModels();
        $this->assertCount(1, $enabledModels);
        $this->assertEquals('m1', $enabledModels->first()->id);
    }

    public function test_is_configured_returns_true_when_models_exist(): void
    {
        $modelsData = [
            [
                'id' => 'm1',
                'name' => 'OpenAI',
                'provider' => 'openai',
                'model' => 'gpt-4o',
                'enabled' => true,
                'capabilities' => ['translation'],
            ],
        ];

        $service = new AIService(function ($key, $default) use ($modelsData) {
            return $key === 'sham-ai.models' ? $modelsData : $default;
        });

        $this->assertTrue($service->isConfigured());
    }

    public function test_is_configured_returns_false_when_no_models(): void
    {
        $service = new AIService(function ($key, $default) {
            return $default;
        });

        $this->assertFalse($service->isConfigured());
    }

    public function test_is_capability_enabled(): void
    {
        $modelsData = [
            [
                'id' => 'm1',
                'name' => 'Translator',
                'provider' => 'openai',
                'model' => 'gpt-4o',
                'enabled' => true,
                'capabilities' => ['translation'],
            ],
        ];

        $service = new AIService(function ($key, $default) use ($modelsData) {
            return $key === 'sham-ai.models' ? $modelsData : $default;
        });

        $this->assertTrue($service->isCapabilityEnabled('translation'));
        $this->assertFalse($service->isCapabilityEnabled('image_generation'));
    }
}
