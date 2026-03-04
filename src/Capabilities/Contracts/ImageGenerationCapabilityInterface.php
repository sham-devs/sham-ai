<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\Contracts;

use Sham\AI\Capabilities\DTOs\ImageGenerationRequest;
use Sham\AI\Capabilities\DTOs\ImageGenerationResponse;

interface ImageGenerationCapabilityInterface
{
    public function generateImage(ImageGenerationRequest $request): ImageGenerationResponse;
}
