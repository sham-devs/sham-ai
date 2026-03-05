<?php

declare(strict_types=1);

namespace Sham\AI;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Sham\AI\Capabilities\CapabilityInterface;
use Sham\AI\Models\AIModel;
use Sham\AI\Models\ModelRegistry;
use Sham\AI\Providers\Adapters\AbstractProviderAdapter;
use Sham\AI\Providers\Adapters\PrismAdapter;

class AIService
{
    protected ?ModelRegistry $registry = null;

    /** @var callable */
    protected $settingsResolver;

    /**
     * Create a new AIService instance.
     *
     * @param  callable  $settingsResolver  A closure that resolves settings keys: fn(string $key, $default)
     */
    public function __construct(callable $settingsResolver)
    {
        $this->settingsResolver = $settingsResolver;
    }

    /**
     * Resolve a setting value.
     */
    protected function resolveSetting(string $key, $default = null): mixed
    {
        return ($this->settingsResolver)($key, $default);
    }

    /**
     * Get the model registry, lazily loaded from settings.
     */
    public function getRegistry(): ModelRegistry
    {
        if ($this->registry === null) {
            $modelsData = $this->resolveSetting('ai.models', []);

            $models = array_map(function (array $data) {
                // Decrypt API key if present
                if (isset($data['config']['api_key'])) {
                    try {
                        $data['config']['api_key'] = Crypt::decryptString($data['config']['api_key']);
                    } catch (\Throwable $e) {
                        // If decryption fails, it might be unencrypted (migration phase) or invalid
                    }
                }

                return new AIModel(
                    id: $data['id'],
                    name: $data['name'],
                    provider: $data['provider'],
                    model: $data['model'],
                    enabled: (bool) ($data['enabled'] ?? true),
                    config: $data['config'] ?? [],
                    priority: (int) ($data['priority'] ?? 0),
                );

            }, $modelsData);

            $this->registry = new ModelRegistry($models);
        }

        return $this->registry;
    }

    /**
     * Get all added models.
     */
    public function getModels(): Collection
    {
        return $this->getRegistry()->getAll();
    }

    /**
     * Get only enabled models.
     */
    public function getEnabledModels(): Collection
    {
        return $this->getRegistry()->getEnabled();
    }

    /**
     * Get enabled models that support a specific capability.
     */
    public function getModelsByCapability(string $capability): Collection
    {
        return $this->getRegistry()->getByCapability($capability);
    }

    /**
     * Get a specific model by ID.
     */
    public function getModel(string $modelId): ?AIModel
    {
        return $this->getRegistry()->get($modelId);
    }

    /**
     * Add a new model.
     */
    public function addModel(array $data): AIModel
    {
        $id = $data['id'] ?? uniqid($data['provider'].'-', false);

        $model = new AIModel(
            id: $id,
            name: $data['name'],
            provider: $data['provider'],
            model: $data['model'],
            enabled: (bool) ($data['enabled'] ?? true),
            config: $data['config'] ?? [],
            priority: (int) ($data['priority'] ?? 0),
        );

        $this->getRegistry()->add($model);
        $this->saveModels();

        return $model;
    }

    /**
     * Update an existing model.
     */
    public function updateModel(string $modelId, array $data): void
    {
        $this->getRegistry()->update($modelId, $data);
        $this->saveModels();
    }

    /**
     * Delete a model.
     */
    public function deleteModel(string $modelId): void
    {
        $this->getRegistry()->delete($modelId);
        $this->saveModels();
    }

    /**
     * Enable a model.
     */
    public function enableModel(string $modelId): void
    {
        $this->getRegistry()->enable($modelId);
        $this->saveModels();
    }

    /**
     * Disable a model.
     */
    public function disableModel(string $modelId): void
    {
        $this->getRegistry()->disable($modelId);
        $this->saveModels();
    }

    /**
     * Update all models from an array (typically from settings UI).
     */
    public function updateModels(array $modelsData): void
    {
        $models = array_map(function (array $data) {
            return new AIModel(
                id: $data['id'],
                name: $data['name'],
                provider: $data['provider'],
                model: $data['model'],
                enabled: (bool) ($data['enabled'] ?? true),
                config: $data['config'] ?? [],
                priority: (int) ($data['priority'] ?? 0),
            );
        }, $modelsData);

        $this->registry = new ModelRegistry($models);
        $this->saveModels();
    }

    /**
     * Get an adapter for a specific model.
     */
    public function getAdapter(string $modelId): AbstractProviderAdapter
    {
        $model = $this->getModel($modelId);

        if (! $model) {
            throw new \InvalidArgumentException("Model not found: {$modelId}");
        }

        // For now, all models use PrismAdapter.
        // In the future, this can be a factory based on provider.
        return new PrismAdapter($model);
    }

    /**
     * Get an adapter with a specific capability check.
     */
    public function getAdapterWithCapability(string $modelId, string $capabilityInterface): ?CapabilityInterface
    {
        $adapter = $this->getAdapter($modelId);

        if ($adapter instanceof $capabilityInterface) {
            return $adapter;
        }

        return null;
    }

