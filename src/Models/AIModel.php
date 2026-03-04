<?php

declare(strict_types=1);

namespace Sham\AI\Models;

readonly class AIModel
{
    /**
     * @param  string  $id  Unique identifier (e.g., 'gpt-4o-main')
     * @param  string  $name  Human-readable name (e.g., 'GPT-4o Primary')
     * @param  string  $provider  Provider name (e.g., 'openai')
     * @param  string  $model  Actual model name (e.g., 'gpt-4o')
     * @param  bool  $enabled  Whether the model is enabled
     * @param  array<string>  $capabilities  Supported capabilities
     * @param  array  $config  Additional configuration (e.g., encrypted api_key)
     * @param  bool  $isDefault  Whether this is the default model
     * @param  int  $priority  Priority for sorting
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $provider,
        public string $model,
        public bool $enabled = true,
        public array $config = [],
        public bool $isDefault = false,
        public int $priority = 0,
    ) {}

    /**
     * Get the capabilities for this model.
     *
     * @return array<string>
     */
    public function getCapabilities(): array
    {
        return SupportedModels::getModelInfo($this->provider, $this->model)['capabilities'] ?? [];
    }

    /**
     * Check if the model supports a specific capability.
     */
    public function supportsCapability(string $capability): bool
    {
        return in_array($capability, $this->getCapabilities(), true);
    }
}
