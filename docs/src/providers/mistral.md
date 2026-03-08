# Mistral AI

Learn how to configure and use Mistral models via their official API with Sham AI.

## Overview

Mistral AI provides powerful, efficient open-source and commercial models that are extremely fast and highly capable in reasoning and coding.

## Configuration

To use Mistral AI, you need to provide your API key in the AI Settings.
You can get your API key from the [La Plateforme (Mistral Console)](https://console.mistral.ai/api-keys/).

## How to find the Model ID

When adding a custom Mistral model, you need to use the exact **Model ID**.

Here is how you can find the correct Model ID:

1. Log in to [La Plateforme](https://console.mistral.ai/).
2. Navigate to the **Models** section or consult the [Mistral Docs](https://docs.mistral.ai/getting-started/models/).
3. The exact string to use is listed under the "Model name" in the documentation.

## Available Models / Examples

Sham AI supports the latest Frontier and optimized Mistral models.

| Model ID | Model Name | Capabilities |
| :--- | :--- | :--- |
| `mistral-large-latest` | Mistral Large 3 | Text, Translation, SEO |
| `mistral-medium-latest` | Mistral Medium 3.1 | Text, Translation, SEO |
| `mistral-small-latest` | Mistral Small 3.2 | Text, Translation |
| `ministral-8b-latest` | Ministral 8B | Text, Translation |
| `ministral-3b-latest` | Ministral 3B | Text, Translation |

> [!TIP]
> Use the `-latest` suffix to ensure you are always using the most recent iteration of a model family.
