# Google (Gemini)

Learn how to configure and use Google's Gemini models with Sham AI.

## Configuration

To use Google Gemini, you need to provide your API key in the AI Settings.
You can get your API key from [Google AI Studio](https://aistudio.google.com/app/apikey).

## How to find the Model ID

When adding a custom Google model, you need to use the exact **Model ID** (e.g., `gemini-2.5-flash`).

Here is how you can find the correct Model ID:

1. Log in to [Google AI Studio](https://aistudio.google.com/).
2. In the left navigation menu, click on **Models**.
3. You will see a list of all available models. Click on a model to see its details.
4. The exact string you need is listed under the **Model ID** field (e.g., `models/gemini-2.5-flash` or just `gemini-2.5-flash`).
5. You can also find a full list in the [Gemini API Documentation](https://ai.google.dev/models/gemini).

**Example Model IDs:**
- `gemini-2.5-flash` (Latest fast and versatile model)
- `gemini-2.5-pro` (High capability reasoning model)
- `gemini-2.0-flash-exp` (Experimental capabilities)
