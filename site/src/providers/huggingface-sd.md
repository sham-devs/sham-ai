# HuggingFace Stable Diffusion

Configure Stable Diffusion image generation models via HuggingFace.

## Overview

Stable Diffusion models are open-weight image generation models.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| API Key | Yes | HuggingFace token from [Settings](https://huggingface.co/settings/tokens) |
| Base URL | No | Default: `https://api-inference.huggingface.co/models` |

## Finding Model IDs

1. Go to [HuggingFace Hub](https://huggingface.co/models?search=stable-diffusion)
2. Select a Stable Diffusion variant
3. Copy the model name

## Example Model IDs

These are examples. Check HuggingFace for all available models.

| Model ID | Use Case |
| :--- | :--- |
| `runwayml/stable-diffusion-v1-5` | Classic SD 1.5 |
| `stabilityai/stable-diffusion-2-1` | SD 2.1 |

## Supported Capabilities

- Image Generation

## Notes

- SD 1.5 is widely compatible with community fine-tunes
- SD 2.1 offers improved quality
