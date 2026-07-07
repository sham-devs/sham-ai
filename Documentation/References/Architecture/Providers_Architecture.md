# Providers

This document describes the available AI providers and their capabilities.

> **Source of Truth:** `Sham\AI\Models\SupportedModels` is the authoritative source for provider and capability information. This document reflects what's defined in code.

## Provider Categories

### Cloud Providers (via Prism)

These providers use the [Prism PHP](https://prismphp.com/) package:

| Provider ID | Name | Default Capabilities |
|------------|------|---------------------|
| `openai` | OpenAI | Text Generation, Translation, SEO, Image Generation |
| `anthropic` | Anthropic | Text Generation, Translation, SEO |
| `google` | Google Gemini | Text Generation, Translation, SEO, Image Generation |
| `deepseek` | DeepSeek | Text Generation, Translation, SEO |
| `xai` | xAI (Grok) | Text Generation, Translation, SEO |
| `mistral` | Mistral | Text Generation, Translation, SEO |

### Local Providers

| Provider ID | Name | Default Capabilities |
|------------|------|---------------------|
| `ollama` | Ollama (Local) | Text Generation, Translation |

### Custom Providers

| Provider ID | Name | Default Capabilities |
|------------|------|---------------------|
| `zhipu` | Zhipu (GLM) | Text Generation, Translation, SEO |

### HuggingFace Providers

| Provider ID | Name | Default Capabilities |
|------------|------|---------------------|
| `huggingface-nllb` | HuggingFace NLLB | Translation |
| `huggingface-opus-mt` | HuggingFace Opus-MT | Translation |
| `huggingface-llama` | HuggingFace Llama | Text Generation, Translation |
| `huggingface-qwen` | HuggingFace Qwen | Text Generation, Translation |
| `huggingface-mistral` | HuggingFace Mistral | Text Generation, Translation |
| `huggingface-flux` | HuggingFace FLUX | Image Generation |
| `huggingface-sd` | HuggingFace Stable Diffusion | Image Generation |
| `huggingface-sdxl` | HuggingFace SDXL | Image Generation |

## Capability Definitions

Capabilities are defined in `Sham\AI\Enums\Capability`:

| Capability | Description |
|------------|-------------|
| `TEXT_GENERATION` | General text generation |
| `TRANSLATION` | Text translation between languages |
| `SEO` | SEO analysis and meta tag generation |
| `IMAGE_GENERATION` | Image creation |

## Configuration

Each provider requires specific configuration:

### Common Config Fields

| Field | Required | Description |
|-------|----------|-------------|
| `api_key` | Yes* | API key for authentication |
| `base_url` | No | Custom API endpoint URL |
| `organization` | No | Organization ID (OpenAI only) |

*Not required for Ollama (local)

### Example Configuration

```php
// Adding a model via AIService
$ai->addModel([
    'name' => 'GPT-4o',
    'provider' => 'openai',
    'model' => 'gpt-4o',
    'capabilities' => ['translation', 'content_generation', 'seo'],
    'config' => [
        'api_key' => 'sk-...',
    ],
    'enabled' => true,
]);
```

## Provider Documentation

Detailed provider documentation is available in the user documentation at:

- [OpenAI](https://sham-packages.github.io/sham-ai/providers/openai)
- [Anthropic](https://sham-packages.github.io/sham-ai/providers/anthropic)
- [Google Gemini](https://sham-packages.github.io/sham-ai/providers/google)
- [xAI](https://sham-packages.github.io/sham-ai/providers/xai)
- [Mistral](https://sham-packages.github.io/sham-ai/providers/mistral)
- [Zhipu](https://sham-packages.github.io/sham-ai/providers/zhipu)
- [Ollama](https://sham-packages.github.io/sham-ai/providers/ollama)
- [DeepSeek](https://sham-packages.github.io/sham-ai/providers/deepseek)
- [HuggingFace Providers](https://sham-packages.github.io/sham-ai/providers/)

## Adding Custom Providers

To add a new provider:

1. Implement `AIProviderInterface` or extend Prism providers
2. Register in `AIServiceProvider::registerPrismProviders()`
3. Add to `SupportedModels::getProviders()`
4. Define capabilities in `SupportedModels::getProviderCapabilities()`
5. Add model info in `SupportedModels::getProviderModelInfo()`

## Adapter Pattern

The package uses adapters to wrap providers with capability-specific interfaces:

- `PrismAdapter` implements all capability interfaces
- `AbstractProviderAdapter` provides base functionality

Adapters are resolved via `AIService::getAdapter($modelId)`.
