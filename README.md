# Sham AI Package

**Version:** 1.0.0
**Author:** Sham
**License:** MIT

## Overview

Sham AI is a **Sham-oriented package** designed for the Sham ecosystem. It provides a clean abstraction layer for AI/LLM integration in Laravel applications with multi-provider support, model registry, and capability-based routing.

## Package Identity

This is a **Sham-shared package**, designed for reuse across Sham applications. It is:

- Integrated with Sham's plugin bootstrapping system
- Using Sham's settings infrastructure for configuration
- Not a standalone generic Laravel package

The package expects a Sham-style host application that provides:
- `Sham\Core\Plugins\PluginServiceProvider` base class
- `Sham\Core\Settings\BaseSettingsProvider` for settings integration
- `Sham\Core\Contracts\Settings\SettingsServiceInterface` for settings storage

## Features

- **Provider Abstraction** - Switch between AI providers without changing code
- **Model Registry** - Settings-backed model management with capabilities
- **Capabilities System** - Translation, Content Generation, SEO support
- **Multiple Providers** - OpenAI, Anthropic, Gemini, Zhipu, HuggingFace, Ollama, and more
- **Type Safety** - Full PHP 8.4+ type hints and strict types

## Installation

```bash
composer require sham/ai
```

## Quick Start

```php
use Sham\AI\AIService;

$ai = app(AIService::class);

// Check if configured
if ($ai->isConfigured()) {
    // Get models by capability
    $translationModels = $ai->getModelsByCapability('translation');

    // Translate texts
    $translations = $ai->translate(
        texts: ['Hello', 'Welcome'],
        from: 'en',
        to: 'ar'
    );
}
```

## Core Concepts

### Models and Capabilities

Models are configured through settings and have capabilities:

- `translation` - Text translation
- `content_generation` - Content/article generation
- `seo` - SEO analysis and meta tag generation
- `image_generation` - Image creation

### AIService Methods

| Method | Description |
|--------|-------------|
| `getModels()` | Get all configured models |
| `getEnabledModels()` | Get only enabled models |
| `getModelsByCapability(string $capability)` | Get models supporting a capability |
| `getModel(string $modelId)` | Get a specific model |
| `addModel(array $data)` | Add a new model |
| `updateModel(string $modelId, array $data)` | Update a model |
| `deleteModel(string $modelId)` | Delete a model |
| `getAdapter(string $modelId)` | Get provider adapter for a model |
| `translate(array $texts, string $from, string $to, ?string $modelId)` | Translate texts |
| `isConfigured()` | Check if at least one model is enabled |
| `isCapabilityEnabled(string $capability)` | Check if capability has enabled models |

### Capabilities

Each capability has its own contract and DTOs:

#### Translation
```php
use Sham\AI\Capabilities\Contracts\TranslationCapabilityInterface;
use Sham\AI\Capabilities\DTOs\TranslationRequest;
use Sham\AI\Capabilities\DTOs\TranslationResponse;

$adapter = $ai->getAdapterWithCapability($modelId, TranslationCapabilityInterface::class);
$request = new TranslationRequest(
    texts: ['Hello'],
    fromLocale: 'en',
    toLocale: 'ar'
);
$response = $adapter->translate($request);
```

#### Content Generation
```php
use Sham\AI\Capabilities\Contracts\ContentGenerationCapabilityInterface;
use Sham\AI\Capabilities\DTOs\ContentGenerationRequest;

$request = new ContentGenerationRequest(
    type: 'article',
    topic: 'AI in Healthcare',
    locale: 'en'
);
```

#### SEO
```php
use Sham\AI\Capabilities\Contracts\SEOCapabilityInterface;
use Sham\AI\Capabilities\DTOs\SEORequest;

$request = new SEORequest(
    content: $pageContent,
    locale: 'en',
    url: 'https://example.com/page'
);
```

## Settings

All configuration is managed through Sham Settings:

| Key | Type | Description |
|-----|------|-------------|
| `sham-ai.models` | array | List of configured AI models |

