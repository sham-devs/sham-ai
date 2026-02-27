<?php

declare(strict_types=1);

return array (
  'messages' => 
  array (
    'test_connection' => 'Test Connection',
  ),
  'settings' => 
  array (
    'tab' => 
    array (
      'label' => 'AI Translation',
      'title' => 'AI Settings',
      'description' => 'Configure AI providers and options.',
    ),
    'field' => 
    array (
      'enabled' => 
      array (
        'label' => 'Enable AI',
        'desc' => 'Main toggle for all AI-powered functionality.',
      ),
      'provider' => 
      array (
        'label' => 'AI Provider',
        'desc' => 'Select the AI service provider.',
      ),
      'model' => 
      array (
        'label' => 'AI Model',
        'desc' => 'Specify the model to use (e.g., gpt-4o, claude-3-5-sonnet).',
      ),
      'api_key' => 
      array (
        'label' => 'API Key',
        'desc' => 'API key for the selected provider.',
      ),
      'temperature' => 
      array (
        'label' => 'Temperature',
        'desc' => 'Controls randomness: 0 is deterministic, 1 is creative.',
      ),
    ),
    'action' => 
    array (
      'save_section' => 'Save Settings',
      'reset_defaults' => 'Reset to Defaults',
      'confirm_reset' => 'Are you sure you want to reset all AI settings to their default values?',
    ),
  ),
);
