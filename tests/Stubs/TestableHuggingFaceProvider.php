<?php

declare(strict_types=1);

namespace Sham\AI\Tests\Stubs;

use Sham\AI\Providers\HuggingFace\BaseHuggingFaceProvider;

/**
 * Concrete subclass exposing protected helpers for isolated unit testing
 * of BaseHuggingFaceProvider::buildPayload() and related helpers.
 */
class TestableHuggingFaceProvider extends BaseHuggingFaceProvider
{
    public function __construct(string $optionsKey = 'parameters', ?string $apiKey = null)
    {
        parent::__construct(array_filter([
            'api_key' => $apiKey,
        ]));

        $this->optionsKey = $optionsKey;
    }

    /**
     * @param  array<string, mixed>  $runtimeOptions
     * @return array<string, mixed>
     */
    public function exposedBuildPayload(string $prompt, array $runtimeOptions): array
    {
        return $this->buildPayload($prompt, $runtimeOptions);
    }

    public function exposedParseSize(string $size): array
    {
        return $this->parseSize($size);
    }

    public function exposedExtractLangCode(string $locale): string
    {
        return $this->extractLangCode($locale);
    }
}
