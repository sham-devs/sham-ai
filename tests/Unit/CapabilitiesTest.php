<?php

declare(strict_types=1);

namespace Sham\AI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sham\AI\Capabilities\DTOs\ContentGenerationRequest;
use Sham\AI\Capabilities\DTOs\ContentGenerationResponse;
use Sham\AI\Capabilities\DTOs\MetaTagsResponse;
use Sham\AI\Capabilities\DTOs\SEORequest;
use Sham\AI\Capabilities\DTOs\SEOResponse;
use Sham\AI\Capabilities\DTOs\TranslationRequest;
use Sham\AI\Capabilities\DTOs\TranslationResponse;

class CapabilitiesTest extends TestCase
{
    public function test_translation_request_dto(): void
    {
        $request = new TranslationRequest(
            texts: ['Hello', 'World'],
            fromLocale: 'en',
            toLocale: 'ar',
            options: ['context' => 'greeting', 'tone' => 'formal']
        );

        $this->assertEquals(['Hello', 'World'], $request->texts);
        $this->assertEquals('en', $request->fromLocale);
        $this->assertEquals('ar', $request->toLocale);
        $this->assertEquals(['context' => 'greeting', 'tone' => 'formal'], $request->options);
    }

    public function test_translation_response_dto(): void
    {
        $response = new TranslationResponse(
            successful: true,
            translations: ['hello' => 'مرحبا'],
            usage: ['tokens' => 10],
            modelUsed: 'gpt-4o'
        );

        $this->assertTrue($response->successful);
        $this->assertEquals(['hello' => 'مرحبا'], $response->translations);
        $this->assertEquals(['tokens' => 10], $response->usage);
        $this->assertEquals('gpt-4o', $response->modelUsed);
        $this->assertNull($response->error);
    }

    public function test_content_generation_request_dto(): void
    {
        $request = new ContentGenerationRequest(
            type: 'article',
            topic: 'AI in Healthcare',
            locale: 'en',
            context: ['target_keywords' => ['ai', 'healthcare']],
            maxLength: 2000,
            tone: 'formal'
        );

        $this->assertEquals('article', $request->type);
        $this->assertEquals('AI in Healthcare', $request->topic);
        $this->assertEquals('en', $request->locale);
        $this->assertEquals(['target_keywords' => ['ai', 'healthcare']], $request->context);
        $this->assertEquals(2000, $request->maxLength);
        $this->assertEquals('formal', $request->tone);
    }

    public function test_seo_response_dto(): void
    {
        $response = new SEOResponse(
            successful: true,
            analysis: ['score' => 85],
            usage: ['tokens' => 30],
            modelUsed: 'gpt-4o'
        );

        $this->assertTrue($response->successful);
        $this->assertEquals(['score' => 85], $response->analysis);
    }

    public function test_meta_tags_response_dto(): void
    {
        $response = new MetaTagsResponse(
            successful: true,
            metaTags: ['description' => 'Best AI'],
            usage: ['tokens' => 20],
            modelUsed: 'gpt-4o'
        );

        $this->assertTrue($response->successful);
        $this->assertEquals(['description' => 'Best AI'], $response->metaTags);
    }
}
