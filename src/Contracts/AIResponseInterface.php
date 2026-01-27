<?php

declare(strict_types=1);

namespace Sham\AI\Contracts;

interface AIResponseInterface
{
    /**
     * Get the response text.
     */
    public function getText(): string;

    /**
     * Check if the request was successful.
     */
    public function isSuccessful(): bool;

    /**
     * Get usage information (tokens used, etc.).
     *
     * @return array<string, mixed>
     */
    public function getUsage(): array;

    /**
     * Get error message if any.
     */
    public function getError(): ?string;
}
