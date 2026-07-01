<?php

declare(strict_types=1);

use Sham\AI\Capabilities\DTOs\ContentGenerationRequest;
use Sham\AI\Capabilities\DTOs\MetaTagsResponse;
use Sham\AI\Capabilities\DTOs\SEOResponse;
use Sham\AI\Capabilities\DTOs\TranslationRequest;
use Sham\AI\Capabilities\DTOs\TranslationResponse;

it('builds a translation request dto', function (): void {
    $request = new TranslationRequest(
        texts: ['Hello', 'World'],
        fromLocale: 'en',
        toLocale: 'ar',
        options: ['context' => 'greeting', 'tone' => 'formal']
    );

    expect($request->texts)->toBe(['Hello', 'World'])
        ->and($request->fromLocale)->toBe('en')
        ->and($request->toLocale)->toBe('ar')
        ->and($request->options)->toBe(['context' => 'greeting', 'tone' => 'formal']);
});

it('builds a translation response dto', function (): void {
    $response = new TranslationResponse(
        successful: true,
        translations: ['hello' => 'مرحبا'],
        usage: ['tokens' => 10],
        modelUsed: 'gpt-4o'
    );

    expect($response->successful)->toBeTrue()
        ->and($response->translations)->toBe(['hello' => 'مرحبا'])
        ->and($response->usage)->toBe(['tokens' => 10])
        ->and($response->modelUsed)->toBe('gpt-4o')
        ->and($response->error)->toBeNull();
});

it('builds a content generation request dto', function (): void {
    $request = new ContentGenerationRequest(
        type: 'article',
        topic: 'AI in Healthcare',
        locale: 'en',
        context: ['target_keywords' => ['ai', 'healthcare']],
        maxLength: 2000,
        tone: 'formal'
    );

    expect($request->type)->toBe('article')
        ->and($request->topic)->toBe('AI in Healthcare')
        ->and($request->locale)->toBe('en')
        ->and($request->context)->toBe(['target_keywords' => ['ai', 'healthcare']])
        ->and($request->maxLength)->toBe(2000)
        ->and($request->tone)->toBe('formal');
});

it('builds a seo response dto', function (): void {
    $response = new SEOResponse(
        successful: true,
        analysis: ['score' => 85],
        usage: ['tokens' => 30],
        modelUsed: 'gpt-4o'
    );

    expect($response->successful)->toBeTrue()
        ->and($response->analysis)->toBe(['score' => 85]);
});

it('builds a meta tags response dto', function (): void {
    $response = new MetaTagsResponse(
        successful: true,
        metaTags: ['description' => 'Best AI'],
        usage: ['tokens' => 20],
        modelUsed: 'gpt-4o'
    );

    expect($response->successful)->toBeTrue()
        ->and($response->metaTags)->toBe(['description' => 'Best AI']);
});