Each model in `sham-ai.models` has:
- `id` - Unique identifier
- `name` - Display name
- `provider` - Provider ID (openai, anthropic, etc.)
- `model` - Model name (gpt-4o, claude-3-5-sonnet, etc.)
- `enabled` - Whether model is enabled
- `capabilities` - Array of supported capabilities
- `config` - Provider configuration (api_key, etc.)
- `options` - Model options (temperature, max_tokens, etc.)
- `priority` - Selection priority

## Supported Providers

- **OpenAI** - GPT-4o, GPT-4o-mini, GPT-3.5-turbo, GPT-4-turbo
- **Anthropic** - Claude 3.5 Sonnet, Claude 3 Opus, Claude 3 Haiku
- **Google Gemini** - Gemini 1.5 Pro, Gemini 1.5 Flash
- **Zhipu** - GLM models
- **HuggingFace** - Llama, Qwen, Mistral, Flux, SD, SDXL, NLLB, Opus-MT
- **Ollama** - Local models
- **Mistral** - Mistral models
- **xAI** - Grok models
- **DeepSeek** - DeepSeek models

## Events

The package dispatches these events:

| Event | Description |
|-------|-------------|
| `Sham\AI\Events\ModelDeleted` | Fired when a model is deleted |
| `Sham\AI\Events\ModelDisabled` | Fired when a model is disabled |
| `Sham\AI\Events\ModelCapabilityChanged` | Fired when model capabilities change |

## File Structure

```
src/
├── AIPackage.php                  # Package helper
├── AIService.php                  # Main service
├── Contracts/
│   ├── AIProviderInterface.php    # Provider contract
│   ├── AIResponseInterface.php    # Response contract
│   ├── PromptInterface.php        # Prompt contract
│   └── AIPromptBuilderInterface.php # Prompt builder contract
├── Capabilities/
│   ├── CapabilityInterface.php    # Base capability interface
│   ├── Contracts/
│   │   ├── TranslationCapabilityInterface.php
│   │   ├── ContentGenerationCapabilityInterface.php
│   │   ├── SEOCapabilityInterface.php
│   │   └── ImageGenerationCapabilityInterface.php
│   ├── DTOs/
│   │   ├── TranslationRequest.php
│   │   ├── TranslationResponse.php
│   │   ├── ContentGenerationRequest.php
│   │   ├── ContentGenerationResponse.php
│   │   ├── SEORequest.php
│   │   ├── SEOResponse.php
│   │   ├── MetaTagsResponse.php
│   │   ├── ImageGenerationRequest.php
│   │   └── ImageGenerationResponse.php
│   └── */Prompts/
│       ├── TranslationPrompt.php
│       ├── FileTranslationPrompt.php
│       ├── ContentGenerationPrompt.php
│       └── SEOPrompt.php
├── Console/Commands/
│   └── AIScanCommand.php       # Translation scan command
├── Enums/
│   └── Capability.php           # Capability enum
├── Events/
│   ├── ModelDeleted.php
│   ├── ModelDisabled.php
│   └── ModelCapabilityChanged.php
├── Models/
│   ├── AIModel.php              # Model DTO
│   ├── ModelRegistry.php       # In-memory registry
│   └── SupportedModels.php     # Provider/model definitions
├── Providers/
│   ├── AIServiceProvider.php    # Laravel service provider
│   ├── PrismProvider.php        # Prism integration
│   ├── ZhipuProvider.php        # Zhipu integration
│   ├── Adapters/
│   │   ├── AbstractProviderAdapter.php
│   │   └── PrismAdapter.php
│   ├── Responses/
│   │   └── PrismResponse.php
│   └── HuggingFace/
│       ├── BaseHuggingFaceProvider.php
│       ├── FluxProvider.php
│       ├── LlamaProvider.php
│       ├── MistralProvider.php
│       ├── NllbProvider.php
│       ├── OpusMtProvider.php
│       ├── QwenProvider.php
│       ├── SDProvider.php
│       └── SdxlProvider.php
└── Settings/
    ├── AISettingsProvider.php   # Settings definitions
    └── Concerns/
        ├── AISettingsCards.php
        └── AISettingsFields.php
```

## Documentation

- **Developer Documentation**: [docs/](docs/architecture.md) - Architecture, contracts, and internal APIs

## License

MIT
