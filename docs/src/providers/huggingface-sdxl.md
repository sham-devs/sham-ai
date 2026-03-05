# HuggingFace SDXL

Learn how to configure and use Stability AI's SDXL image generation models via HuggingFace with Sham AI.

## Configuration

To use HuggingFace models, you need to provide your API key (Access Token) in the AI Settings.
You can get your Access Token from your [HuggingFace Settings](https://huggingface.co/settings/tokens).

## How to find the Model ID

When adding a custom SDXL model, you need to use the exact **Model ID** (e.g., `stabilityai/stable-diffusion-xl-base-1.0`).

Here is how you can find the correct Model ID:

1. Go to [HuggingFace Hub](https://huggingface.co/stabilityai).
2. Look for the specific SDXL model variant you want to use.
3. Click on the model.
4. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `stabilityai/stable-diffusion-xl-base-1.0`
- `stabilityai/sdxl-turbo` (Faster, lower steps required)
