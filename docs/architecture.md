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
