<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | The default AI provider to use for translations.
    | Supported providers: 'prism'
    |
    */
    'default_provider' => env('AI_PROVIDER', 'prism'),

    /*
    |--------------------------------------------------------------------------
    | Provider Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for each AI provider.
    |
    */
    'providers' => [
        'prism' => [
            /*
            |--------------------------------------------------------------------------
            | Prism Provider
            |--------------------------------------------------------------------------
            |
            | Configuration for Prism AI provider.
            |
            */
            'provider' => env('PRISM_PROVIDER', 'openai'),
            'model' => env('PRISM_MODEL', 'gpt-4'),
            'api_key' => env('OPENAI_API_KEY'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for AI translations.
    |
    */
    'translation' => [
        'batch_size' => env('AI_TRANSLATION_BATCH_SIZE', 10),
        'temperature' => env('AI_TRANSLATION_TEMPERATURE', 0.3),
        'max_tokens' => env('AI_TRANSLATION_MAX_TOKENS', 2000),
        'preserve_placeholders' => env('AI_TRANSLATION_PRESERVE_PLACEHOLDERS', true),
        'preserve_html' => env('AI_TRANSLATION_PRESERVE_HTML', true),
    ],
];
