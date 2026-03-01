<?php

declare(strict_types=1);

namespace Sham\AI\Prompts;

use Sham\AI\Contracts\PromptInterface;

/**
 * SEO Optimization Prompt
 */
class SEOPrompt implements PromptInterface
{
    /**
     * @param string $content The content to optimize
     * @param array $keywords Target keywords
     * @param array $options SEO options
     */
    public function __construct(
        protected string $content,
        protected array $keywords = [],
        protected array $options = []
    ) {}

    /**
     * {@inheritdoc}
     */
    public function getSystemPrompt(): string
    {
        $keywords = !empty($this->keywords) ? implode(', ', $this->keywords) : 'None provided';
        $language = $this->options['language'] ?? 'English';

        return <<<PROMPT
You are an experienced SEO specialist and digital marketer. Your task is to analyze and optimize the provided content for search engines while maintaining high quality and readability.

Target Keywords: {$keywords}
Language: {$language}

Your analysis should include:
1. Optimized Meta Title (Max 60 characters)
2. Optimized Meta Description (Max 160 characters)
3. Content Optimization Suggestions (How to better incorporate keywords)
4. Readability and Structure Improvements
5. Missing SEO Opportunities

Return the analysis in a structured format (JSON if requested, otherwise clear headings).
PROMPT;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPrompt(): string
    {
        return "Content to Analyze:\n\n{$this->content}";
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return array_merge([
            'temperature' => 0.4,
            'max_tokens' => 2000,
        ], $this->options);
    }
}
