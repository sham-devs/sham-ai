<?php

declare(strict_types=1);

use Sham\AI\Capabilities\DTOs\TranslationRequest;
use Sham\AI\Models\AIModel;
use Sham\AI\Providers\Adapters\PrismAdapter;

function makeTranslationModel(): AIModel
{
    return new AIModel(
        id: 't1',
        name: 'Translator',
        provider: 'openai',
        model: 'gpt-4o',
        enabled: true,
        capabilities: ['translation'],
        config: ['api_key' => 'fake-key']
    );
}

it('rejects translation when model lacks the capability', function (): void {
    $model = new AIModel(
        id: 'img1',
        name: 'Image only',
        provider: 'huggingface-flux',
        model: 'flux-dev',
        enabled: true,
        capabilities: ['image_generation'],
        config: ['api_key' => 'fake-key']
    );

    $adapter = new PrismAdapter($model);
    $response = $adapter->translate(new TranslationRequest(texts: ['Hello'], fromLocale: 'en', toLocale: 'ar'));

    expect($response->successful)->toBeFalse()
        ->and($response->error)->not->toBeEmpty();
});

it('reports configured status based on api key presence', function (): void {
    $withKey = new PrismAdapter(new AIModel(
        id: 'm1', name: 'T', provider: 'openai', model: 'gpt-4o',
        enabled: true, capabilities: ['translation'], config: ['api_key' => 'k']
    ));
    $withoutKey = new PrismAdapter(new AIModel(
        id: 'm2', name: 'T', provider: 'openai', model: 'gpt-4o',
        enabled: true, capabilities: ['translation'], config: []
    ));

    expect($withKey->isConfigured())->toBeTrue()
        ->and($withoutKey->isConfigured())->toBeFalse();
});

it('exposes static capability metadata', function (): void {
    expect(PrismAdapter::getCapabilityName())->toBe('translation')
        ->and(PrismAdapter::getCapabilityLabel())->toBe('Translation');
});

it('can determine translation capability from the model', function (): void {
    $adapter = new PrismAdapter(makeTranslationModel());

    expect($adapter->canTranslate())->toBeTrue();
});
