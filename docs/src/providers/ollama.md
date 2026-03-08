# Ollama (Local AI)

Learn how to configure and use locally hosted models via Ollama with Sham AI.

## Overview

Ollama allows you to get up and running with large language models locally. This ensures absolute privacy and zero API costs as models run on your own hardware.

## Configuration

To use Ollama, you do not need an API key. Instead, you need to make sure Ollama is running and accessible.
If your Ollama instance is not on the same server, you must provide its **Base URL** in the provider configuration settings (e.g., `http://192.168.1.100:11434/v1`).

## How to find the Model ID

When adding a custom Ollama model, you need to use the exact **Model ID** that you have pulled locally.

Here is how you can find the correct Model ID:

1. Open your terminal on the machine running Ollama.
2. Run the command `ollama list`.
3. The exact string you need is the name of the model in the output (e.g., `llama3.2:latest` or `mistral`).
4. You can also browse available models to pull from the [Ollama Library](https://ollama.com/library).

## Available Models / Examples

Sham AI supports a wide range of models available via Ollama.

| Model ID | Base Model | Capabilities |
| :--- | :--- | :--- |
| `llama4:maverick` | Llama 4 (Maverick variant) | Text, Translation |
| `llama4:scout` | Llama 4 (Scout variant) | Text, Translation |
| `llama3.3` | Llama 3.3 (Stable) | Text, Translation |
| `qwen3.5` | Qwen 3.5 (Latest bilingual) | Text, Translation, SEO |
| `qwen3` | Qwen 3 | Text, Translation, SEO |
| `gemma3` | Google's Gemma 3 | Text, Translation |
| `phi4` | Microsoft's Phi-4 | Text, Translation |

> [!IMPORTANT]
> Ensure you have enough VRAM/RAM for the model you choose. Models with higher parameter counts (e.g., Qwen 3.5 72B) require significant resources.
