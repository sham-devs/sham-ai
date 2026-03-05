# HuggingFace NLLB

Learn how to configure and use Meta's NLLB (No Language Left Behind) models for translation via HuggingFace with Sham AI.

## Usage Steps

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. How to find the Model ID
By default, Sham AI uses the distilled 600M parameter model, but you can use other versions.

Here is how you can find a specific Model ID:
1. Go to [HuggingFace Hub](https://huggingface.co/models?search=nllb).
2. Choose the NLLB version you want to use.
3. Click the copy icon next to the model name.

**Example Model IDs:**
- `facebook/nllb-200-distilled-600M` (Default and recommended for speed)
- `facebook/nllb-200-1.3B` (Higher quality, requires more resources)
