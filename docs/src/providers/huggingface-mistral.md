# HuggingFace Mistral

Learn how to configure and use Mistral models via HuggingFace with Sham AI.

## Overview

Mistral AI produces highly efficient and powerful open-source models, including dense models and Mixture-of-Experts (MoE) architectures, excellent for general generation tasks.

## Configuration

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. Enter Settings
In the Sham AI settings, select `huggingface-mistral` as the provider and enter your Hugging Face Access Token.

## How to find the Model ID
When adding a custom Mistral model, you need to use the exact **Model ID**.

Here is how you can find the correct Model ID:
1. Go to [HuggingFace Hub](https://huggingface.co/mistralai).
2. Look for the specific Mistral model you want to use. Make sure you select the `Instruct` or `Chat` version, not the base version.
3. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

## Available Models / Examples

**Example Model IDs:**
- `mistralai/Mistral-Nemo-Instruct-2407` (Highly capable model co-developed with NVIDIA)
- `mistralai/Mistral-7B-Instruct-v0.3` (Fast and lightweight)
- `mistralai/Mixtral-8x7B-Instruct-v0.1` (Powerful Mixture-of-Experts model)
