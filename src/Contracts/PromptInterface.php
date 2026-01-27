<?php

declare(strict_types=1);

namespace Sham\AI\Contracts;

interface PromptInterface
{
    /**
     * Get the system prompt.
     */
    public function getSystemPrompt(): string;

    /**
     * Get the user prompt.
     */
    public function getUserPrompt(): string;

    /**
     * Get additional options for the AI request.
     *
     * @return array<string, mixed>
     */
    public function getOptions(): array;
}
