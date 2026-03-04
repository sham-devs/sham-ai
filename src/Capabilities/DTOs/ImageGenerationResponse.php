<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\DTOs;

class ImageGenerationResponse
{
    /**
     * @param  array<string>  $images  List of image URLs or base64 data
     */
    public function __construct(
        public array $images = [],
        public bool $successful = true,
        public ?string $error = null
    ) {}
}
