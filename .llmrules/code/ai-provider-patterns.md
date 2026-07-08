# AI Provider Patterns — sham-ai

Multi-provider AI system with capability segregation, model registry, and Prism adapter.

Source: `/home/basel/Development/ShamPackages/sham-ai/src/`

## 1) Core Contracts

| Contract | File:Line | Purpose |
|----------|-----------|---------|
| `AIProviderInterface` | `src/Contracts/AIProviderInterface.php:7` | Send prompts, check configuration, get name |
| `AIResponseInterface` | `src/Contracts/AIResponseInterface.php:7` | Standardized response (getText, isSuccessful, getUsage, getError) |
| `PromptInterface` | `src/Contracts/PromptInterface.php:7` | Prompt structure (systemPrompt, userPrompt, options) |
| `CapabilityInterface` | `src/Capabilities/CapabilityInterface.php:7` | Capability metadata (getName, getLabel, getDescription) |
| `AIPromptBuilderInterface` | `src/Contracts/AIPromptBuilderInterface.php:14` | Translation-specific prompt building |

## 2) Provider Pattern (Strategy/Adapter)

```
AIProviderInterface ← AbstractProviderAdapter (abstract)
    └── PrismAdapter (concrete, implements TranslationCapabilityInterface)
```

`AbstractProviderAdapter` (`src/Providers/Adapters/AbstractProviderAdapter.php:12`): Holds `AIModel`, provides `getName()` (delegates to `$model->provider`), leaves `send()` and `isConfigured()` abstract.

`PrismAdapter` (`src/Providers/Adapters/PrismAdapter.php:17`): Uses `Prism\Prism` for text generation. Handles 9 custom providers (Zhipu + 8 HuggingFace variants) via `CUSTOM_PROVIDERS` list. Supports both string provider names (custom) and `Provider` enum values (built-in).

## 3) Capability Segregation

4 capability interfaces:

| Interface | Extends | Methods | DTOs |
|-----------|---------|---------|------|
| `TranslationCapabilityInterface` | `CapabilityInterface` | `canTranslate()`, `translate()` | TranslationRequest/Response |
| `ContentGenerationCapabilityInterface` | `CapabilityInterface` | `canGenerateContent()`, `generate()`, `getSupportedContentTypes()` | ContentGenerationRequest/Response |
| `SEOCapabilityInterface` | `CapabilityInterface` | `canAnalyzeSEO()`, `analyzeSEO()`, `generateMetaTags()`, `suggestKeywords()`, `improveContentForSEO()` | SEORequest/Response, MetaTagsResponse |
| `ImageGenerationCapabilityInterface` | *(none)* | `generateImage()` | ImageGenerationRequest/Response |

`Capability` enum (`src/Enums/Capability.php:8`): Backed string enum with 4 cases: `TEXT_GENERATION`, `TRANSLATION`, `SEO`, `IMAGE_GENERATION`. Each has `getLabel()` and `getDescription()` returning localized strings.

## 4) Model Registry (`src/Models/`)

- `AIModel` — readonly DTO: id, name, provider, model, enabled, capabilities, config, options, priority
- `ModelRegistry` — collection management for AIModel instances (getAll, getEnabled, getByCapability, add, update, delete, enable, disable)
- `SupportedModels` — static provider/capability definitions
- `AIService::getRegistry()` — lazy-loads from settings, decrypts API keys via `Crypt::decryptString()`

API key encryption flow: encrypt on save (`saveModels()` line 291), decrypt on load (`getRegistry()` line 59).

## 5) AIService (`src/AIService.php`)

Central orchestrator:
- CRUD: `addModel()`, `updateModel()`, `deleteModel()`, `enableModel()`, `disableModel()`
- Events: `ModelCapabilityChanged`, `ModelDeleted`, `ModelDisabled`
- `getAdapter($modelId)`: returns `PrismAdapter` (TODO comment about future factory)
- `send(PromptInterface $prompt, ?$modelId)`: routes through adapter for first-enabled or specified model
- `translate()`: high-level convenience for translation capability
- Settings persistence via `SettingsServiceInterface::set()`

## 6) Provider Registration

`AIServiceProvider` (`src/Providers/AIServiceProvider.php:22`), extends `PluginServiceProvider`:
- `register()`: binds `AIService` as singleton with settings resolver closure
- `registerPrismProviders()`: registers 9 providers via `prism-manager.extend()`:

```
ZhipuProvider + LlamaProvider, MistralProvider, QwenProvider,
FluxProvider, SDProvider, SdxlProvider, NllbProvider, OpusMtProvider
```

## 7) Error Mapping

`PrismAdapter::mapError()` maps HTTP status codes to localized user messages:
- 401/403 → permissions error
- 402 → payment error
- 429 → rate limit error
- 503 → unavailable error
- default → generic error

Original errors are logged to Laravel's log channel.
