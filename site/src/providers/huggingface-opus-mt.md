# HuggingFace Opus-MT

Configure Helsinki-NLP's Opus-MT translation models via HuggingFace.

## Overview

Opus-MT models are small, efficient language-pair specific translation models.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| API Key | Yes | HuggingFace token from [Settings](https://huggingface.co/settings/tokens) |
| Base URL | No | Default: `https://api-inference.huggingface.co/models` |

## Finding Model IDs

1. Go to [HuggingFace Hub - Helsinki-NLP](https://huggingface.co/Helsinki-NLP)
2. Search for your language pair (e.g., `opus-mt-en-ar`)
3. Copy the model name

## Example Model IDs

These are examples. Each model is language-pair specific.

| Model ID | Language Pair |
| :--- | :--- |
| `Helsinki-NLP/opus-mt-en-ar` | English → Arabic |
| `Helsinki-NLP/opus-mt-ar-en` | Arabic → English |
| `Helsinki-NLP/opus-mt-en-fr` | English → French |
| `Helsinki-NLP/opus-mt-fr-en` | French → English |

## Supported Capabilities

- Translation

## Notes

- Language-pair specific - configure the correct model for your source/target languages
- Very fast and efficient
- Free tier available
