# HuggingFace Qwen

Learn how to configure and use Alibaba Cloud's Qwen models via HuggingFace with Sham AI.

## Overview

Qwen models are highly competitive, large language models that excel at multilingual generation, coding, and logical reasoning, offering versions scaling from mobile-friendly to massive enterprise deployments.

## Configuration

### 1. Get API Key from Hugging Face
1. Go to [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens).
2. Create an account if you don't have one.
3. Create a new **Access Token** with `read` permissions.

### 2. Enter Settings
In the Sham AI settings, select `huggingface-qwen` as the provider and enter your Hugging Face Access Token.

## How to find the Model ID
When adding a custom Qwen model, you need to use the exact **Model ID**.

Here is how you can find the correct Model ID:
1. Go to [HuggingFace Hub](https://huggingface.co/Qwen).
2. Look for the specific Qwen model you want to use. Make sure you select the `Instruct` or `Chat` version, not the base version.
3. Click the copy icon next to the model name at the top of the page. This is the exact string you need.

## Available Models / Examples

**Example Model IDs:**
- `Qwen/Qwen2.5-72B-Instruct` (Highly capable, large reasoning model)
- `Qwen/Qwen2.5-7B-Instruct` (Fast and efficient middle-tier model)
- `Qwen/Qwen2.5-Coder-32B-Instruct` (Advanced coding model)
