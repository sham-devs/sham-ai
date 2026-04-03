#!/usr/bin/env php
<?php
/**
 * Generate providers.json for VitePress documentation from SupportedModels.php
 *
 * This script reads the canonical provider data from SupportedModels.php
 * and generates the JSON data file used by VitePress for sidebar navigation.
 *
 * Usage:
 *   php scripts/generate-providers-docs.php
 *
 * Run this script when:
 *   - Adding new providers to SupportedModels.php
 *   - Updating provider capabilities or example models
 *   - Before releasing documentation updates
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Sham\AI\Models\SupportedModels;

$providers = SupportedModels::getProviders();
$output = [];

// Sidebar label mappings (shorter versions for sidebar)
$sidebarLabels = [
    'openai' => 'OpenAI',
    'anthropic' => 'Anthropic',
    'google' => 'Google',
    'deepseek' => 'DeepSeek',
    'xai' => 'xAI',
    'mistral' => 'Mistral',
    'zhipu' => 'Zhipu',
    'ollama' => 'Ollama',
    'huggingface-nllb' => 'NLLB',
    'huggingface-opus-mt' => 'Opus-MT',
    'huggingface-llama' => 'Llama',
    'huggingface-qwen' => 'Qwen',
    'huggingface-mistral' => 'HF Mistral',
    'huggingface-flux' => 'FLUX',
    'huggingface-sd' => 'Stable Diffusion',
    'huggingface-sdxl' => 'SDXL',
];

// Example models for each provider (canonical examples from SupportedModels.php)
$exampleModels = [
    'openai' => 'gpt-4o',
    'anthropic' => 'claude-3-7-sonnet-latest',
    'google' => 'gemini-2.5-flash',
    'deepseek' => 'deepseek-chat',
    'xai' => 'grok-3-latest',
    'mistral' => 'mistral-large-latest',
    'zhipu' => 'glm-4-plus',
    'ollama' => 'llama3.2',
    'huggingface-nllb' => 'facebook/nllb-200-distilled-600M',
    'huggingface-opus-mt' => 'Helsinki-NLP/opus-mt-en-ar',
    'huggingface-llama' => 'meta-llama/Llama-3.2-3B-Instruct',
    'huggingface-qwen' => 'Qwen/Qwen2.5-72B-Instruct',
    'huggingface-mistral' => 'mistralai/Mistral-7B-Instruct-v0.3',
    'huggingface-flux' => 'black-forest-labs/FLUX.1-schnell',
    'huggingface-sd' => 'runwayml/stable-diffusion-v1-5',
    'huggingface-sdxl' => 'stabilityai/stable-diffusion-xl-base-1.0',
];

foreach ($providers as $providerId => $label) {
    $capabilities = SupportedModels::getProviderCapabilities($providerId);

    // Convert capabilities to strings
    $capStrings = array_map(
        fn ($cap) => $cap->value,
        $capabilities
    );

    $output[$providerId] = [
        'label' => $label,
        'sidebarLabel' => $sidebarLabels[$providerId] ?? $label,
        'capabilities' => $capStrings,
        'exampleModel' => $exampleModels[$providerId] ?? '',
    ];
}

$jsonPath = __DIR__ . '/../site/data/providers.json';
$jsonContent = json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

if (file_put_contents($jsonPath, $jsonContent . "\n") === false) {
    fwrite(STDERR, "Error: Failed to write to $jsonPath\n");
    exit(1);
}

echo "Generated providers.json with " . count($output) . " providers\n";
echo "File: $jsonPath\n";
