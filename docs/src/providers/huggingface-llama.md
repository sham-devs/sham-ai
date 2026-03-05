# HuggingFace Llama

Learn how to configure and use Meta's Llama models via HuggingFace with Sham AI.

## Usage Steps

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. How to find the Model ID
When adding a custom Llama model, you need to use the exact **Model ID** (e.g., `meta-llama/Llama-3.2-3B-Instruct`).

Here is how you can find the correct Model ID:
1. Go to [HuggingFace Hub](https://huggingface.co/meta-llama).
2. Look for the specific Llama model you want to use. Make sure you select the `Instruct` or `Chat` version, not the base version.
3. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `meta-llama/Llama-3.2-3B-Instruct`
- `meta-llama/Llama-3.1-8B-Instruct`
- `meta-llama/Llama-3.1-70B-Instruct`
