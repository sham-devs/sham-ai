# HuggingFace Mistral

Learn how to configure and use Mistral models via HuggingFace with Sham AI.

## Usage Steps

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. How to find the Model ID
When adding a custom Mistral model, you need to use the exact **Model ID** (e.g., `mistralai/Mistral-7B-Instruct-v0.3`).

Here is how you can find the correct Model ID:
1. Go to [HuggingFace Hub](https://huggingface.co/mistralai).
2. Look for the specific Mistral model you want to use. Make sure you select the `Instruct` or `Chat` version, not the base version.
3. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `mistralai/Mistral-7B-Instruct-v0.3`
- `mistralai/Mixtral-8x7B-Instruct-v0.1`
