<?php

declare(strict_types=1);

namespace Sham\AI\Enums;

enum Capability: string
{
    case TEXT_GENERATION = 'text_generation';
    case TRANSLATION = 'translation';
    case SEO = 'seo';
    case IMAGE_GENERATION = 'image_generation';
    case IMAGE_EDITING = 'image_editing';

    public function getLabel(): string
    {
        return match ($this) {
            self::TEXT_GENERATION => __('sham-ai.settings.capabilities.text_generation'),
            self::TRANSLATION => __('sham-ai.settings.capabilities.translation'),
            self::SEO => __('sham-ai.settings.capabilities.seo'),
            self::IMAGE_GENERATION => __('sham-ai.settings.capabilities.image_generation'),
            self::IMAGE_EDITING => __('sham-ai.settings.capabilities.image_editing'),
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::TEXT_GENERATION => __('sham-ai.settings.capabilities_desc.text_generation'),
            self::TRANSLATION => __('sham-ai.settings.capabilities_desc.translation'),
            self::SEO => __('sham-ai.settings.capabilities_desc.seo'),
            self::IMAGE_GENERATION => __('sham-ai.settings.capabilities_desc.image_generation'),
            self::IMAGE_EDITING => __('sham-ai.settings.capabilities_desc.image_editing'),
        };
    }
}
