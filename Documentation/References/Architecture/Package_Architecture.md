# Architecture

## Package Identity

`sham/ai` is a **Sham-oriented package** designed for the Sham ecosystem.

### Sham Integration

The package integrates with Sham's shared infrastructure:

- **Plugin Bootstrapping**: Extends `Sham\Core\Plugins\PluginServiceProvider`
- **Settings System**: Uses `Sham\Core\Settings\BaseSettingsProvider`
- **Plugin Interface**: Implements `Sham\Core\Contracts\Plugins\PluginInterface`

## Main Components

### AIService

The main entry point for all AI operations. It provides:

- Model registry management (CRUD operations)
- Capability-based model selection
- Provider adapter resolution
- Encryption/decryption of API keys

### ModelRegistry

In-memory registry for AI models. Handles:

- Model storage and retrieval
- Capability filtering
- Enable/disable state management

### Capabilities System

Capability-based routing allows different models to support different features:

| Capability | Interface | Description |
|------------|-----------|-------------|
| Translation | `TranslationCapabilityInterface` | Text translation between languages |
| Content Generation | `ContentGenerationCapabilityInterface` | Article/content generation |
| SEO | `SEOCapabilityInterface` | SEO analysis and meta tag generation |
| Image Generation | `ImageGenerationCapabilityInterface` | Image creation |

### Provider Adapters

Adapters wrap Prism providers with capability interfaces:

- `PrismAdapter` - Main adapter implementing all capabilities
- `AbstractProviderAdapter` - Base adapter class

### SupportedModels

The `SupportedModels` class is the authoritative source for:
- Available provider IDs
- Provider display names
- Model-to-provider mappings
- Provider capability declarations

## Data Flow

### Model Management Flow

1. Settings store model configurations in `sham-ai.models`
2. AIService loads models into ModelRegistry
3. API keys are encrypted at rest
4. Models filtered by capability

### Translation Flow

1. Request received with texts and locales
2. Model selected by capability
3. Adapter resolves with PrismProvider
4. Prompt built from TranslationPrompt
5. Response parsed into TranslationResponse

## Settings Integration

The package uses `AISettingsProvider` which:

- Exposes `sham-ai.models` settings collection
- Defines field definitions for model configuration
- Masks sensitive fields (API keys)

## Host Boundaries

This package does NOT own:

- Business logic and prompts
- Project-specific model configurations
- UI for managing models
- Authentication and authorization for settings

## Extension Points

### Adding a New Provider

1. Implement `AIProviderInterface` or extend existing Prism providers
2. Register in `AIServiceProvider::registerPrismProviders()`
3. Add provider to `SupportedModels::getProviders()`
4. Add models to `SupportedModels::getModelsForProvider()`

### Adding a New Capability

1. Create interface in `Capabilities/Contracts/`
2. Create request/response DTOs in `Capabilities/DTOs/`
3. Create prompt class in `Capabilities/*/Prompts/`
4. Implement in adapter (`PrismAdapter`)

---

## (Extracted from README.md)

### Overview

Sham AI is a **Sham-oriented package** designed for the Sham ecosystem. It provides a clean abstraction layer for AI/LLM integration in Laravel applications with multi-provider support, model registry, and capability-based routing.

### Package Identity

This is a **Sham-shared package**, designed for reuse across Sham applications. It is:

- Integrated with Sham's plugin bootstrapping system
- Using Sham's settings infrastructure for configuration
- Not a standalone generic Laravel package

The package expects a Sham-style host application that provides:
- `Sham\Core\Plugins\PluginServiceProvider` base class
- `Sham\Core\Settings\BaseSettingsProvider` for settings integration
- `Sham\Core\Contracts\Settings\SettingsServiceInterface` for settings storage

### Features

- **Provider Abstraction** - Switch between AI providers without changing code
- **Model Registry** - Settings-backed model management with capabilities
- **Capabilities System** - Translation, Content Generation, SEO support
- **Multiple Providers** - OpenAI, Anthropic, Gemini, Zhipu, HuggingFace, Ollama, and more
- **Type Safety** - Full PHP 8.4+ type hints and strict types

### Core Concepts

#### Models and Capabilities

Models are configured through settings and have capabilities:

- `translation` - Text translation
- `content_generation` - Content/article generation
- `seo` - SEO analysis and meta tag generation
- `image_generation` - Image creation

Capabilities are defined in `Sham\AI\Enums\Capability`.

### Events

The package dispatches these events:

| Event | Description |
|-------|-------------|
| `Sham\AI\Events\ModelDeleted` | Fired when a model is deleted |
| `Sham\AI\Events\ModelDisabled` | Fired when a model is disabled |
| `Sham\AI\Events\ModelCapabilityChanged` | Fired when model capabilities change |

### File Structure

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
