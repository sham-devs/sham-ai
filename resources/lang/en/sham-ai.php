<?php

declare(strict_types=1);

return [
    'id' => 'ID',
    'messages' => [
        'test_connection' => 'Test Connection',
    ],
    'settings' => [
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
            'models' => [
                'label' => 'AI Models',
                'desc' => 'Manage multiple AI models and their capabilities.',
            ],
        ],
        'models' => [
            'label' => 'AI Models',
            'add' => 'Add Model',
            'edit' => 'Edit Model',
            'name' => 'Name',
            'enabled' => 'Enabled',
            'provider' => 'Provider',
            'model' => 'Model',
            'capabilities' => 'Capabilities',
            'configure_desc' => 'Configure your AI model settings and capabilities.',
            'empty_state' => 'No AI models configured yet.',
            'create' => 'Add Your First Model',
        ],
        'capabilities' => [
            'translation' => 'Translation',
            'content_generation' => 'Content Generation',
            'seo' => 'SEO Analysis',
        ],
        'capabilities_short' => [
            'translation' => 'TRL',
            'content_generation' => 'GEN',
            'seo' => 'SEO',
        ],
        'sections' => [
            'general' => [
                'title' => 'General Settings',
                'description' => 'Main AI configuration and status.',
            ],
            'models' => [
                'title' => 'AI Models Management',
                'description' => 'Add, edit, or remove AI models and their provider specific configurations.',
            ],
        ],
        'action' => [
            'save_section' => 'Save Settings',
            'reset_defaults' => 'Reset to Defaults',
            'confirm_reset' => 'Are you sure you want to reset all AI settings to their default values?',
        ],
    ],
];
