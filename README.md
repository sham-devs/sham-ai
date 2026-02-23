# Sham AI Package

**Version:** 1.0.0
**Author:** Sham
**License:** MIT

## Overview

Sham AI is a PHP package providing a clean abstraction layer for AI/LLM integration in Laravel applications. It supports multiple AI providers through a unified interface, with built-in support for [prism-php/prism](https://prismphp.com/).

## Features

- **Provider Abstraction** - Switch between AI providers without changing code
- **Translation Support** - Built-in text translation capabilities
- **Prompt System** - Reusable prompt classes with system/user instructions
- **Settings Integration** - Uses Sham Settings for configuration
- **Type Safety** - Full PHP 8.4+ type hints and strict types

## Installation

```bash
composer require sham/ai
```

## Quick Start

```php
use Sham\AI\AIService;

$ai = app(AIService::class);

// Check if configured
if ($ai->isConfigured()) {
    // Translate texts
    $translations = $ai->translate(
        texts: ['Hello', 'Welcome'],
        from: 'en',
        to: 'ar'
    );

    // ['مرحباً', 'أهلاً وسهلاً']
}
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=sham-ai-config
```

**config/ai.php:**

```php
return [
    'enabled' => env('AI_ENABLED', false),
    'default_provider' => env('AI_PROVIDER', 'prism'),
    'providers' => [
        'prism' => [
            'provider' => env('AI_PRISM_PROVIDER', 'openai'),
            'model' => env('AI_PRISM_MODEL', 'gpt-4o'),
            'api_key' => env('AI_PRISM_API_KEY'),
        ],
    ],
    'translation' => [
        'temperature' => env('AI_TEMPERATURE', 0.3),
        'max_tokens' => env('AI_MAX_TOKENS', 2000),
    ],
];
```

## Core Classes

### AIService

The main service facade for all AI operations.

| Method | Parameters | Returns |
|--------|------------|---------|
| `send()` | `PromptInterface $prompt` | `AIResponseInterface` |
| `translate()` | `array $texts, string $from, string $to, array $options` | `array` |
| `isConfigured()` | - | `bool` |
| `getProvider()` | - | `AIProviderInterface` |

### AIProviderInterface

Contract for AI providers.

```php
interface AIProviderInterface
{
    public function send(PromptInterface $prompt): AIResponseInterface;
    public function isConfigured(): bool;
    public function getName(): string;
    public function configure(array $config): void;
}
```

**Built-in providers:**
- `PrismProvider` - Uses prism-php/prism

### PromptInterface

Contract for AI prompts.

```php
interface PromptInterface
{
    public function getSystemPrompt(): string;
    public function getUserPrompt(): string;
    public function getOptions(): array;
}
```

### AIResponseInterface

Contract for AI responses.

```php
interface AIResponseInterface
{
    public function getText(): string;
    public function isSuccessful(): bool;
    public function getUsage(): array;
    public function getError(): ?string;
}
```

### AIPromptBuilderInterface

Interface for building custom prompts from translation context.

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

## Prompts

### TranslationPrompt

Pre-built prompt for text translations.

```php
use Sham\AI\Prompts\TranslationPrompt;

$prompt = new TranslationPrompt(
    texts: ['Hello', 'Welcome'],
    from: 'en',
    to: 'ar',
    options: [
        'temperature' => 0.3,
        'max_tokens' => 2000,
        'system_instruction' => 'You are a professional translator.',
    ]
);

$ai->send($prompt);
```

**Features:**
- Preserves placeholders: `:name`, `{{variable}}`, `%s`
- Respects HTML tags and markdown
- Returns numbered list format

### FileTranslationPrompt

For translating translation files (JSON/PHP arrays).

```php
use Sham\AI\Prompts\FileTranslationPrompt;

$prompt = new FileTranslationPrompt(
    texts: ['title' => 'Home', 'body' => 'Welcome'],
    from: 'en',
    to: 'ar',
    options: ['preserve_html' => true]
);
```

## Settings

All AI settings are managed through Sham Settings:

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `ai.enabled` | bool | `false` | Enable AI features |
| `ai.default_provider` | string | `'prism'` | Provider to use |
| `ai.provider` | string | `'openai'` | Prism provider |
| `ai.model` | string | `'gpt-4o'` | AI model |
| `ai.api_key` | string | - | API key |
| `ai.temperature` | float | `0.3` | Temperature |
| `ai.max_tokens` | int | `2000` | Max tokens |

## Usage Examples

### Custom Provider

Implement the `AIProviderInterface`:

```php
use Sham\AI\Contracts\AIProviderInterface;
use Sham\AI\Contracts\{PromptInterface, AIResponseInterface};

class CustomProvider implements AIProviderInterface
{
    public function send(PromptInterface $prompt): AIResponseInterface
    {
        // Your implementation
    }

    public function isConfigured(): bool
    {
        return !empty(config('ai.custom.api_key'));
    }

    public function getName(): string
    {
        return 'custom';
    }

    public function configure(array $config): void
    {
        // Configuration
    }
}
```

Register in config:

```php
'default_provider' => 'custom',

'providers' => [
    'custom' => [
        // Custom config
    ],
],
```

### Custom Prompt

Create a class implementing `PromptInterface`:

```php
use Sham\AI\Contracts\PromptInterface;

class SummarizationPrompt implements PromptInterface
{
    public function __construct(
        private string $text,
        private int $maxLength = 100
    ) {}

    public function getSystemPrompt(): string
    {
        return 'You are a text summarizer. Keep summaries concise.';
    }

    public function getUserPrompt(): string
    {
        return "Summarize this text in {$this->maxLength} chars or less:\n\n{$this->text}";
    }

    public function getOptions(): array
    {
        return ['temperature' => 0.5, 'max_tokens' => 500];
    }
}
```

## File Structure

```
src/
├── AIService.php                  # Main service
├── AIServiceProvider.php          # Laravel service provider
├── AIPackage.php                  # Package helper
├── Contracts/
│   ├── AIProviderInterface.php
│   ├── AIResponseInterface.php
│   ├── PromptInterface.php
│   └── AIPromptBuilderInterface.php
├── Providers/
│   └── PrismProvider.php
├── Prompts/
│   ├── TranslationPrompt.php
│   └── FileTranslationPrompt.php
├── Responses/
│   └── PrismResponse.php
├── Database/Seeders/
│   └── AISettingsSeeder.php
├── Settings/
│   └── AISettingsProvider.php
└── resources/lang/
    └── {en,ar}/
        ├── ai.php
        └── settings.php
```

## License

MIT
