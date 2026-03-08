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

Sham AI supports the latest Grok models from xAI.

| Model ID | Description | Capabilities |
| :--- | :--- | :--- |
| `grok-4` | The most intelligent Grok model for complex tasks. | Text, Translation, SEO |
| `grok-4-1-fast` | High-speed variant of Grok 4. | Text, Translation, SEO |
| `grok-3` | Reliable and versatile reasoning-capable model. | Text, Translation, SEO |
| `grok-3-mini` | Optimized for speed and lightweight tasks. | Text, Translation |

> [!WARNING]
> Ensure you have sufficient credits in your xAI account, as Grok 4 models are billed at a higher rate.
