<?php

declare(strict_types=1);

use Sham\AI\Providers\HuggingFace\BaseHuggingFaceProvider;
use Sham\AI\Providers\HuggingFace\FluxProvider;
use Sham\AI\Providers\HuggingFace\LlamaProvider;
use Sham\AI\Providers\HuggingFace\MistralProvider;
use Sham\AI\Providers\HuggingFace\NllbProvider;
use Sham\AI\Providers\HuggingFace\OpusMtProvider;
use Sham\AI\Providers\HuggingFace\QwenProvider;
use Sham\AI\Providers\HuggingFace\SDProvider;
use Sham\AI\Providers\HuggingFace\SdxlProvider;
use Sham\AI\Tests\Stubs\TestableHuggingFaceProvider;

it('builds payload with parameters key and runtime options', function (): void {
    $provider = new TestableHuggingFaceProvider('parameters');

    $payload = $provider->exposedBuildPayload('Hello world', ['max_new_tokens' => 50, 'temperature' => 0.7]);

    expect($payload)
        ->toHaveKey('inputs')
        ->toHaveKey('parameters')
        ->and($payload['inputs'])->toBe('Hello world')
        ->and($payload['parameters'])->toBe(['max_new_tokens' => 50, 'temperature' => 0.7]);
});

it('builds payload with options key for NLLB-style models', function (): void {
    $provider = new TestableHuggingFaceProvider('options');

    $payload = $provider->exposedBuildPayload('Bonjour', ['src_lang' => 'fr', 'tgt_lang' => 'en']);

    expect($payload)
        ->toHaveKey('inputs')
        ->toHaveKey('options')
        ->and($payload)->not->toHaveKey('parameters')
        ->and($payload['inputs'])->toBe('Bonjour')
        ->and($payload['options'])->toBe(['src_lang' => 'fr', 'tgt_lang' => 'en']);
});

it('omits the options key when no runtime options are provided', function (): void {
    $provider = new TestableHuggingFaceProvider('parameters');

    $payload = $provider->exposedBuildPayload('A prompt', []);

    expect($payload)
        ->toHaveKey('inputs')
        ->and($payload['inputs'])->toBe('A prompt')
        ->and($payload)->not->toHaveKey('parameters');
});

it('returns inputs even for empty prompt', function (): void {
    $provider = new TestableHuggingFaceProvider('parameters');

    $payload = $provider->exposedBuildPayload('', ['max_new_tokens' => 10]);

    expect($payload['inputs'])->toBe('')
        ->and($payload['parameters'])->toBe(['max_new_tokens' => 10]);
});

it('parses a size string into width and height', function (): void {
    $provider = new TestableHuggingFaceProvider;

    expect($provider->exposedParseSize('1024x768'))->toBe(['width' => 1024, 'height' => 768]);
});

it('falls back to 1024x1024 on invalid size', function (): void {
    $provider = new TestableHuggingFaceProvider;

    expect($provider->exposedParseSize('invalid'))->toBe(['width' => 1024, 'height' => 1024]);
});

it('extracts a 2-letter language code from a locale', function (): void {
    $provider = new TestableHuggingFaceProvider;

    expect($provider->exposedExtractLangCode('ar_SA'))->toBe('ar')
        ->and($provider->exposedExtractLangCode('en_US'))->toBe('en');
});

it('can instantiate all seven HuggingFace providers that depend on buildPayload', function (): void {
    // Guards against regression of F1: each of the seven providers must construct
    // and be a BaseHuggingFaceProvider subclass that resolves buildPayload via the base.
    $classes = [
        LlamaProvider::class,
        QwenProvider::class,
        NllbProvider::class,
        OpusMtProvider::class,
        MistralProvider::class,
        FluxProvider::class,
        SDProvider::class,
        SdxlProvider::class,
    ];

    foreach ($classes as $class) {
        $instance = new $class(['api_key' => 'test-key']);
        expect($instance)->toBeInstanceOf(BaseHuggingFaceProvider::class)
            ->and(method_exists($instance, 'sendRequest'))->toBeTrue();
    }
});
