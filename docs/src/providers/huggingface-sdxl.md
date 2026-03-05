# HuggingFace SDXL

Learn how to configure and use Stable Diffusion XL image generation models via HuggingFace with Sham AI.

## Usage Steps

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. How to find the Model ID
When adding a custom SDXL model, you need to use the exact **Model ID** (e.g., `stabilityai/stable-diffusion-xl-base-1.0`).

Here is how you can find the correct Model ID:
1. Go to [HuggingFace Hub](https://huggingface.co/stabilityai).
2. Look for the specific SDXL model variant you want to use.
3. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `stabilityai/stable-diffusion-xl-base-1.0`
- `stabilityai/stable-diffusion-xl-refiner-1.0`
