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
     * @param  array<string>  $capabilities  User-selected capabilities (e.g., ['translation', 'seo'])
     * @param  array  $config  Additional configuration (e.g., encrypted api_key)
     * @param  array  $options  Default options for the provider (e.g., src_lang, width, temperature)
     * @param  int  $priority  Priority for sorting
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $provider,
        public string $model,
        public bool $enabled = true,
        public array $capabilities = [],
        public array $config = [],
        public array $options = [],
        public int $priority = 0,
    ) {}

    /**
     * Get the capabilities for this model.
     *
     * Returns user-selected capabilities if set.
     * Falls back to provider-wide defaults if none are stored.
     *
     * @return array<string>
     */
    public function getCapabilities(): array
    {
        if (! empty($this->capabilities)) {
            return $this->capabilities;
        }

        return array_map(
            fn ($capability) => $capability->value,
            SupportedModels::getProviderCapabilities($this->provider)
        );
    }

    /**
     * Check if the model supports a specific capability.
     */
    public function supportsCapability(string $capability): bool
    {
        return in_array($capability, $this->getCapabilities(), true);
    }
}
