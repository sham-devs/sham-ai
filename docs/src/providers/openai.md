# OpenAI

Learn how to configure and use OpenAI models with Sham AI.

## Overview

OpenAI provides industry-leading AI models capable of complex reasoning, text and code generation, and image creation. Sham AI seamlessly integrates these models via the API.

## Configuration

To use OpenAI, you need to provide your API key in the AI Settings.
You can get your API key from the [OpenAI Platform Settings](https://platform.openai.com/api-keys).

Optionally, you can also provide your Organization ID and a custom Base URL if you are using a proxy or an OpenAI-compatible API.

## How to find the Model ID

When adding a custom OpenAI model, you need to use the exact **Model ID**.

Here is how you can find the correct Model ID:

1. Log in to your [OpenAI Platform Dashboard](https://platform.openai.com/).
2. Navigate to the **API Reference** or **Playground** section.
3. In the model selection dropdown, or in the documentation tables, look for the exact string used in API calls.
4. Alternatively, you can find a comprehensive list of all current models on the [OpenAI Models Documentation page](https://platform.openai.com/docs/models).

## Available Models / Examples

**Example Model IDs:**
- `gpt-4o` (Optimized for speed and capabilities, best overall)
- `gpt-4o-mini` (Fast, cost-effective small model for simpler tasks)
- `o1` (Advanced reasoning model for difficult problems)
- `o3-mini` (Fast reasoning model)
- `dall-e-3` (Image generation)
