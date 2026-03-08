<?php

declare(strict_types=1);

namespace Sham\AI\Events;

class ModelDisabled
{
    public function __construct(
        public readonly string $modelId,
    ) {}
}
