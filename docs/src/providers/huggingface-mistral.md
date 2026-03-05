# HuggingFace Mistral

Learn how to configure and use Mistral AI models via HuggingFace with Sham AI.

## Configuration

To use HuggingFace models, you need to provide your API key (Access Token) in the AI Settings.
You can get your Access Token from your [HuggingFace Settings](https://huggingface.co/settings/tokens).

## How to find the Model ID

When adding a custom Mistral model via HuggingFace, you need to use the exact **Model ID** (e.g., `mistralai/Mistral-Nemo-Instruct-2407`).

Here is how you can find the correct Model ID:

1. Go to [HuggingFace Hub](https://huggingface.co/mistralai).
2. Look for the specific Mistral model you want to use. Make sure you select an `Instruct` version for chat.
3. Click on the model.
4. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `mistralai/Mistral-Nemo-Instruct-2407`
- `mistralai/Mixtral-8x7B-Instruct-v0.1`
- `mistralai/Mistral-7B-Instruct-v0.3`
