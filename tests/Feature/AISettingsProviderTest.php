<?php

declare(strict_types=1);

use Sham\AI\AIService;
use Sham\AI\Settings\AISettingsProvider;

function resolveSettingsProvider(): AISettingsProvider
{
    return new AISettingsProvider(app(AIService::class));
}

it('executes the test_connection action and reports configured status', function (): void {
    $provider = resolveSettingsProvider();

    $result = $provider->executeAction('test_connection', [], 'settings');

    expect($result)->toHaveKey('success')
        ->and($result)->toHaveKey('message')
        ->and($result['success'])->toBeFalse(); // no models configured in empty test env
});

it('reports unknown action as failure', function (): void {
    $provider = resolveSettingsProvider();

    $result = $provider->executeAction('unknown_action', [], 'settings');

    expect($result['success'])->toBeFalse()
        ->and($result['message'])->toContain('Unknown action');
});

it('returns the plugin id', function (): void {
    expect(resolveSettingsProvider()->getId())->toBe('sham-ai');
});

it('has a tab definition', function (): void {
    $tab = resolveSettingsProvider()->getTabDefinition();

    expect($tab)->toHaveKey('key')
        ->and($tab['key'])->toBe('sham-ai');
});

it('satisfies the HasSettingsStructure contract via live trait methods', function (): void {
    // Proves AISettingsCards trait is NOT dead: validateStructure()/getStructureInfo()
    // are the live implementations required by HasSettingsStructure interface.
    $provider = resolveSettingsProvider();

    $structure = $provider->validateStructure();
    $info = $provider->getStructureInfo();

    expect($structure)->toHaveKey('valid')
        ->and($structure)->toHaveKey('errors')
        ->and($info)->toHaveKey('groups_count');
});
