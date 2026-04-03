# OpenAI

Configure OpenAI models in Sham AI.

## Overview

OpenAI provides GPT models for text generation, translation, and image creation.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| API Key | Yes | Get from [OpenAI Platform](https://platform.openai.com/api-keys) |
| Organization ID | No | For enterprise accounts |
| Base URL | No | Override for proxies or compatible APIs |

## Finding Model IDs

1. Log in to [OpenAI Platform](https://platform.openai.com/)
2. Go to **API Reference** or **Playground**
3. Check the model dropdown or [Models Documentation](https://platform.openai.com/docs/models)

## Example Model IDs

These are examples. Check OpenAI's documentation for current models.

| Model ID | Use Case |
| :--- | :--- |
| `gpt-4o` | General purpose |
| `gpt-4o-mini` | Fast, cost-effective |
| `gpt-4-turbo` | Legacy high-intelligence |
| `gpt-3.5-turbo` | Fast, economical |
| `gpt-image-1` | Image generation |
| `dall-e-3` | Image generation |

## Supported Capabilities

- Text Generation
- Translation
- SEO Analysis
- Image Generation

## Notes

- Some models require access requests in the OpenAI dashboard
- Check your usage limits in the OpenAI dashboard
- Reasoning models (o1, o3) are not supported - they use a different inference pattern
