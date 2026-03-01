<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\DTOs;

readonly class SEORequest
{
    public function __construct(
        public string $content,
        public string $locale,
        public ?string $title = null,
        public ?string $url = null,
        public array $targetKeywords = [],
        public array $options = [],
    ) {}
}
