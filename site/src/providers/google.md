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

Sham AI supports the latest stable and preview Gemini models.

### Text Generation & Translation
| Model ID | Best Use Case | Capabilities |
| :--- | :--- | :--- |
| `gemini-3.1-pro` | Most capable model for complex reasoning. | Text, Translation, SEO |
| `gemini-3.1-flash-lite` | Ultra-fast and cost-effective. | Text, Translation, SEO |
| `gemini-3-flash` | Balanced speed and performance. | Text, Translation, SEO |
| `gemini-2.5-pro` | Reliable stable flagship. | Text, Translation, SEO |

### Image Generation
| Model ID | Description |
| :--- | :--- |
| `nano-banana-2` | Near-instantaneous high-fidelity images. |
| `imagen-3.0-generate-002` | Standard high-quality Imagen 3 model. |

> [!NOTE]
> Gemini 3.1 models provide significant advancements in abstract reasoning and token output capacity.
