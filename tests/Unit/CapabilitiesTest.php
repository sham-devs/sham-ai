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
            texts: ['hello' => 'world'],
            fromLocale: 'en',
            toLocale: 'ar',
            options: ['tone' => 'formal']
        );

        $this->assertEquals(['hello' => 'world'], $request->texts);
        $this->assertEquals('en', $request->fromLocale);
        $this->assertEquals('ar', $request->toLocale);
        $this->assertEquals(['tone' => 'formal'], $request->options);
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
            topic: 'AI',
            locale: 'en',
            context: ['target' => 'tech'],
            maxLength: 500,
            tone: 'casual'
        );

        $this->assertEquals('article', $request->type);
        $this->assertEquals('AI', $request->topic);
        $this->assertEquals('en', $request->locale);
        $this->assertEquals(['target' => 'tech'], $request->context);
        $this->assertEquals(500, $request->maxLength);
        $this->assertEquals('casual', $request->tone);
    }

    public function test_content_generation_response_dto(): void
    {
        $response = new ContentGenerationResponse(
            successful: true,
            content: 'AI is great',
            usage: ['tokens' => 50],
            modelUsed: 'gpt-4o'
        );

        $this->assertTrue($response->successful);
        $this->assertEquals('AI is great', $response->content);
        $this->assertEquals(['tokens' => 50], $response->usage);
        $this->assertEquals('gpt-4o', $response->modelUsed);
    }

    public function test_seo_request_dto(): void
    {
        $request = new SEORequest(
            content: 'Some content',
            locale: 'en',
            title: 'Title',
            url: 'https://example.com',
            targetKeywords: ['ai', 'tech'],
            options: ['deep' => true]
        );

        $this->assertEquals('Some content', $request->content);
        $this->assertEquals('en', $request->locale);
        $this->assertEquals('Title', $request->title);
        $this->assertEquals('https://example.com', $request->url);
        $this->assertEquals(['ai', 'tech'], $request->targetKeywords);
        $this->assertEquals(['deep' => true], $request->options);
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
