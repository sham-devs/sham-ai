# HuggingFace NLLB

Learn how to configure and use HuggingFace's No Language Left Behind (NLLB) translation models with Sham AI.

## Configuration

To use HuggingFace models, you need to provide your API key (Access Token) in the AI Settings.
You can get your Access Token from your [HuggingFace Settings](https://huggingface.co/settings/tokens).

## How to find the Model ID

When adding a custom NLLB model, you need to use the exact **Model ID** (e.g., `facebook/nllb-200-distilled-600M`).

Here is how you can find the correct Model ID:

1. Go to [HuggingFace Hub](https://huggingface.co/models?search=nllb).
2. Search for `nllb` models, typically published by `facebook`.
3. Click on the model you want to use.
4. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

**Example Model IDs:**
- `facebook/nllb-200-distilled-600M` (Fast and lightweight)
- `facebook/nllb-200-1.3B` (Better quality, requires more resources)
- `facebook/nllb-200-3.3B` (Highest quality)
