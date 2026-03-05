# OpenAI

Learn how to configure and use OpenAI models with Sham AI.

## Configuration

To use OpenAI, you need to provide your API key in the AI Settings.
You can get your API key from the [OpenAI Platform](https://platform.openai.com/api-keys).

Optionally, you can also provide your Organization ID and a custom Base URL if you are using a proxy or an OpenAI-compatible API.

## How to find the Model ID

When adding a custom OpenAI model, you need to use the exact **Model ID** (e.g., `gpt-4o`, `o1-preview`, `dall-e-3`).

Here is how you can find the correct Model ID:

1. Log in to your [OpenAI Platform Dashboard](https://platform.openai.com/).
2. Navigate to the **Playground** or **API Reference** section.
3. In the model selection dropdown, or in the documentation tables, look for the exact string used in API calls.
4. Alternatively, you can find a comprehensive list of all current models on the [OpenAI Models Documentation page](https://platform.openai.com/docs/models).

**Example Model IDs:**
- `gpt-4o` (Optimized for speed and capabilities)
- `gpt-4o-mini` (Fast, cost-effective small model)
- `o1` (Reasoning model)
- `dall-e-3` (Image generation)
