# xAI (Grok)

Learn how to configure and use xAI's Grok models with Sham AI.

## Configuration

To use xAI, you need to provide your API key in the AI Settings.
You can get your API key from the [xAI Console](https://console.x.ai/).

## How to find the Model ID

When adding a custom xAI model, you need to use the exact **Model ID** (e.g., `grok-2-latest`).

Here is how you can find the correct Model ID:

1. Log in to the [xAI Console](https://console.x.ai/).
2. Navigate to the API Documentation section.
3. Look for the list of supported models in their REST API reference for the `/chat/completions` endpoint.
4. Use the exact string provided.

**Example Model IDs:**
- `grok-2-latest` (Latest stable Grok 2 model)
- `grok-2-vision-latest` (Vision capable model)
