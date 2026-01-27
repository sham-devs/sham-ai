<?php

declare(strict_types=1);

namespace Sham\AI\Prompts;

use Sham\AI\Contracts\PromptInterface;

class TranslationPrompt implements PromptInterface
{
    /**
     * @param  array<string>  $texts
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
You are a professional translator. Your task is to translate the provided texts from {$this->from} to {$this->to}.

Rules:
- Preserve any placeholders like :name, {{variable}}, %s, {variable}
- Maintain the same tone and formality as the source text
- For technical terms, use the standard terminology in the target language
- Return ONLY the translations in the exact same order as provided
- Do not include any explanations or additional text
PROMPT;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPrompt(): string
    {
        $formattedTexts = array_map(
            fn ($text, $index) => "{$index}. {$text}",
            $this->texts,
            array_keys($this->texts)
        );

        return implode("\n", $formattedTexts);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        $defaults = config('ai.translation', [
            'temperature' => 0.3,
            'max_tokens' => 2000,
        ]);

        return array_merge($defaults, $this->options);
    }
}
