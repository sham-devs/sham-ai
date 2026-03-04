<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\DTOs;

class ImageGenerationRequest
{
    public function __construct(
        public string $prompt,
        public string $size = '1024x1024',
        public int $count = 1,
        public array $options = []
    ) {}
}
