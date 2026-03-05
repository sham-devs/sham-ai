# HuggingFace Opus-MT

Learn how to configure and use Helsinki-NLP's Opus-MT models for translation via HuggingFace with Sham AI.

## Usage Steps

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. How to find the Model ID
Opus-MT models are language-pair specific.

Here is how you can find the correct Model ID:
1. Go to [HuggingFace Hub](https://huggingface.co/Helsinki-NLP).
2. Search for the language pair you need (e.g., `opus-mt-en-ar` for English to Arabic).
3. Click the copy icon next to the model name.

**Example Model IDs:**
- `Helsinki-NLP/opus-mt-en-ar` (English to Arabic)
- `Helsinki-NLP/opus-mt-ar-en` (Arabic to English)
