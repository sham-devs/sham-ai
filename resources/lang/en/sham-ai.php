<?php

declare(strict_types=1);

return [
    'id' => 'ID',
    'messages' => [
        'test_connection' => 'Test Connection',
    ],
    'settings' => [
        'tab' => [
            'label' => 'AI Setup',
            'title' => 'AI Settings',
            'description' => 'Configure AI providers and options.',
        ],
        'field' => [
            'provider' => [
                'label' => 'AI Provider',
                'desc' => 'Select the AI service provider.',
            ],
            'search_term' => [
                'label' => 'Search Models on API (Optional)',
                'placeholder' => 'e.g. llama, qwen, flux...',
                'desc' => 'Enter a keyword to search for specific models on Hugging Face during sync.',
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
            'capabilities_info' => 'Model Info & Capabilities',
            'base_url_desc' => 'Optional API endpoint. Leave empty for default.',
        ],
        'provider_instructions' => [
            'how_to_find' => 'How to find the Model ID',
            'example' => 'Example',
            'openai' => [
                'instructions' => 'Go to the Models page, select the model, and copy the "Model ID"',
                'notes' => 'Such as: gpt-5, gpt-4.1, gpt-4o, dall-e-3',
            ],
            'anthropic' => [
                'instructions' => 'Go to the Console, select the model, and copy the "Model ID"',
                'notes' => 'Such as: claude-3-7-sonnet-latest, claude-opus-4-5',
            ],
            'google' => [
                'instructions' => 'Go to AI Studio, copy the model name',
                'notes' => 'Such as: gemini-2.5-pro, gemini-2.5-flash',
            ],
            'huggingface-flux' => [
                'instructions' => 'Go to HuggingFace, search for FLUX, and copy the full "Model ID"',
                'notes' => 'schnell = fast, dev = higher quality',
            ],
            'huggingface-nllb' => [
                'instructions' => 'Copy the "Model ID" of the chosen model',
                'notes' => 'Such as: facebook/nllb-200-distilled-600M',
            ],
            'default' => [
                'instructions' => 'Enter the Model ID manually',
                'notes' => '',
            ],
        ],
        'capabilities' => [
            'text_generation' => 'Text Generation',
            'translation' => 'Translation',
            'seo' => 'SEO Analysis',
            'image_generation' => 'Image Generation',
        ],
        'capabilities_short' => [
            'text_generation' => 'GEN',
            'translation' => 'TRL',
            'seo' => 'SEO',
            'image_generation' => 'IMG',
        ],
        'capabilities_desc' => [
            'text_generation' => 'Content writing, summaries, and text generation',
            'translation' => 'Multi-language text translation',
            'seo' => 'SEO analysis, meta tags, and keyword suggestions',
            'image_generation' => 'AI-powered image creation from text prompts',
        ],
        'sections' => [
            'models' => [
                'title' => 'AI Models Management',
                'description' => 'Add, edit, or remove AI models and their provider specific configurations.',
            ],
        ],
        'action' => [
            'save_section' => 'Save Settings',
            'sync_models' => 'Sync Models from API',
            'reset_defaults' => 'Reset to Defaults',
            'confirm_reset' => 'Are you sure you want to reset all AI settings to their default values?',
        ],
        'messages' => [
            'no_translation_models' => 'AI not enabled - no translation models found.',
        ],
        'errors' => [
            'generic' => 'Technical error occurred during model execution.',
            'capability_mismatch' => "Model ':model' does not support ':capability'. It supports: [:supported].",
        ],
        'status' => [
            'payment_required' => 'Paid Model (Credits Required)',
            'gated' => 'Gated Model (Access/Login Required)',
            'usable' => 'Usable Model',
        ],
    ],
];
