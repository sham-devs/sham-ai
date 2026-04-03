# Mistral

Configure Mistral models in Sham AI.

## Overview

Mistral provides open-weight and commercial AI models.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| API Key | Yes | Get from [Mistral Platform](https://console.mistral.ai/api-keys/) |
| Base URL | No | Override for proxies |

## Finding Model IDs

1. Log in to [Mistral Platform](https://console.mistral.ai/)
2. Check available models in [Mistral Docs](https://docs.mistral.ai/getting-started/models/)
3. Use the "Model name" from the documentation

## Example Model IDs

These are examples. Check Mistral's documentation for current models.

| Model ID | Use Case |
| :--- | :--- |
| `mistral-large-latest` | Most capable |
| `mistral-medium-latest` | Balanced |
| `mistral-small-latest` | Fast, cost-effective |
| `codestral-latest` | Code generation |

## Supported Capabilities

- Text Generation
- Translation
- SEO Analysis

## Notes

- Using `-latest` suffix ensures you get the most recent model version
