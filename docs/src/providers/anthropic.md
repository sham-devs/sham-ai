# Anthropic (Claude)

Learn how to configure and use Anthropic's Claude models with Sham AI.

## Overview

Anthropic's Claude models excel at advanced reasoning, coding, and careful analysis, prioritizing safety and steerability.

## Configuration

To use Anthropic, you need to provide your API key in the AI Settings.
You can get your API key from the [Anthropic Console](https://console.anthropic.com/settings/keys).

## How to find the Model ID

When adding a custom Anthropic model, you need to use the exact **Model ID**.

Here is how you can find the correct Model ID:

1. Log in to the [Anthropic Console](https://console.anthropic.com/).
2. Navigate to the **Workbench** to test models or go to the **Settings > Plans & billing** section to see your accessible models.
3. The most reliable place to find the exact API model names is the [Anthropic Models Documentation page](https://docs.anthropic.com/en/docs/about-claude/models).
4. Look for the "Model name" or "API string" column in their documentation tables (e.g., under the `Claude 3.5 Sonnet` or `Claude 3.5 Haiku` sections).

## Available Models / Examples

Sham AI supports the latest Anthropic Claude models.

### Primary Models
| Model ID | Description | Capabilities |
| :--- | :--- | :--- |
| `claude-opus-4-5` | The most powerful model for complex analysis. | Text, Translation, SEO |
| `claude-sonnet-4-5` | High intelligence with massive speed. | Text, Translation, SEO |
| `claude-3-7-sonnet-latest` | Latest reliable frontier model. | Text, Translation, SEO |
| `claude-haiku-4-5` | Ultra-fast, responsive high-intelligence model. | Text, Translation |

> [!IMPORTANT]
> Anthropic updates their models frequently. Using the `-latest` suffix is recommended for most use cases where consistency is less important than having the newest features.
