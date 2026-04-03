# AI Providers

Sham AI integrates with multiple AI providers through Prism. Configure models in Settings using your own API keys.

## How It Works

Sham AI uses a **provider + model** system:

- **Provider**: The service provider (OpenAI, Anthropic, HuggingFace, etc.)
- **Model ID**: The specific model identifier you enter (e.g., `gpt-4o`, `claude-3-7-sonnet-latest`)

You can add multiple models from different providers and assign them different capabilities.

## Adding a Model

1. Go to **Settings → AI → Models**
2. Click **Add Model**
3. Select a **Provider** from the dropdown
4. Enter the **Model ID** (check provider documentation below for examples)
5. Enter your **API Key** in the configuration section
6. Select the **Capabilities** this model supports
7. Save the model

## Capability-Based Routing

Models are assigned capabilities that determine which features they can be used for:

| Capability | Description |
|------------|-------------|
| `translation` | Text translation between languages |
| `content_generation` | Article and content generation |
| `seo` | SEO analysis and meta tag generation |
| `image_generation` | Image creation from text prompts |

Different models support different capabilities. The system filters available models based on what each provider supports.

## Source of Truth

Provider data is maintained in `src/Models/SupportedModels.php`:

- **Provider IDs and Labels**: Canonical list of supported providers
- **Capabilities**: Default capabilities per provider
- **Example Models**: Primary example model ID for each provider
- **Doc URLs**: Links to this documentation (used in Settings UI)

The sidebar navigation is generated from `site/data/providers.json`, which is produced by running:

```bash
php scripts/generate-providers-docs.php
```

Run this script when updating provider data in SupportedModels.php.

## Example Model IDs

The Model ID is the exact string used in the provider's API. It's your responsibility to enter a valid model ID.

Below are **example** model IDs for reference. Check each provider's official documentation for the current list of available models.

## Providers

| Provider | Capabilities | Type |
| :--- | :--- | :--- |
| [OpenAI](/providers/openai) | Text, Translation, SEO, Images | Frontier API |
| [Anthropic](/providers/anthropic) | Text, Translation, SEO | Frontier API |
| [Google](/providers/google) | Text, Translation, SEO, Images | Frontier API |
| [xAI](/providers/xai) | Text, Translation, SEO | Frontier API |
| [Mistral](/providers/mistral) | Text, Translation, SEO | Frontier API |
| [DeepSeek](/providers/deepseek) | Text, Translation, SEO | Frontier API |
| [Zhipu](/providers/zhipu) | Text, Translation, SEO | Frontier API |
| [Ollama](/providers/ollama) | Text, Translation | Local |
| [HuggingFace NLLB](/providers/huggingface-nllb) | Translation | HuggingFace |
| [HuggingFace Opus-MT](/providers/huggingface-opus-mt) | Translation | HuggingFace |
| [HuggingFace Llama](/providers/huggingface-llama) | Text, Translation | HuggingFace |
| [HuggingFace Qwen](/providers/huggingface-qwen) | Text, Translation | HuggingFace |
| [HuggingFace Mistral](/providers/huggingface-mistral) | Text, Translation | HuggingFace |
| [HuggingFace FLUX](/providers/huggingface-flux) | Images | HuggingFace |
| [HuggingFace SD](/providers/huggingface-sd) | Images | HuggingFace |
| [HuggingFace SDXL](/providers/huggingface-sdxl) | Images | HuggingFace |

## Programmatic Usage

```php
use Sham\AI\AIService;

$ai = app(AIService::class);

// Check if configured
if ($ai->isConfigured()) {
    // Translate text
    $translations = $ai->translate(
        texts: ['Hello', 'Welcome'],
        from: 'en',
        to: 'ar'
    );
}
```

## Notes

- **API Keys**: You provide your own API keys for each provider
- **Costs**: Usage is billed directly by each provider according to their pricing
- **Rate Limits**: Each provider has their own rate limits
- **Model Availability**: Some models require special access or subscriptions
