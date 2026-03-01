<?php

declare(strict_types=1);

namespace Sham\AI\Prompts;

use Sham\AI\Contracts\PromptInterface;

/**
 * Content Generation Prompt
 */
class ContentGenerationPrompt implements PromptInterface
{
    /**
     * @param string $topic The topic or instructions for content generation
     * @param array $options Generation options (tone, language, format, etc.)
     */
    public function __construct(
        protected string $topic,
        protected array $options = []
    ) {}

    /**
     * {@inheritdoc}
     */
    public function getSystemPrompt(): string
    {
        $tone = $this->options['tone'] ?? 'professional';
        $language = $this->options['language'] ?? 'English';
        $format = $this->options['format'] ?? 'blog post';
        $audience = $this->options['audience'] ?? 'general';

        return <<<PROMPT
You are an expert content creator and copywriter. Your task is to generate high-quality, engaging, and original content based on the provided topic or instructions.

Guidelines:
- Language: {$language}
- Tone: {$tone}
- Target Audience: {$audience}
- Format: {$format}
- Ensure the content is well-structured and follows the requested format.
- Use natural language and optimize for readability.
- Do not include any meta-talk or additional commentary unless requested.
PROMPT;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPrompt(): string
    {
        $prompt = "Topic/Instructions: {$this->topic}";

        if (!empty($this->options['additional_instructions'])) {
            $prompt .= "\n\nAdditional Instructions: " . $this->options['additional_instructions'];
        }

        return $prompt;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return array_merge([
            'temperature' => 0.7,
            'max_tokens' => 4000,
        ], $this->options);
    }
}
