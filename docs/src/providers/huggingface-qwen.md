# HuggingFace Qwen

Learn how to configure and use Alibaba's Qwen models via HuggingFace with Sham AI.

## Configuration

To use HuggingFace models, you need to provide your API key (Access Token) in the AI Settings.
You can get your Access Token from your [HuggingFace Settings](https://huggingface.co/settings/tokens).

## How to find the Model ID

When adding a custom Qwen model, you need to use the exact **Model ID** (e.g., `Qwen/Qwen2.5-72B-Instruct`).

Here is how you can find the correct Model ID:

1. Go to [HuggingFace Hub](https://huggingface.co/Qwen).
2. Look for the specific Qwen model you want to use. Make sure you select the `Instruct` version, not the base version.
3. Click on the model.
4. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `Qwen/Qwen2.5-72B-Instruct`
- `Qwen/Qwen2.5-14B-Instruct`
- `Qwen/Qwen2.5-Coder-32B-Instruct`
