# HuggingFace NLLB

Configure Meta's NLLB translation models via HuggingFace.

## Overview

Meta's NLLB (No Language Left Behind) models provide multilingual translation between 200 languages.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| API Key | Yes | HuggingFace token from [Settings](https://huggingface.co/settings/tokens) |
| Base URL | No | Default: `https://api-inference.huggingface.co/models` |

## Finding Model IDs

1. Go to [HuggingFace Hub](https://huggingface.co/models?search=nllb)
2. Select an NLLB model variant
3. Copy the model name (e.g., `facebook/nllb-200-distilled-600M`)

## Example Model IDs

These are examples. Check HuggingFace for all available models.

| Model ID | Use Case |
| :--- | :--- |
| `facebook/nllb-200-distilled-600M` | Fast, efficient (recommended) |
| `facebook/nllb-200-1.3B` | Higher quality |
| `facebook/nllb-200-3.3B` | Best quality, resource intensive |

## Supported Capabilities

- Translation

## Notes

- Supports 200 languages
- Free tier available with rate limits
