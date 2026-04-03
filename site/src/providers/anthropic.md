# Anthropic

Configure Anthropic Claude models in Sham AI.

## Overview

Anthropic provides Claude models for text generation and analysis.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| API Key | Yes | Get from [Anthropic Console](https://console.anthropic.com/settings/keys) |
| Base URL | No | Override for proxies |

## Finding Model IDs

1. Log in to [Anthropic Console](https://console.anthropic.com/)
2. Check the **Workbench** or [Models Documentation](https://docs.anthropic.com/en/docs/about-claude/models)
3. Use the "API model name" from the documentation

## Example Model IDs

These are examples. Check Anthropic's documentation for current models.

| Model ID | Use Case |
| :--- | :--- |
| `claude-3-7-sonnet-latest` | Balanced performance |
| `claude-3-5-sonnet-latest` | Fast, capable |
| `claude-3-5-haiku-latest` | Fast responses |
| `claude-3-opus-latest` | Most capable |

## Supported Capabilities

- Text Generation
- Translation
- SEO Analysis

## Notes

- Using `-latest` suffix is recommended for automatic updates
- Anthropic updates models frequently
