<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\DTOs;

readonly class ContentGenerationRequest
{
    public function __construct(
        public string $type,
        public string $topic,
        public string $locale,
        public array $context = [],
        public int $maxLength = 1000,
        public string $tone = 'professional',
    ) {}
}
