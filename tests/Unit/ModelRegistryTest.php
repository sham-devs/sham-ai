<?php

declare(strict_types=1);

namespace Sham\AI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sham\AI\Models\AIModel;
use Sham\AI\Models\ModelRegistry;

class ModelRegistryTest extends TestCase
{
    public function test_registry_can_add_and_retrieve_models(): void
    {
        $model = new AIModel(
            id: 'test-1',
            name: 'Test Model',
            provider: 'openai',
            model: 'gpt-4o',
            enabled: true,
            capabilities: ['translation']
        );

        $registry = new ModelRegistry([$model]);

        $this->assertEquals($model, $registry->get('test-1'));
        $this->assertCount(1, $registry->getAll());
    }

    public function test_registry_filters_by_capability(): void
    {
        $m1 = new AIModel('m1', 'N1', 'p', 'md', true, ['translation']);
        $m2 = new AIModel('m2', 'N2', 'p', 'md', true, ['content_generation']);
        $m3 = new AIModel('m3', 'N3', 'p', 'md', false, ['translation']);

        $registry = new ModelRegistry([$m1, $m2, $m3]);

        $translationModels = $registry->getByCapability('translation');
        $this->assertCount(1, $translationModels);
        $this->assertTrue($translationModels->contains('id', 'm1'));

        $enabledTranslation = $registry->getEnabled()->filter(fn($m) => in_array('translation', $m->capabilities));
        $this->assertCount(1, $enabledTranslation);
        $this->assertEquals('m1', $enabledTranslation->first()->id);
    }

    public function test_registry_update_and_delete(): void
    {
        $model = new AIModel('test', 'Name', 'p', 'm');
        $registry = new ModelRegistry([$model]);

        $registry->update('test', ['name' => 'Updated Name']);
        $this->assertEquals('Updated Name', $registry->get('test')->name);

        $registry->delete('test');
        $this->assertNull($registry->get('test'));
    }
}
