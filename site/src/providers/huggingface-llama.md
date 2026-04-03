# HuggingFace Llama

Configure Meta's Llama models via HuggingFace.

## Overview

Meta's Llama models are open-weight language models for text generation.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| API Key | Yes | HuggingFace token from [Settings](https://huggingface.co/settings/tokens) |
| Base URL | No | Default: `https://api-inference.huggingface.co/models` |

## Finding Model IDs

1. Go to [HuggingFace Hub - meta-llama](https://huggingface.co/meta-llama)
2. Select an Instruct or Chat variant
3. Copy the model name

## Example Model IDs

These are examples. Check HuggingFace for all available models.

| Model ID | Use Case |
| :--- | :--- |
| `meta-llama/Llama-3.2-3B-Instruct` | Small, fast |
| `meta-llama/Llama-3.1-8B-Instruct` | Balanced |
| `meta-llama/Llama-3.3-70B-Instruct` | Most capable |

## Supported Capabilities

- Text Generation
- Translation

## Notes

- Some Llama models require access request approval
- Use Instruct variants for chat/translation tasks
