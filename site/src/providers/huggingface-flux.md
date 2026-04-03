# HuggingFace FLUX

Configure Black Forest Labs' FLUX image generation models via HuggingFace.

## Overview

FLUX models are text-to-image models known for high-quality outputs and prompt adherence.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| API Key | Yes | HuggingFace token from [Settings](https://huggingface.co/settings/tokens) |
| Base URL | No | Default: `https://api-inference.huggingface.co/models` |

## Finding Model IDs

1. Go to [HuggingFace Hub - black-forest-labs](https://huggingface.co/black-forest-labs)
2. Select a FLUX model variant
3. Copy the model name

## Example Model IDs

These are examples. Check HuggingFace for all available models.

| Model ID | Use Case |
| :--- | :--- |
| `black-forest-labs/FLUX.1-schnell` | Fast generation (recommended) |
| `black-forest-labs/FLUX.1-dev` | Higher quality |

## Supported Capabilities

- Image Generation

## Notes

- `schnell` variant is optimized for speed
- `dev` variant may have stricter usage limits
