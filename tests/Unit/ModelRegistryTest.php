<?php

declare(strict_types=1);

namespace Sham\AI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sham\AI\Models\AIModel;
use Sham\AI\Models\ModelRegistry;

class ModelRegistryTest extends TestCase
{
    public function test_registry_get_all_models(): void
    {
        $model1 = new AIModel(
            id: 'm1',
            name: 'Model 1',
            provider: 'openai',
            model: 'gpt-4o',
            enabled: true,
            capabilities: ['translation']
        );

        $model2 = new AIModel(
            id: 'm2',
            name: 'Model 2',
            provider: 'anthropic',
            model: 'claude-3-5-sonnet',
            enabled: false,
            capabilities: ['translation']
        );

        $registry = new ModelRegistry([$model1, $model2]);

        $all = $registry->getAll();
        $this->assertCount(2, $all);
    }

    public function test_registry_get_enabled_only(): void
    {
        $model1 = new AIModel(
            id: 'm1',
            name: 'Model 1',
            provider: 'openai',
            model: 'gpt-4o',
            enabled: true,
            capabilities: ['translation']
        );

        $model2 = new AIModel(
            id: 'm2',
            name: 'Model 2',
            provider: 'anthropic',
            model: 'claude-3-5-sonnet',
            enabled: false,
            capabilities: ['translation']
        );

        $registry = new ModelRegistry([$model1, $model2]);

        $enabled = $registry->getEnabled();
        $this->assertCount(1, $enabled);
        $this->assertEquals('m1', $enabled->first()->id);
    }

    public function test_registry_filters_by_capability(): void
    {
        $model1 = new AIModel(
            id: 'm1',
            name: 'Translator',
            provider: 'openai',
            model: 'gpt-4o',
            enabled: true,
            capabilities: ['translation', 'content_generation']
        );

        $model2 = new AIModel(
            id: 'm2',
            name: 'Image Generator',
            provider: 'huggingface-flux',
            model: 'flux-dev',
            enabled: true,
            capabilities: ['image_generation']
        );

        $registry = new ModelRegistry([$model1, $model2]);

        $translationModels = $registry->getByCapability('translation');
        $this->assertCount(1, $translationModels);
        $this->assertEquals('m1', $translationModels->first()->id);
    }

    public function test_registry_add_model(): void
    {
        $registry = new ModelRegistry([]);

        $model = new AIModel(
            id: 'new',
            name: 'New Model',
            provider: 'openai',
            model: 'gpt-4o-mini',
            enabled: true,
            capabilities: ['translation']
        );

        $registry->add($model);

        $this->assertCount(1, $registry->getAll());
        $this->assertEquals('new', $registry->get('new')->id);
    }

    public function test_registry_update_model(): void
    {
        $model = new AIModel(
            id: 'm1',
            name: 'Original Name',
            provider: 'openai',
            model: 'gpt-4o',
            enabled: true,
            capabilities: ['translation']
        );

        $registry = new ModelRegistry([$model]);

        $registry->update('m1', ['name' => 'Updated Name', 'enabled' => false]);

        $updated = $registry->get('m1');
        $this->assertEquals('Updated Name', $updated->name);
        $this->assertFalse($updated->enabled);
    }

    public function test_registry_delete_model(): void
    {
        $model = new AIModel(
            id: 'm1',
            name: 'To Delete',
            provider: 'openai',
            model: 'gpt-4o',
            enabled: true,
            capabilities: ['translation']
        );

        $registry = new ModelRegistry([$model]);

        $registry->delete('m1');

        $this->assertNull($registry->get('m1'));
        $this->assertCount(0, $registry->getAll());
    }

    public function test_registry_enable_disable_model(): void
    {
        $model = new AIModel(
            id: 'm1',
            name: 'Test',
            provider: 'openai',
            model: 'gpt-4o',
            enabled: true,
            capabilities: ['translation']
        );

        $registry = new ModelRegistry([$model]);

        $registry->disable('m1');
        $this->assertFalse($registry->get('m1')->enabled);

        $registry->enable('m1');
        $this->assertTrue($registry->get('m1')->enabled);
    }
}
