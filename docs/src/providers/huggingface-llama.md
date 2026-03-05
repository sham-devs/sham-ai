# HuggingFace Llama

Learn how to configure and use Meta's Llama models via HuggingFace with Sham AI.

## Configuration

To use HuggingFace models, you need to provide your API key (Access Token) in the AI Settings.
You can get your Access Token from your [HuggingFace Settings](https://huggingface.co/settings/tokens).

## How to find the Model ID

When adding a custom Llama model, you need to use the exact **Model ID** (e.g., `meta-llama/Llama-3.1-8B-Instruct`).

Here is how you can find the correct Model ID:

1. Go to [HuggingFace Hub](https://huggingface.co/meta-llama).
2. Look for the specific Llama model you want to use. Make sure you select the `Instruct` or `Chat` version, not the base version.
3. Click on the model.
4. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `meta-llama/Llama-3.2-3B-Instruct`
- `meta-llama/Llama-3.1-8B-Instruct`
- `meta-llama/Llama-3.1-70B-Instruct`
