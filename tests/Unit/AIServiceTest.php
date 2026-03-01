<?php

declare(strict_types=1);

namespace Sham\AI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sham\AI\AIService;
use Sham\AI\Models\AIModel;

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
                'config' => ['api_key' => 'secret-key']
            ]
        ];

        $service = new AIService(function ($key, $default) use ($modelsData) {
            return $key === 'ai.models' ? $modelsData : $default;
        });

        $this->assertCount(1, $service->getModels());
        $this->assertEquals('m1', $service->getModel('m1')->id);
        $this->assertEquals('secret-key', $service->getModel('m1')->config['api_key']);
    }

    public function test_ai_service_filters_by_capability(): void
    {
        $modelsData = [
            ['id' => 'm1', 'name' => 'N1', 'provider' => 'p', 'model' => 'm', 'enabled' => true, 'capabilities' => ['translation']],
            ['id' => 'm2', 'name' => 'N2', 'provider' => 'p', 'model' => 'm', 'enabled' => true, 'capabilities' => ['seo']],
        ];

        $service = new AIService(function ($key, $default) use ($modelsData) {
            return $key === 'ai.models' ? $modelsData : $default;
        });

        $this->assertCount(1, $service->getModelsByCapability('translation'));
        $this->assertEquals('m1', $service->getModelsByCapability('translation')->first()->id);
    }
}
