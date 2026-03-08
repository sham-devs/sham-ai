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

Sham AI supports the latest frontier OpenAI models.

### Frontier Models (High Intelligence)
| Model ID | Model Name | Capabilities |
| :--- | :--- | :--- |
| `gpt-5.4` | GPT-5.4 Thinking / Pro | Text, Translation, SEO |
| `gpt-5.4-mini` | GPT-5.4 Mini | Text, Translation, SEO |
| `gpt-5.1` | GPT-5.1 Personalized | Text, Translation, SEO |
| `gpt-5.1-mini` | GPT-5.1 Mini | Text, Translation |

### Legacy & Image Generation
| Model ID | Description | Capabilities |
| :--- | :--- | :--- |
| `gpt-4o` | Reliable legacy stable model. | Text, Translation, SEO |
| `gpt-image-1` | Next-gen image creation. | Image Generation |
| `dall-e-3` | Standard high-quality image generation. | Image Generation |

> [!CAUTION]
> **Reasoning Models (o1, o3, etc.)** are currently **not supported** in Sham AI as they utilize a different inference pattern (recursive thinking) not compatible with standard chat adapters.

> [!IMPORTANT]
> Always verify the model is enabled for your API key in the OpenAI Dashboard.
