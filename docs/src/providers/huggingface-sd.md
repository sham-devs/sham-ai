# HuggingFace Stable Diffusion

Learn how to configure and use Stable Diffusion image generation models via HuggingFace with Sham AI.

## Usage Steps

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. How to find the Model ID
When adding a custom Stable Diffusion model, you need to use the exact **Model ID** (e.g., `stable-diffusion-v1-5/stable-diffusion-v1-5`).

Here is how you can find the correct Model ID:
1. Go to [HuggingFace Hub](https://huggingface.co/models?search=stable-diffusion).
2. Look for the specific Stable Diffusion version you want to use (v1.5, v2.1, etc).
3. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `stable-diffusion-v1-5/stable-diffusion-v1-5`
- `stabilityai/stable-diffusion-2-1`
