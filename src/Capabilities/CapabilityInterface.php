<?php

declare(strict_types=1);

namespace Sham\AI\Capabilities;

interface CapabilityInterface
{
    /**
     * Get the machine name of the capability.
     */
    public static function getCapabilityName(): string;

    /**
     * Get the human-readable label of the capability.
     */
    public static function getCapabilityLabel(): string;

    /**
     * Get a description of the capability.
     */
    public static function getCapabilityDescription(): string;
}
