<?php

declare(strict_types=1);

use Sham\AI\Models\AIModel;
use Sham\AI\Models\ModelRegistry;

function model(string $id, array $caps = ['translation'], bool $enabled = true): AIModel
{
    return new AIModel(
        id: $id,
        name: $id,
        provider: 'openai',
        model: 'gpt-4o',
        enabled: $enabled,
        capabilities: $caps
    );
}

it('lists all models', function (): void {
    $registry = new ModelRegistry([model('m1'), model('m2')]);

    expect($registry->getAll())->toHaveCount(2);
});

it('lists only enabled models', function (): void {
    $registry = new ModelRegistry([model('m1'), model('m2', ['translation'], false)]);

    $enabled = $registry->getEnabled();

    expect($enabled)->toHaveCount(1)
        ->and($enabled->first()->id)->toBe('m1');
});

it('filters by capability', function (): void {
    $registry = new ModelRegistry([
        model('m1', ['translation', 'content_generation']),
        model('m2', ['image_generation']),
    ]);

    expect($registry->getByCapability('translation'))->toHaveCount(1)
        ->and($registry->getByCapability('translation')->first()->id)->toBe('m1');
});

it('adds a model', function (): void {
    $registry = new ModelRegistry([]);

    $registry->add(model('new'));

    expect($registry->getAll())->toHaveCount(1)
        ->and($registry->get('new')->id)->toBe('new');
});

it('updates a model', function (): void {
    $registry = new ModelRegistry([model('m1')]);

    $registry->update('m1', ['name' => 'Updated', 'enabled' => false]);

    expect($registry->get('m1')->name)->toBe('Updated')
        ->and($registry->get('m1')->enabled)->toBeFalse();
});

it('deletes a model', function (): void {
    $registry = new ModelRegistry([model('m1')]);

    $registry->delete('m1');

    expect($registry->get('m1'))->toBeNull()
        ->and($registry->getAll())->toHaveCount(0);
});

it('enables and disables a model', function (): void {
    $registry = new ModelRegistry([model('m1')]);

    $registry->disable('m1');
    expect($registry->get('m1')->enabled)->toBeFalse();

    $registry->enable('m1');
    expect($registry->get('m1')->enabled)->toBeTrue();
});
