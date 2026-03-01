<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\Contracts;

use Sham\AI\Capabilities\CapabilityInterface;
use Sham\AI\Capabilities\DTOs\ContentGenerationRequest;
use Sham\AI\Capabilities\DTOs\ContentGenerationResponse;

interface ContentGenerationCapabilityInterface extends CapabilityInterface
{
    /**
     * Check if the model can generate content.
     */
    public function canGenerateContent(): bool;

    /**
     * Generate content.
     */
    public function generate(ContentGenerationRequest $request): ContentGenerationResponse;

    /**
     * Get the supported content types.
     *
     * @return array<string>
     */
    public function getSupportedContentTypes(): array;
}
