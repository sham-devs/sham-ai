# HuggingFace Stable Diffusion

Learn how to configure and use Stable Diffusion image generation models via HuggingFace with Sham AI.

## Overview

Stable Diffusion models are highly popular open-weights image generation models, capable of generating photo-realistic images given a text prompt.

## Configuration

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. Enter Settings
In the Sham AI settings, select `huggingface-sd` as the provider and enter your Hugging Face Access Token.

## How to find the Model ID
When adding a custom Stable Diffusion model, you need to use the exact **Model ID**.

Here is how you can find the correct Model ID:
1. Go to [HuggingFace Hub](https://huggingface.co/models?search=stable-diffusion).
2. Look for the specific Stable Diffusion version you want to use (v1.5, v2.1, etc).
3. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

## Available Models / Examples

**Example Model IDs:**
- `runwayml/stable-diffusion-v1-5` (Classic lightweight SD model)
- `stabilityai/stable-diffusion-2-1` (Upgraded SD model)
