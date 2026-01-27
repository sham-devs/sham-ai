<?php

declare(strict_types=1);

namespace Sham\AI\Responses;

use Prism\Prism\Text\Response as TextResponse;
use Sham\AI\Contracts\AIResponseInterface;

class PrismResponse implements AIResponseInterface
{
    protected ?TextResponse $response;

    protected ?string $error;

    public function __construct(?TextResponse $response, ?string $error = null)
    {
        $this->response = $response;
        $this->error = $error;
    }

    /**
     * {@inheritdoc}
     */
    public function getText(): string
    {
        return $this->response?->text ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return $this->error === null && $this->response !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsage(): array
    {
        if ($this->response === null) {
            return [];
        }

        return [
            'prompt_tokens' => $this->response->usage->promptTokens,
            'completion_tokens' => $this->response->usage->completionTokens,
            'total_tokens' => $this->response->usage->totalTokens,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}
