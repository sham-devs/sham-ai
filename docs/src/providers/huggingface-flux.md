# HuggingFace FLUX

Learn how to configure and use Black Forest Labs' FLUX image generation models via HuggingFace with Sham AI.

## Configuration

To use HuggingFace models, you need to provide your API key (Access Token) in the AI Settings.
You can get your Access Token from your [HuggingFace Settings](https://huggingface.co/settings/tokens).

## How to find the Model ID

When adding a custom FLUX model, you need to use the exact **Model ID** (e.g., `black-forest-labs/FLUX.1-schnell`).

Here is how you can find the correct Model ID:

1. Go to [HuggingFace Hub](https://huggingface.co/black-forest-labs).
2. Look for the specific FLUX model variant you want to use (schnell, dev, etc).
3. Click on the model.
4. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `black-forest-labs/FLUX.1-schnell` (Fastest version)
- `black-forest-labs/FLUX.1-dev` (Higher quality, but may have strict gating or usage limits)
