# HuggingFace Stable Diffusion

Learn how to configure and use Stable Diffusion image generation models via HuggingFace with Sham AI.

## Configuration

To use HuggingFace models, you need to provide your API key (Access Token) in the AI Settings.
You can get your Access Token from your [HuggingFace Settings](https://huggingface.co/settings/tokens).

## How to find the Model ID

When adding a custom Stable Diffusion model, you need to use the exact **Model ID** (e.g., `runwayml/stable-diffusion-v1-5`).

Here is how you can find the correct Model ID:

1. Go to [HuggingFace Hub Models](https://huggingface.co/models?pipeline_tag=text-to-image).
2. Filter for Text-to-Image models.
3. Click on the model you want to use.
4. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `runwayml/stable-diffusion-v1-5`
- `CompVis/stable-diffusion-v1-4`
