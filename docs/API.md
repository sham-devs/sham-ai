# Sham AI - API Documentation

## Table of Contents

1. [AIService](#aiservice)
2. [Providers](#providers)
3. [Prompts](#prompts)
4. [Responses](#responses)
5. [Configuration](#configuration)

---

## AIService

### Methods

#### `send()`

Send a prompt to the AI provider.

```php
public function send(PromptInterface $prompt): AIResponseInterface
```

**Parameters:**
- `$prompt` - Instance of `PromptInterface`

**Returns:** `AIResponseInterface`

**Throws:** `\RuntimeException` if provider fails

**Example:**
```php
$prompt = new TranslationPrompt(['Hello'], 'en', 'ar');
$response = $ai->send($prompt);

if ($response->isSuccessful()) {
    echo $response->getText();
}
```

---

#### `translate()`

Translate texts from one locale to another.

```php
public function translate(
    array $texts,
    string $from,
    string $to,
    array $options = []
): array
```

**Parameters:**
- `$texts` - Array of strings to translate
- `$from` - Source locale code (e.g., 'en')
- `$to` - Target locale code (e.g., 'ar')
- `$options` - Optional settings:
  - `temperature` (float, default 0.3)
  - `max_tokens` (int, default 2000)

**Returns:** Array of translated strings

**Throws:**
- `\RuntimeException` if AI is disabled
- `\RuntimeException` if translation fails

**Example:**
```php
$translations = $ai->translate(
    texts: ['Hello World', 'Goodbye'],
    from: 'en',
    to: 'ar',
    options: ['temperature' => 0.5]
);

// ['مرحباً بالعالم', 'وداعاً']
```

---

#### `isConfigured()`

Check if AI is properly configured and enabled.

```php
public function isConfigured(): bool
```

**Returns:** `true` if AI is enabled and provider is configured

**Example:**
```php
if ($ai->isConfigured()) {
    // AI features are available
}
```

---

#### `getProvider()`

Get the current AI provider instance.

```php
public function getProvider(): AIProviderInterface
```

**Returns:** Instance of `AIProviderInterface`

**Example:**
```php
$provider = $ai->getProvider();
echo $provider->getName(); // 'prism'
```

---

## Providers

### AIProviderInterface

All AI providers must implement this interface.

```php
interface AIProviderInterface
{
    public function send(PromptInterface $prompt): AIResponseInterface;
    public function isConfigured(): bool;
    public function getName(): string;
    public function configure(array $config): void;
}
```

### PrismProvider

Built-in provider using [prism-php/prism](https://prismphp.com/).

**Configuration Options:**

| Key | Type | Description |
|-----|------|-------------|
| `provider` | string | Prism provider (openai, anthropic, etc.) |
| `model` | string | Model name |
| `api_key` | string | API key |

**Example:**
```php
$provider = new PrismProvider();
$provider->configure([
    'provider' => 'openai',
    'model' => 'gpt-4o',
    'api_key' => env('OPENAI_API_KEY'),
]);
```

---

## Prompts

### PromptInterface

```php
interface PromptInterface
{
    public function getSystemPrompt(): string;
    public function getUserPrompt(): string;
    public function getOptions(): array;
}
```

### TranslationPrompt

Pre-built prompt for text translations.

**Constructor:**
```php
public function __construct(
    protected array $texts,
    protected string $from,
    protected string $to,
    protected array $options = []
)
```

**Options:**
- `temperature` (float, default 0.3)
- `max_tokens` (int, default 2000)
- `system_instruction` (string) - Custom system prompt

**Example:**
```php
$prompt = new TranslationPrompt(
    texts: ['Welcome', 'Login'],
    from: 'en',
    to: 'ar',
    options: [
        'temperature' => 0.2,
        'system_instruction' => 'Use formal Arabic language.'
    ]
);
```

### FileTranslationPrompt

For translating translation files (JSON/PHP arrays).

**Constructor:**
```php
public function __construct(
    protected array $texts,
    protected string $from,
    protected string $to,
    protected array $options = []
)
```

**Options:**
- `preserve_html` (bool) - Preserve HTML tags
- `preserve_placeholders` (bool) - Keep placeholders

---

## Responses

### AIResponseInterface

```php
interface AIResponseInterface
{
    public function getText(): string;
    public function isSuccessful(): bool;
    public function getUsage(): array;
    public function getError(): ?string;
}
```

### PrismResponse

Implementation for Prism responses.

**Methods:**

| Method | Returns | Description |
|--------|---------|-------------|
| `getText()` | `string` | Response text |
| `isSuccessful()` | `bool` | Success status |
| `getUsage()` | `array` | Token usage |
| `getError()` | `string|null` | Error message |

---

## Configuration

### Settings

Settings are managed via Sham Settings system.

| Key | Type | Default |
|-----|------|---------|
| `ai.enabled` | bool | `false` |
| `ai.default_provider` | string | `'prism'` |
| `ai.provider` | string | `'openai'` |
| `ai.model` | string | `'gpt-4o'` |
| `ai.api_key` | string | (none) |
| `ai.temperature` | float | `0.3` |
| `ai.max_tokens` | int | `2000` |

### Environment Variables

```bash
# AI Settings
AI_ENABLED=true
AI_PROVIDER=prism
AI_PRISM_PROVIDER=openai
AI_PRISM_MODEL=gpt-4o
AI_PRISM_API_KEY=sk-...
AI_TEMPERATURE=0.3
AI_MAX_TOKENS=2000
```

---

## Type Definitions

### AIPromptBuilderInterface

Interface for custom prompt builders (added for Sham Translation integration).

```php
interface AIPromptBuilderInterface
{
    public function buildTranslationPrompt(
        array $texts,
        string $from,
        string $to,
        array $context = []
    ): PromptInterface;
}
```

**Context Keys:**
- `model` - The Eloquent model being translated
- `attributes` - The attributes being translated
- `context` - Additional context from TranslationPrompt
