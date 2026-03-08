# DeepSeek

Learn how to configure and use DeepSeek models with Sham AI.

## Overview

DeepSeek provides powerful open-source and API-accessible models that are highly efficient, including state-of-the-art reasoning models (DeepSeek-R1) and general chat models (DeepSeek-V3).

## Configuration

To use DeepSeek, you need to provide your API key in the AI Settings.
You can get your API key from the [DeepSeek Platform](https://platform.deepseek.com/api_keys).

## How to find the Model ID

When adding a custom DeepSeek model, you need to use the exact **Model ID**.

Here is how you can find the correct Model ID:

1. Log in to the [DeepSeek Platform](https://platform.deepseek.com/).
2. Navigate to the API documentation section.
3. Look for the list of supported models in their API reference.
4. The exact string to use is the one provided in their code examples for the `model` parameter.

## Available Models / Examples

**Example Model IDs:**
- `deepseek-chat` (DeepSeek-V3, optimized for general conversation and coding)
- `deepseek-reasoner` (DeepSeek-R1, optimized for complex reasoning, math, and logic)
