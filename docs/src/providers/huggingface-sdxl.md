# HuggingFace SDXL

Learn how to configure and use Stable Diffusion XL image generation models via HuggingFace with Sham AI.

## Overview

SDXL (Stable Diffusion XL) offers significantly higher resolution, detail, and prompt adherence compared to earlier Stable Diffusion models, while remaining fully open-weights.

## Configuration

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. Enter Settings
In the Sham AI settings, select `huggingface-sdxl` as the provider and enter your Hugging Face Access Token.

## How to find the Model ID
When adding a custom SDXL model, you need to use the exact **Model ID**.

Here is how you can find the correct Model ID:
1. Go to [HuggingFace Hub](https://huggingface.co/stabilityai).
2. Look for the specific SDXL model variant you want to use.
3. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

## Available Models / Examples

**Example Model IDs:**
- `stabilityai/stable-diffusion-xl-base-1.0` (Standard full SDXL model)
- `stabilityai/stable-diffusion-xl-refiner-1.0` (Used to refine base outputs)
