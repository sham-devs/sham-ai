<?php

declare(strict_types=1);

namespace Sham\AI\Prompts;

use Sham\AI\Contracts\PromptInterface;

/**
 * FileTranslationPrompt
 *
 * Builds AI prompts for translating language files (JSON/PHP).
 * This is used by TranslateFilesCommand to translate UI translations.
 */
class FileTranslationPrompt implements PromptInterface
{
    /**
     * @param  array<string, string>  $texts  Key-value pairs of translations to translate
     * @param  string  $from  Source locale code
     * @param  string  $to  Target locale code
     * @param  array<string, mixed>  $options  Additional options
     */
    public function __construct(
        protected array $texts,
        protected string $from,
        protected string $to,
        protected array $options = []
    ) {}

    /**
     * {@inheritdoc}
     */
    public function getSystemPrompt(): string
    {
        return <<<PROMPT
You are a professional translator specializing in UI/localization translations.
Your task is to translate the provided JSON key-value pairs from {$this->from} to {$this->to}.

Rules:
- Preserve any placeholders like :name, {{variable}}, %s, {variable}
- Keep the JSON structure exactly the same
- Maintain the same tone and formality as the source text
- For technical terms, use the standard terminology in the target language
- For UI elements (buttons, labels, etc.), use natural translations
- Return ONLY valid JSON with the same keys
- Do not include any explanations or additional text
- Ensure proper JSON encoding for special characters
PROMPT;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPrompt(): string
    {
        $jsonTexts = json_encode($this->texts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
Translate the following JSON from {$this->from} to {$this->to}:

{$jsonTexts}

Return ONLY the translated JSON with the same structure and keys.
PROMPT;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return array_merge([
            'temperature' => 0.3,
            'max_tokens' => 4000,
        ], $this->options);
    }

    /**
     * Get the texts being translated.
     *
     * @return array<string, string>
     */
    public function getTexts(): array
    {
        return $this->texts;
    }

    /**
     * Get the source locale.
     */
    public function getFromLocale(): string
    {
        return $this->from;
    }

    /**
     * Get the target locale.
     */
    public function getToLocale(): string
    {
        return $this->to;
    }
}
