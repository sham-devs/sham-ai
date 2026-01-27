<?php

declare(strict_types=1);

namespace Sham\AI\Contracts;

interface AIProviderInterface
{
    /**
     * Send a prompt to the AI provider and return the response.
     *
     * @param  PromptInterface  $prompt  The prompt to send
     * @return AIResponseInterface The AI response
     */
    public function send(PromptInterface $prompt): AIResponseInterface;

    /**
     * Check if the provider is properly configured.
     */
    public function isConfigured(): bool;

    /**
     * Get the provider name.
     */
    public function getName(): string;

    /**
     * Configure the provider with custom settings.
     */
    public function configure(array $config): void;
}
