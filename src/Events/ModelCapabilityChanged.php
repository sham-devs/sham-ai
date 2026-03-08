<?php

declare(strict_types=1);

namespace Sham\AI\Events;

class ModelCapabilityChanged
{
    public function __construct(
        public readonly string $modelId,
        public readonly array $addedCapabilities,
        public readonly array $removedCapabilities,
    ) {}
}
