# Ollama (Local)

Configure local Ollama models in Sham AI.

## Overview

Ollama runs models locally on your machine. No API key required.

## Configuration

| Field | Required | Description |
|-------|----------|-------------|
| Base URL | No | Default: `http://localhost:11434` |
| API Key | No | Not required for local models |

## Prerequisites

1. Install Ollama from [ollama.com](https://ollama.com)
2. Pull a model: `ollama pull llama3.2`
3. Verify Ollama is running: `ollama list`

## Finding Model IDs

1. Run `ollama list` to see installed models
2. Browse available models at [Ollama Library](https://ollama.com/library)
3. Pull new models: `ollama pull <model-name>`

## Example Model IDs

These are examples. Check Ollama library for all available models.

| Model ID | Use Case |
| :--- | :--- |
| `llama3.2` | General purpose |
| `llama3.1` | Larger Llama model |
| `mistral` | Mistral open model |
| `qwen2.5` | Qwen open model |
| `codellama` | Code generation |

## Supported Capabilities

- Text Generation
- Translation

## Notes

- No API costs - runs entirely locally
- Requires sufficient RAM for model size
- First pull downloads the model (several GB)
- Performance depends on your hardware
