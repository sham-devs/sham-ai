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
            enabled: true
        );

        $registry = new ModelRegistry([$model]);

        $this->assertEquals($model, $registry->get('test-1'));
        $this->assertCount(1, $registry->getAll());
    }

    public function test_registry_filters_by_capability(): void
    {
        // openai has translation
        $m1 = new AIModel('m1', 'N1', 'openai', 'gpt-4o', true);
        // huggingface-flux does NOT have translation (it has image_generation)
        $m2 = new AIModel('m2', 'N2', 'huggingface-flux', 'flux', true);
        // disabled model
        $m3 = new AIModel('m3', 'N3', 'openai', 'gpt-4o', false);

        $registry = new ModelRegistry([$m1, $m2, $m3]);

        $translationModels = $registry->getByCapability('translation');
        $this->assertCount(1, $translationModels);
        $this->assertTrue($translationModels->contains('id', 'm1'));

        $enabledTranslation = $registry->getEnabled()->filter(fn ($m) => $m->supportsCapability('translation'));
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
