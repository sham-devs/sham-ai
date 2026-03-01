<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\DTOs;

readonly class MetaTagsResponse
{
    public function __construct(
        public bool $successful,
        public array $metaTags = [],
        public ?string $error = null,
        public array $usage = [],
        public string $modelUsed = '',
    ) {}
}
