<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\Contracts;

use Sham\AI\Capabilities\CapabilityInterface;
use Sham\AI\Capabilities\DTOs\MetaTagsResponse;
use Sham\AI\Capabilities\DTOs\SEORequest;
use Sham\AI\Capabilities\DTOs\SEOResponse;

interface SEOCapabilityInterface extends CapabilityInterface
{
    /**
     * Check if the model can analyze SEO.
     */
    public function canAnalyzeSEO(): bool;

    /**
     * Analyze SEO for a given content.
     */
    public function analyzeSEO(SEORequest $request): SEOResponse;

    /**
     * Generate Meta Tags for a given content.
     */
    public function generateMetaTags(SEORequest $request): MetaTagsResponse;

    /**
     * Suggest keywords for a given content.
     *
     * @return array<string>
     */
    public function suggestKeywords(string $content, string $locale): array;

    /**
     * Improve content for SEO.
     */
    public function improveContentForSEO(SEORequest $request): string;
}
