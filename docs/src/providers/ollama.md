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

**Example Model IDs:**
- `llama3.2` (Meta's highly capable local model)
- `qwen2.5:14b` (Strong alternative open-weight model)
- `mistral:instruct` (Mistral's fast local option)
