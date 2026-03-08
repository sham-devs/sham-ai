# Google (Gemini)

Learn how to configure and use Google's Gemini models with Sham AI.

## Overview

Google's Gemini models are highly capable multimodal AI models designed for high-performance reasoning, massive context windows, and tight integration with Google's ecosystem.

## Configuration

To use Google Gemini, you need to provide your API key in the AI Settings.
You can get your API key from [Google AI Studio](https://aistudio.google.com/app/apikey).

## How to find the Model ID

When adding a custom Google model, you need to use the exact **Model ID**.

Here is how you can find the correct Model ID:

1. Log in to [Google AI Studio](https://aistudio.google.com/).
2. In the left navigation menu, click on **Models** or **Get API key** page.
3. The exact string you need is usually listed under the **Model version** or from the documentation (e.g., `gemini-2.5-flash`).
4. You can also find a full list in the [Gemini API Documentation](https://ai.google.dev/models/gemini).

## Available Models / Examples

Sham AI supports both stable and preview Gemini models.

### Text Generation & Translation
| Model ID | Best Use Case | Capabilities |
| :--- | :--- | :--- |
| `gemini-2.5-pro` | High capability for complex reasoning and long context. | Text, Translation, SEO |
| `gemini-2.5-flash` | Optimized for speed and cost-effectiveness. | Text, Translation, SEO |
| `gemini-3.1-pro-preview` | The newest preview model with enhanced intelligence. | Text, Translation, SEO |
| `gemini-3-flash-preview` | Ultra-low latency preview model. | Text, Translation |

### Image Generation
| Model ID | Description |
| :--- | :--- |
| `imagen-3.0-generate-002` | Standard high-quality Imagen 3 model. |

> [!NOTE]
> Gemini 3.x models are currently in **preview**. Stable production workloads should prefer the 2.5 series.
