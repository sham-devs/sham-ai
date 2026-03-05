# AI Providers

Sham AI integrates with a multitude of providers through Prism. Follow the instructions below to configure and use the models.

## How the System Works
The system does not use a "subscription" in the traditional sense, but instead relies on adding AI models with your own API key.

## Available Hugging Face Providers
| Provider | Usage |
| :--- | :--- |
| **huggingface-nllb** | Translation (NLLB models) |
| **huggingface-opus-mt** | Translation (Opus-MT models) |
| **huggingface-llama** | Text Generation |
| **huggingface-qwen** | Text Generation |
| **huggingface-mistral** | Text Generation |
| **huggingface-flux** | Image Generation |
| **huggingface-sd** | Image Generation (Stable Diffusion) |
| **huggingface-sdxl** | Image Generation (SDXL) |

## Usage Steps

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. Adding a Model via Settings
Through the system's settings interface, you can:
- Add a new model.
- Choose the provider (e.g., `huggingface-llama`).
- Enter the API key.
- Specify the actual model name (e.g., `meta-llama/Llama-3.2-3B-Instruct`).

### 3. Programmatic Usage

```php
use Sham\AI\AIService;

// Translation
$response = app(AIService::class)->translate(
    ['Hello World'],
    'en',
    'ar'
);

// Image Generation (Using flux/sd/sdxl)
$response = app(AIService::class)->generateImage([
    'prompt' => 'A beautiful sunset over mountains',
    'provider' => 'huggingface-flux',
    'model' => 'black-forest-labs/FLUX.1-schnell'
]);
```

## Important Notes
- **Free vs. Paid Models**: Some Hugging Face models are free, while others require a Pro subscription.
- **Gated Models**: Some models like Llama require access request and approval.
- **Limits**: Free accounts have limits on the number of requests.

---

## Other Providers
- [OpenAI](/providers/openai)
- [Anthropic](/providers/anthropic)
- [Google](/providers/google)
- [xAI](/providers/xai)
- [Mistral](/providers/mistral)
- [Zhipu](/providers/zhipu)
- [Ollama](/providers/ollama)
- [DeepSeek](/providers/deepseek)