    /**
     * Send a prompt to the AI.
     */
    public function send(\Sham\AI\Contracts\PromptInterface $prompt, ?string $modelId = null): \Sham\AI\Contracts\AIResponseInterface
    {
        if ($modelId) {
            $model = $this->getModel($modelId);
        } else {
            // Find first enabled model
            $model = $this->getEnabledModels()->first();
        }

        if (! $model) {
            throw new \RuntimeException('No enabled AI model found');
        }

        return $this->getAdapter($model->id)->send($prompt);
    }

    /**
     * Save/persist models back to settings.
     */
    protected function saveModels(): void
    {
        $models = $this->getRegistry()->getAll()->map(function (AIModel $model) {
            $data = [
                'id' => $model->id,
                'name' => $model->name,
                'provider' => $model->provider,
                'model' => $model->model,
                'enabled' => $model->enabled,
                'config' => $model->config,
                'priority' => $model->priority,
            ];

            // Encrypt API key before saving
            if (isset($data['config']['api_key']) && ! empty($data['config']['api_key'])) {
                $data['config']['api_key'] = Crypt::encryptString($data['config']['api_key']);
            }

            return $data;
        })->values()->toArray();

        // Use SettingsService if available to save
        if (app()->bound(\App\Services\Settings\SettingsService::class)) {
            app(\App\Services\Settings\SettingsService::class)->set('ai.models', $models);
        }
    }

    /**
     * Check if at least one model is enabled.
     */
    public function hasEnabledModels(): bool
    {
        return $this->getEnabledModels()->isNotEmpty();
    }

    /**
     * Check if at least one model is enabled and configured.
     */
    public function isConfigured(): bool
    {
        return $this->hasEnabledModels();
    }

    /**
     * Check if a specific capability is enabled (has at least one enabled model).
     */
    public function isCapabilityEnabled(string $capability): bool
    {
        return $this->getRegistry()->getByCapability($capability)->isNotEmpty();
    }

    /**
     * Alias for isCapabilityEnabled.
     */
    public function hasCapabilityEnabled(string $capability): bool
    {
        return $this->isCapabilityEnabled($capability);
    }

    /**
     * Get enabled models for a specific capability.
     */
    public function getEnabledModelsForCapability(string $capability): Collection
    {
        return $this->getRegistry()->getByCapability($capability);
    }

    /**
     * Get the libraries that use this model.
     */
    public function getModelUsage(string $modelId): array
    {
        $usage = [];

        // Check translation settings if sham-translation is installed
        try {
            if (app()->bound(\App\Services\Settings\SettingsService::class)) {
                $settingsService = app(\App\Services\Settings\SettingsService::class);
                $modelIdStr = $settingsService->get('sham-translation.model_id');

                if ($modelIdStr === $modelId) {
                    $usage[] = 'sham-translation';
                }
            }
        } catch (\Throwable $e) {
            // Settings might not be available yet
        }

        return $usage;
    }

    /**
     * Check if a model can be disabled (is not used by any library).
     */
    public function canDisableModel(string $modelId): bool
    {
        return empty($this->getModelUsage($modelId));
    }

    /**
     * Get providers that have at least one enabled model for a capability.
     *
     * @return array<string, string>
     */
    public function getProvidersWithCapability(string $capability): array
    {
        $enabledModels = $this->getModelsByCapability($capability);
        $providers = $enabledModels->pluck('provider')->unique();

        $allProviders = \Sham\AI\Models\SupportedModels::getProviders();

        return collect($allProviders)
            ->filter(fn ($name, $id) => $providers->contains($id))
            ->toArray();
    }

    /**
     * Get all capabilities supported by a specific provider.
     *
     * @return array<string>
     */
    public function getProviderCapabilities(string $provider): array
    {
        return \Sham\AI\Models\SupportedModels::getProviderCapabilities($provider);
    }

    /**
     * Translate texts using the first available model with translation capability.
     *
     * @param  array<string>  $texts
     */
    public function translate(array $texts, string $from, string $to, ?string $modelId = null): array
    {
        if ($modelId) {
            $model = $this->getModel($modelId);
        } else {
            $model = $this->getModelsByCapability('translation')->first();
        }

        if (! $model) {
            throw new \RuntimeException(__('sham-ai::sham-ai.settings.messages.no_translation_models'));
        }

        $adapter = $this->getAdapter($model->id);

        if (! $adapter instanceof \Sham\AI\Capabilities\Contracts\TranslationCapabilityInterface) {
            throw new \RuntimeException("Model {$model->id} does not support translation capability");
        }

        $request = new \Sham\AI\Capabilities\DTOs\TranslationRequest(
            texts: $texts,
            fromLocale: $from,
            toLocale: $to
        );

        $response = $adapter->translate($request);

        if (! $response->successful) {
            throw new \RuntimeException($response->error ?: 'Translation failed');
        }

        return $response->translations;
    }
}
