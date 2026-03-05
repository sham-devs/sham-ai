# HuggingFace Opus-MT

Learn how to configure and use Helsinki-NLP Opus-MT translation models via HuggingFace with Sham AI.

## Configuration

To use HuggingFace models, you need to provide your API key (Access Token) in the AI Settings.
You can get your Access Token from your [HuggingFace Settings](https://huggingface.co/settings/tokens).

## How to find the Model ID

When adding a custom Opus-MT model, you need to use the exact **Model ID** (e.g., `Helsinki-NLP/opus-mt-en-ar`).

Here is how you can find the correct Model ID:

1. Go to [HuggingFace Hub](https://huggingface.co/Helsinki-NLP).
2. Search for the specific language pair you need in the format `opus-mt-{source}-{target}`.
3. Click on the model you want to use.
4. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `Helsinki-NLP/opus-mt-en-ar` (English to Arabic)
- `Helsinki-NLP/opus-mt-ar-en` (Arabic to English)
- `Helsinki-NLP/opus-mt-en-fr` (English to French)
