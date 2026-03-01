<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\DTOs;

readonly class SEOResponse
{
    public function __construct(
        public bool $successful,
        public array $analysis = [],
        public ?string $error = null,
        public array $usage = [],
        public string $modelUsed = '',
    ) {}
}
