<?php

declare(strict_types=1);

namespace Sham\AI\Contracts;

/**
 * AIPromptBuilderInterface
 *
 * Interface for building AI prompts from translation context.
 * Allows Sham Translation to customize how AI prompts are constructed.
 */
interface AIPromptBuilderInterface
{
    /**
     * Build an AI prompt from translation context.
     *
     * @param array $texts Texts to translate
     * @param string $from Source locale code (e.g., 'en')
     * @param string $to Target locale code (e.g., 'ar')
     * @param array $context Additional context for prompt customization:
     *                       - 'model': The Eloquent model being translated
     *                       - 'attributes': The attributes being translated
     *                       - 'ai_options': Additional AI options (temperature, etc.)
     * @return PromptInterface The constructed AI prompt
     */
    public function buildTranslationPrompt(
        array $texts,
        string $from,
        string $to,
        array $context = []
    ): PromptInterface;
}
