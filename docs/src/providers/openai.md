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

Sham AI supports most production-ready OpenAI models.

### Text Generation & Translation
| Model ID | Best Use Case | Capabilities |
| :--- | :--- | :--- |
| `gpt-5` | The latest frontier model for high-intelligence tasks. | Text, Translation, SEO |
| `gpt-4.1` | Reliable, versatile model for most complex workflows. | Text, Translation, SEO |
| `gpt-4o` | Multi-modal, optimized for speed and high-quality chat. | Text, Translation, SEO |
| `gpt-5-mini` | High efficiency and speed for developer tasks. | Text, Translation, SEO |
| `gpt-4.1-mini` | Lightweight and cost-effective for simple tasks. | Text, Translation |

### Image Generation
| Model ID | Description | Capabilities |
| :--- | :--- | :--- |
| `gpt-image-1` | The latest native image generation model. | Image Generation |
| `dall-e-3` | Standard high-quality artistic image generation. | Image Generation |

> [!WARNING]
> **Reasoning Models (o1, o3, etc.)** are currently **not supported** by Sham AI's default capabilities architecture as they are optimized for logical chains rather than content generation or translation.

> [!IMPORTANT]
> Always verify the model is enabled for your API key in the OpenAI Dashboard.
