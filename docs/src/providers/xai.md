# xAI (Grok)

Learn how to configure and use xAI's Grok models with Sham AI.

## Overview

xAI provides the Grok family of models, which are engineered for high-end reasoning and coding, often featuring uniquely up-to-date real-world knowledge capabilities when combined with custom data.

## Configuration

To use xAI, you need to provide your API key in the AI Settings.
You can get your API key from the [xAI Console](https://console.x.ai/).

## How to find the Model ID

When adding a custom xAI model, you need to use the exact **Model ID**.

Here is how you can find the correct Model ID:

1. Log in to the [xAI Console](https://console.x.ai/).
2. Navigate to the API Documentation section.
3. Look for the list of supported models in their REST API reference for the `/chat/completions` endpoint.
4. Use the exact string provided.

## Available Models / Examples

**Example Model IDs:**
- `grok-2-latest` (Latest stable Grok 2 model)
- `grok-2-vision-latest` (Vision capable model)
