# Settings

## Overview

All AI settings are managed through Sham's settings infrastructure. The package provides `AISettingsProvider` which exposes settings fields for managing AI models.

## Settings Keys

| Key | Type | Description |
|-----|------|-------------|
| `sham-ai.models` | array | List of configured AI models |

## Model Configuration

Each model in `sham-ai.models` has the following fields:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `id` | string | Yes | Unique identifier |
| `name` | string | Yes | Display name |
| `provider` | string | Yes | Provider ID (openai, anthropic, gemini, zhipu, etc.) |
| `model` | string | Yes | Model name (gpt-4o, claude-3-5-sonnet, etc.) |
| `enabled` | boolean | No | Whether model is enabled (default: true) |
| `capabilities` | array | No | List of supported capabilities |
| `config` | object | No | Provider configuration |
| `options` | object | No | Model options |
| `priority` | int | No | Selection priority (default: 0) |

### Config Object

Provider-specific configuration:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `api_key` | string | Yes* | API key (encrypted in storage) |
| `base_url` | string | No | Custom API base URL |
| `organization` | string | No | Organization ID |

*Required for most cloud providers. Not needed for Ollama.

### Options Object

Model-specific options:

| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `temperature` | float | 0.3 | Sampling temperature |
| `max_tokens` | int | 2000 | Maximum tokens per request |
| `system_instruction` | string | null | Custom system prompt override |
| `preserve_placeholders` | bool | true | Preserve placeholders in translation |
| `preserve_html` | bool | true | Preserve HTML tags in translation |

## Encryption

Sensitive fields are automatically encrypted when saved and decrypted when loaded.

The encrypted fields are defined in `AISettingsProvider`:

```php
public function getEncryptedFields(): array
{
    return ['config.api_key'];
}
```

## Usage

```php
use Sham\AI\AIService;

$ai = app(AIService::class);

// Get all configured models
$models = $ai->getModels();

// Get only enabled models
$enabledModels = $ai->getEnabledModels();

// Get models supporting a specific capability
$translationModels = $ai->getModelsByCapability('translation');

// Check if AI is configured
if ($ai->isConfigured()) {
    // At least one model is enabled
}

// Add a new model
$model = $ai->addModel([
    'name' => 'GPT-4o',
    'provider' => 'openai',
    'model' => 'gpt-4o',
    'capabilities' => ['translation', 'content_generation', 'seo'],
    'config' => ['api_key' => 'sk-...'],
    'enabled' => true,
]);

// Update a model
$ai->updateModel($modelId, [
    'enabled' => false,
]);

// Delete a model
$ai->deleteModel($modelId);
```

## Settings UI

Models are configured through the Sham Settings UI. The package provides:

- Card views for model management
- Field definitions for the settings forms
- Encrypted field handling for API keys

Refer to the host application's settings documentation for how to access the settings UI.
