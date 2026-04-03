# HuggingFace NLLB

Learn how to configure and use Meta's NLLB (No Language Left Behind) models for translation via HuggingFace with Sham AI.

## Overview

Meta's NLLB models are state-of-the-art multilingual machine translation models capable of translating directly between 200 languages.

## Configuration

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. Enter Settings
In the Sham AI settings, select `huggingface-nllb` as the provider and enter your Hugging Face Access Token.

## How to find the Model ID
By default, Sham AI uses the distilled 600M parameter model, but you can use other versions.

Here is how you can find a specific Model ID:
1. Go to [HuggingFace Hub](https://huggingface.co/models?search=nllb).
2. Choose the NLLB version you want to use.
3. Click the copy icon next to the model name.

## Available Models / Examples

**Example Model IDs:**
- `facebook/nllb-200-distilled-600M` (Default and recommended for speed/efficiency)
- `facebook/nllb-200-1.3B` (Higher quality, requires more resources)
- `facebook/nllb-200-3.3B` (Highest quality, heavily resource intensive)
