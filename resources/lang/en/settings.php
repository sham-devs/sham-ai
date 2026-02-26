<?php

return [
    'tab' => [
        'label' => 'AI Translation',
        'title' => 'AI Settings',
        'description' => 'Configure AI providers and options.',
    ],
    'field' => [
        'enabled' => [
            'label' => 'Enable AI',
            'desc' => 'Main toggle for all AI-powered functionality.',
        ],
        'provider' => [
            'label' => 'AI Provider',
            'desc' => 'Select the AI service provider.',
        ],
        'model' => [
            'label' => 'AI Model',
            'desc' => 'Specify the model to use (e.g., gpt-4o, claude-3-5-sonnet).',
        ],
        'api_key' => [
            'label' => 'API Key',
            'desc' => 'API key for the selected provider.',
        ],
        'temperature' => [
            'label' => 'Temperature',
            'desc' => 'Controls randomness: 0 is deterministic, 1 is creative.',
        ],
    ],
    'action' => [
        'save_section' => 'Save Settings',
        'reset_defaults' => 'Reset to Defaults',
        'confirm_reset' => 'Are you sure you want to reset all AI settings to their default values?',
    ],
];
