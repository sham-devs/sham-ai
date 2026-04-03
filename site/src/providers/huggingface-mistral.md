# HuggingFace Mistral

Configure Mistral models via HuggingFace.

## Overview

Mistral models are efficient open-weight language models.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| API Key | Yes | HuggingFace token from [Settings](https://huggingface.co/settings/tokens) |
| Base URL | No | Default: `https://api-inference.huggingface.co/models` |

## Finding Model IDs

1. Go to [HuggingFace Hub - mistralai](https://huggingface.co/mistralai)
2. Select an Instruct variant
3. Copy the model name

## Example Model IDs

These are examples. Check HuggingFace for all available models.

| Model ID | Use Case |
| :--- | :--- |
| `mistralai/Mistral-7B-Instruct-v0.3` | Fast, efficient |
| `mistralai/Mistral-Nemo-Instruct-2407` | More capable |
| `mistralai/Mixtral-8x7B-Instruct-v0.1` | MoE architecture |

## Supported Capabilities

- Text Generation
- Translation

## Notes

- Use Instruct variants for chat/translation tasks
- Mixtral uses Mixture-of-Experts architecture
