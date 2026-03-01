<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\DTOs;

readonly class ContentGenerationResponse
{
    public function __construct(
        public bool $successful,
        public string $content = '',
        public ?string $error = null,
        public array $usage = [],
        public string $modelUsed = '',
    ) {}
}
