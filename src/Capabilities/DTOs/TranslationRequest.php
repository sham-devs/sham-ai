<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\DTOs;

readonly class TranslationRequest
{
    /**
     * @param  array<string, string>  $texts
     */
    public function __construct(
        public array $texts,
        public string $fromLocale,
        public string $toLocale,
        public array $options = [],
    ) {}
}
