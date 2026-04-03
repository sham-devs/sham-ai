# HuggingFace Qwen

Configure Alibaba's Qwen models via HuggingFace.

## Overview

Qwen models are large language models with strong multilingual and reasoning capabilities.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| API Key | Yes | HuggingFace token from [Settings](https://huggingface.co/settings/tokens) |
| Base URL | No | Default: `https://api-inference.huggingface.co/models` |

## Finding Model IDs

1. Go to [HuggingFace Hub - Qwen](https://huggingface.co/Qwen)
2. Select an Instruct variant
3. Copy the model name

## Example Model IDs

These are examples. Check HuggingFace for all available models.

| Model ID | Use Case |
| :--- | :--- |
| `Qwen/Qwen2.5-7B-Instruct` | Balanced |
| `Qwen/Qwen2.5-72B-Instruct` | Most capable |
| `Qwen/Qwen2.5-Coder-32B-Instruct` | Code generation |

## Supported Capabilities

- Text Generation
- Translation

## Notes

- Strong multilingual capabilities
- Use Instruct variants for chat/translation tasks
