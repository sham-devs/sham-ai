<?php

declare(strict_types=1);

namespace Sham\AI\Providers\Adapters;

use Sham\AI\Contracts\AIProviderInterface;
use Sham\AI\Contracts\AIResponseInterface;
use Sham\AI\Contracts\PromptInterface;
use Sham\AI\Models\AIModel;

abstract class AbstractProviderAdapter implements AIProviderInterface
{
    public function __construct(
        protected AIModel $model
    ) {}

    /**
     * Get the model associated with this adapter.
     */
    public function getModel(): AIModel
    {
        return $this->model;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function send(PromptInterface $prompt): AIResponseInterface;

    /**
     * {@inheritdoc}
     */
    abstract public function isConfigured(): bool;

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->model->provider;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(array $config): void
    {
        // Configuration is typically passed via the AIModel object
    }
}
