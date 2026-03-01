<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\Contracts;

use Sham\AI\Capabilities\CapabilityInterface;
use Sham\AI\Capabilities\DTOs\TranslationRequest;
use Sham\AI\Capabilities\DTOs\TranslationResponse;

interface TranslationCapabilityInterface extends CapabilityInterface
{
    /**
     * Check if the model can translate.
     */
    public function canTranslate(): bool;

    /**
     * Translate an array of texts.
     */
    public function translate(TranslationRequest $request): TranslationResponse;
}
