<?php

declare(strict_types=1);

use Sham\AI\AIService;
use Sham\AI\Models\AIModel;
use Sham\AI\Providers\Adapters\PrismAdapter;
use Sham\AI\Tests\Stubs\SettingsServiceStub;
use Sham\Core\Contracts\Settings\SettingsServiceInterface;

function makeService(array $modelsData = []): AIService
{
    return new AIService(function (string $key, $default = null) use ($modelsData) {
        return $key === 'sham-ai.models' ? $modelsData : $default;
    });
}

it('loads models from settings', function (): void {
    $service = makeService([
        [
            'id' => 'm1',
            'name' => 'OpenAI',
            'provider' => 'openai',
            'model' => 'gpt-4o',
            'enabled' => true,
            'capabilities' => ['translation'],
            'config' => ['api_key' => 'secret-key'],
        ],
    ]);

    $models = $service->getModels();

    expect($models)->toHaveCount(1)
        ->and($service->getModel('m1')->id)->toBe('m1')
        ->and($service->getModel('m1')->config['api_key'])->toBe('secret-key');
});

it('filters models by capability', function (): void {
    $service = makeService([
        ['id' => 'm1', 'name' => 'N1', 'provider' => 'openai', 'model' => 'gpt-4o', 'enabled' => true, 'capabilities' => ['translation']],
        ['id' => 'm2', 'name' => 'N2', 'provider' => 'huggingface-flux', 'model' => 'flux-dev', 'enabled' => true, 'capabilities' => ['image_generation']],
    ]);

    expect($service->getModelsByCapability('translation'))->toHaveCount(1)
        ->and($service->getModelsByCapability('translation')->first()->id)->toBe('m1');
});

it('returns only enabled models', function (): void {
    $service = makeService([
        ['id' => 'm1', 'name' => 'Enabled', 'provider' => 'openai', 'model' => 'gpt-4o', 'enabled' => true, 'capabilities' => ['translation']],
        ['id' => 'm2', 'name' => 'Disabled', 'provider' => 'openai', 'model' => 'gpt-3.5-turbo', 'enabled' => false, 'capabilities' => ['translation']],
    ]);

    $enabled = $service->getEnabledModels();

    expect($enabled)->toHaveCount(1)
        ->and($enabled->first()->id)->toBe('m1');
});

it('is configured when models exist', function (): void {
    expect(makeService([
        ['id' => 'm1', 'name' => 'OpenAI', 'provider' => 'openai', 'model' => 'gpt-4o', 'enabled' => true, 'capabilities' => ['translation']],
    ])->isConfigured())->toBeTrue();
});

it('is not configured when no models exist', function (): void {
    expect(makeService([])->isConfigured())->toBeFalse();
});

it('reports capability enabled status', function (): void {
    $service = makeService([
        ['id' => 'm1', 'name' => 'Translator', 'provider' => 'openai', 'model' => 'gpt-4o', 'enabled' => true, 'capabilities' => ['translation']],
    ]);

    expect($service->isCapabilityEnabled('translation'))->toBeTrue()
        ->and($service->isCapabilityEnabled('image_generation'))->toBeFalse();
});

it('adds a model', function (): void {
    $service = makeService([]);

    $model = $service->addModel([
        'name' => 'New',
        'provider' => 'openai',
        'model' => 'gpt-4o-mini',
        'enabled' => true,
        'capabilities' => ['translation'],
    ]);

    expect($model->id)->not->toBeEmpty()
        ->and($service->getModels())->toHaveCount(1);
});

it('updates and deletes a model', function (): void {
    $service = makeService([
        ['id' => 'm1', 'name' => 'Original', 'provider' => 'openai', 'model' => 'gpt-4o', 'enabled' => true, 'capabilities' => ['translation']],
    ]);

    $service->updateModel('m1', ['name' => 'Updated']);
    expect($service->getModel('m1')->name)->toBe('Updated');

    $service->deleteModel('m1');
    expect($service->getModel('m1'))->toBeNull();
});

it('encrypts and decrypts api keys on round-trip', function (): void {
    // SettingsServiceInterface is bound (stub) so saveModels persists to stub store.
    $this->bindSettings($this->app);

    $service = makeService([]);
    $model = $service->addModel([
        'name' => 'Encrypted',
        'provider' => 'openai',
        'model' => 'gpt-4o',
        'enabled' => true,
        'capabilities' => ['translation'],
        'config' => ['api_key' => 'plain-secret'],
    ]);

    /** @var SettingsServiceStub $settings */
    $settings = $this->app->make(SettingsServiceInterface::class);
    $saved = $settings->get('sham-ai.models');

    expect($saved)->not->toBeNull()
        ->and($saved[0]['config']['api_key'])->not->toBe('plain-secret');

    // Reload registry from saved (encrypted) settings and verify decryption.
    $reloaded = new AIService(fn ($k, $d = null) => $settings->get($k, $d));
    expect($reloaded->getModel($model->id)->config['api_key'])->toBe('plain-secret');
});

it('returns adapter for a model', function (): void {
    $service = makeService([
        ['id' => 'm1', 'name' => 'OpenAI', 'provider' => 'openai', 'model' => 'gpt-4o', 'enabled' => true, 'capabilities' => ['translation']],
    ]);

    expect($service->getAdapter('m1'))->toBeInstanceOf(PrismAdapter::class);
});

it('throws when model not found for adapter', function (): void {
    makeService([])->getAdapter('missing');
})->throws(InvalidArgumentException::class);

it('disables and enables a model', function (): void {
    $service = makeService([
        ['id' => 'm1', 'name' => 'T', 'provider' => 'openai', 'model' => 'gpt-4o', 'enabled' => true, 'capabilities' => ['translation']],
    ]);

    $service->disableModel('m1');
    expect($service->getModel('m1')->enabled)->toBeFalse();

    $service->enableModel('m1');
    expect($service->getModel('m1')->enabled)->toBeTrue();
});

it('can disable when no library uses the model', function (): void {
    $service = makeService([
        ['id' => 'm1', 'name' => 'T', 'provider' => 'openai', 'model' => 'gpt-4o', 'enabled' => true, 'capabilities' => ['translation']],
    ]);

    expect($service->canDisableModel('m1'))->toBeTrue();
});

function makeModel(string $id): AIModel
{
    return new AIModel(
        id: $id,
        name: $id,
        provider: 'openai',
        model: 'gpt-4o',
        enabled: true,
        capabilities: ['translation']
    );
}
