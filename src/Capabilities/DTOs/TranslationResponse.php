<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities\DTOs;

readonly class TranslationResponse
{
    /**
     * @param  array<string, string>  $translations
     */
    public function __construct(
        public bool $successful,
        public array $translations = [],
        public ?string $error = null,
        public array $usage = [],
        public string $modelUsed = '',
    ) {}
}
