# HuggingFace SDXL

Configure Stable Diffusion XL image generation models via HuggingFace.

## Overview

SDXL (Stable Diffusion XL) provides higher resolution and detail than SD 1.5/2.1.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| API Key | Yes | HuggingFace token from [Settings](https://huggingface.co/settings/tokens) |
| Base URL | No | Default: `https://api-inference.huggingface.co/models` |

## Finding Model IDs

1. Go to [HuggingFace Hub - stabilityai](https://huggingface.co/stabilityai)
2. Select an SDXL model variant
3. Copy the model name

## Example Model IDs

These are examples. Check HuggingFace for all available models.

| Model ID | Use Case |
| :--- | :--- |
| `stabilityai/stable-diffusion-xl-base-1.0` | Base model |
| `stabilityai/stable-diffusion-xl-refiner-1.0` | Refinement pass |

## Supported Capabilities

- Image Generation

## Notes

- Higher resolution than SD 1.5/2.1
- Base + Refiner pipeline for best quality
