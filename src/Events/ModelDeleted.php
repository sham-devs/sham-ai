<?php

declare(strict_types=1);

namespace Sham\AI\Events;

use Sham\AI\Models\AIModel;

class ModelDeleted
{
    public function __construct(
        public readonly string $modelId,
        public readonly AIModel $model,
    ) {}
}
