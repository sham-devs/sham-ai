<?php

declare(strict_types=1);

namespace Sham\AI\Models;

use Illuminate\Support\Collection;

class ModelRegistry
{
    /**
     * @var Collection<string, AIModel>
     */
    protected Collection $models;

    public function __construct(array $models = [])
    {
        $this->models = collect($models)->mapWithKeys(function (AIModel $model) {
            return [$model->id => $model];
        });
    }

    /**
     * Get all models.
     *
     * @return Collection<string, AIModel>
     */
    public function getAll(): Collection
    {
        return $this->models;
    }

    /**
     * Get enabled models only.
     *
     * @return Collection<string, AIModel>
     */
    public function getEnabled(): Collection
    {
        return $this->models->filter->enabled;
    }

    /**
     * Get enabled models by capability.
     *
     * @return Collection<string, AIModel>
     */
    public function getByCapability(string $capability): Collection
    {
        return $this->getEnabled()->filter(function (AIModel $model) use ($capability) {
            return $model->supportsCapability($capability);
        });
    }

    /**
     * Get a model by ID.
     */
    public function get(string $id): ?AIModel
    {
        return $this->models->get($id);
    }

    /**
     * Add a model.
     */
    public function add(AIModel $model): void
    {
        $this->models->put($model->id, $model);
    }

    /**
     * Update a model.
     */
    public function update(string $id, array $data): void
    {
        $model = $this->get($id);

        if (! $model) {
            return;
        }

        $updatedModel = new AIModel(
            id: $model->id,
            name: $data['name'] ?? $model->name,
            provider: $data['provider'] ?? $model->provider,
            model: $data['model'] ?? $model->model,
            enabled: $data['enabled'] ?? $model->enabled,
            config: $data['config'] ?? $model->config,
            priority: $data['priority'] ?? $model->priority,
        );

        $this->models->put($id, $updatedModel);
    }

    /**
     * Delete a model.
     */
    public function delete(string $id): void
    {
        $this->models->forget($id);
    }

    /**
     * Enable a model.
     */
    public function enable(string $id): void
    {
        $this->update($id, ['enabled' => true]);
    }

    /**
     * Disable a model.
     */
    public function disable(string $id): void
    {
        $this->update($id, ['enabled' => false]);
    }
}
