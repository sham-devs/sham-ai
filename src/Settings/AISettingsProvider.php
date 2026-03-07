<?php

declare(strict_types=1);

namespace Sham\AI\Settings;

use App\Support\Settings\Concerns\HasSettingsActions;
use App\Support\Settings\DefinesActionsInterface;
use App\Support\Settings\HasSettingsStructure;
use Sham\AI\AIService;
use Sham\AI\Settings\Concerns\AISettingsCards;
use Sham\AI\Settings\Concerns\AISettingsFields;

/**
 * AI Settings Provider
 *
 * Field definitions and cards sections are in separate concern files.
 * Implements DefinesActionsInterface for flexible action handling.
 */
class AISettingsProvider extends \App\Support\Settings\BaseSettingsProvider implements DefinesActionsInterface, HasSettingsStructure
{
    use AISettingsCards;
    use AISettingsFields;
    use HasSettingsActions;

    public function __construct(
        protected AIService $aiService
    ) {}

    public function getId(): string
    {
        return 'sham-ai';
    }

    public function getTabDefinition(): array
    {
        $pkg = $this->transPrefix();

        return [
            'key' => $this->getId(),
            'label' => $pkg.'settings.tab.label',
            'title' => $pkg.'settings.tab.title',
            'description' => $pkg.'settings.tab.description',
            'icon' => 'ic:outline-auto-awesome',
            'order' => 5,
            'permission' => 'manage settings',
        ];
    }

    public function getStructure(): array
    {
        $id = $this->getId();

        return [
            'sections' => [
                array_merge([
                    'key' => 'models',
                    'icon' => 'ic:outline-smart-toy',
                    'settings_keys' => [$id.'.models'],
                    'hide_save_button' => true,
                ], $this->getTranslationAttributes('settings.sections.models.title', 'title'),
                    $this->getTranslationAttributes('settings.sections.models.description', 'description')),
            ],
        ];
    }

    /**
     * Get metadata for frontend rendering.
     *
     * Extends parent to include action definitions.
     */
    public function getMetadata(): array
    {
        $pkg = $this->transPrefix();
        $id = $this->getId();

        $metadata = array_merge([
            'pattern' => 'basic',
        ], $this->getStructure());

        // Add action definitions
        $metadata['actions'] = $this->getActionDefinitionsForMetadata();

        // Add supported providers and models for the UI
        $metadata['available_providers'] = collect(\Sham\AI\Models\SupportedModels::getProviders())->map(fn ($label, $value) => ['value' => $value, 'label' => $label])->values()->toArray();

        // Load model configuration instructions for each provider
        $metadata['provider_model_info'] = collect(\Sham\AI\Models\SupportedModels::getProviders())->mapWithKeys(function ($label, $provider) {
            return [$provider => \Sham\AI\Models\SupportedModels::getProviderModelInfo($provider)];
        })->toArray();

        // Add provider capabilities for the UI (read-only info)
        $metadata['provider_capabilities'] = collect(\Sham\AI\Models\SupportedModels::getProviders())->mapWithKeys(function ($label, $provider) {
            return [$provider => \Sham\AI\Models\SupportedModels::getProviderCapabilities($provider)];
        })->toArray();

        // Add capability labels and descriptions for the UI
        $metadata['capability_info'] = collect(\Sham\AI\Enums\Capability::cases())->mapWithKeys(fn ($c) => [
            $c->value => [
                'label' => $c->getLabel(),
                'description' => $c->getDescription(),
            ],
        ])->toArray();

        $metadata['collection_schemas'] = [
            $id.'.models' => [
                'fields' => [
                    [
                        'key' => 'name',
                        'type' => 'string',
                        'input_type' => 'text',
                        'label' => $pkg.'settings.models.name',
                        'required' => true,
                        'default' => 'New Model',
                    ],
                    [
                        'key' => 'enabled',
                        'type' => 'boolean',
                        'input_type' => 'toggle',
                        'label' => $pkg.'settings.models.enabled',
                        'default' => true,
                    ],
                    [
                        'key' => 'provider',
                        'type' => 'string',
                        'input_type' => 'select',
                        'label' => $pkg.'settings.models.provider',
                        'required' => true,
                        'options_from_metadata' => 'available_providers',
                        'default' => 'openai',
                        'ui_options' => [
                            'min_width' => '200px',
                        ],
                    ],
                    [
                        'key' => 'model',
                        'type' => 'string',
                        'input_type' => 'text',
                        'label' => $pkg.'settings.models.model',
                        'required' => true,
                        'ui_options' => [
                            'min_width' => '300px',
                        ],
                    ],
                    [
                        'key' => 'capabilities_info',
                        'type' => 'virtual',
                        'input_type' => 'provider_info_display',
                        'label' => $pkg.'settings.models.capabilities_info',
                    ],
                ],

                'provider_config_fields' => [
                    'openai' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.organization_id', 'type' => 'string', 'input_type' => 'text', 'label' => 'Organization ID'],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api.openai.com/v1', 'description' => $pkg.'settings.models.base_url_desc'],
                    ],
                    'anthropic' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api.anthropic.com'],
                    ],
                    'google' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://generativelanguage.googleapis.com'],
                    ],
                    'xai' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api.x.ai/v1'],
                    ],
                    'mistral' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api.mistral.ai/v1'],
                    ],
                    'zhipu' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://open.bigmodel.cn/api/paas/v4'],
                    ],
                    'openrouter' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://openrouter.ai/api/v1'],
                    ],
                    'huggingface' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'HuggingFace Token', 'is_sensitive' => true, 'required' => true, 'description' => 'Token starts with hf_'],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api-inference.huggingface.co/models'],
                    ],
                    'huggingface-nllb' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'HuggingFace Token', 'is_sensitive' => true, 'required' => true, 'description' => 'Token starts with hf_'],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api-inference.huggingface.co/models'],
                    ],
                    'huggingface-opus-mt' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'HuggingFace Token', 'is_sensitive' => true, 'required' => true, 'description' => 'Token starts with hf_'],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api-inference.huggingface.co/models'],
                    ],
                    'huggingface-llama' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'HuggingFace Token', 'is_sensitive' => true, 'required' => true, 'description' => 'Token starts with hf_'],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api-inference.huggingface.co/models'],
                    ],
                    'huggingface-qwen' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'HuggingFace Token', 'is_sensitive' => true, 'required' => true, 'description' => 'Token starts with hf_'],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api-inference.huggingface.co/models'],
                    ],
                    'huggingface-mistral' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'HuggingFace Token', 'is_sensitive' => true, 'required' => true, 'description' => 'Token starts with hf_'],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api-inference.huggingface.co/models'],
                    ],
                    'huggingface-flux' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'HuggingFace Token', 'is_sensitive' => true, 'required' => true, 'description' => 'Token starts with hf_'],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api-inference.huggingface.co/models'],
                    ],
                    'huggingface-sd' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'HuggingFace Token', 'is_sensitive' => true, 'required' => true, 'description' => 'Token starts with hf_'],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api-inference.huggingface.co/models'],
                    ],
                    'huggingface-sdxl' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'HuggingFace Token', 'is_sensitive' => true, 'required' => true, 'description' => 'Token starts with hf_'],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api-inference.huggingface.co/models'],
                    ],
                    'ollama' => [
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'http://localhost:11434', 'required' => true],
                    ],
                    'deepseek' => [
                        ['key' => 'config.api_key', 'type' => 'string', 'input_type' => 'password', 'label' => 'API Key', 'is_sensitive' => true, 'required' => true],
                        ['key' => 'config.base_url', 'type' => 'string', 'input_type' => 'text', 'label' => 'Base URL', 'placeholder' => 'https://api.deepseek.com'],
                    ],
                ],

                // Provider options fields - additional options for each provider
                'provider_options_fields' => [
                    'huggingface-nllb' => [
                        ['key' => 'options.src_lang', 'type' => 'string', 'input_type' => 'text', 'label' => 'Default Source Language', 'default' => 'ar', 'description' => 'Default source language code (e.g., ar, en, fr)'],
                        ['key' => 'options.tgt_lang', 'type' => 'string', 'input_type' => 'text', 'label' => 'Default Target Language', 'default' => 'en', 'description' => 'Default target language code (e.g., ar, en, fr)'],
                    ],
                    'huggingface-opus-mt' => [
                        ['key' => 'options.src_lang', 'type' => 'string', 'input_type' => 'text', 'label' => 'Default Source Language', 'default' => 'en', 'description' => 'Default source language code'],
                        ['key' => 'options.tgt_lang', 'type' => 'string', 'input_type' => 'text', 'label' => 'Default Target Language', 'default' => 'ar', 'description' => 'Default target language code'],
                    ],
                    'huggingface-flux' => [
                        ['key' => 'options.num_inference_steps', 'type' => 'integer', 'input_type' => 'number', 'label' => 'Inference Steps', 'default' => 28, 'min' => 1, 'max' => 100, 'description' => 'Number of denoising steps'],
                        ['key' => 'options.guidance_scale', 'type' => 'float', 'input_type' => 'number', 'label' => 'Guidance Scale', 'default' => 3.5, 'step' => 0.1, 'description' => 'Controls how closely the model follows the prompt'],
                        ['key' => 'options.width', 'type' => 'integer', 'input_type' => 'select', 'label' => 'Default Width', 'default' => 1024, 'options' => [512, 768, 1024, 1280, 1536]],
                        ['key' => 'options.height', 'type' => 'integer', 'input_type' => 'select', 'label' => 'Default Height', 'default' => 1024, 'options' => [512, 768, 1024, 1280, 1536]],
                    ],
                    'huggingface-sd' => [
                        ['key' => 'options.negative_prompt', 'type' => 'string', 'input_type' => 'textarea', 'label' => 'Negative Prompt', 'default' => '', 'description' => 'What to avoid in the generated image'],
                        ['key' => 'options.num_inference_steps', 'type' => 'integer', 'input_type' => 'number', 'label' => 'Inference Steps', 'default' => 50, 'min' => 1, 'max' => 150],
                        ['key' => 'options.guidance_scale', 'type' => 'float', 'input_type' => 'number', 'label' => 'Guidance Scale', 'default' => 7.5, 'step' => 0.1],
                        ['key' => 'options.width', 'type' => 'integer', 'input_type' => 'select', 'label' => 'Default Width', 'default' => 512, 'options' => [512, 768, 1024]],
                        ['key' => 'options.height', 'type' => 'integer', 'input_type' => 'select', 'label' => 'Default Height', 'default' => 512, 'options' => [512, 768, 1024]],
                    ],
                    'huggingface-sdxl' => [
                        ['key' => 'options.negative_prompt', 'type' => 'string', 'input_type' => 'textarea', 'label' => 'Negative Prompt', 'default' => ''],
                        ['key' => 'options.num_inference_steps', 'type' => 'integer', 'input_type' => 'number', 'label' => 'Inference Steps', 'default' => 50, 'min' => 1, 'max' => 150],
                        ['key' => 'options.guidance_scale', 'type' => 'float', 'input_type' => 'number', 'label' => 'Guidance Scale', 'default' => 7.5, 'step' => 0.1],
                        ['key' => 'options.width', 'type' => 'integer', 'input_type' => 'select', 'label' => 'Default Width', 'default' => 1024, 'options' => [1024, 1280, 1536]],
                        ['key' => 'options.height', 'type' => 'integer', 'input_type' => 'select', 'label' => 'Default Height', 'default' => 1024, 'options' => [1024, 1280, 1536]],
                    ],
                    'huggingface-llama' => [
                        ['key' => 'options.max_new_tokens', 'type' => 'integer', 'input_type' => 'number', 'label' => 'Max Tokens', 'default' => 1000, 'min' => 1, 'max' => 4096],
                        ['key' => 'options.temperature', 'type' => 'float', 'input_type' => 'number', 'label' => 'Temperature', 'default' => 0.7, 'min' => 0, 'max' => 2, 'step' => 0.1],
                        ['key' => 'options.top_p', 'type' => 'float', 'input_type' => 'number', 'label' => 'Top P', 'default' => 0.95, 'min' => 0, 'max' => 1, 'step' => 0.05],
                        ['key' => 'options.top_k', 'type' => 'integer', 'input_type' => 'number', 'label' => 'Top K', 'default' => 50, 'min' => 1, 'max' => 100],
                        ['key' => 'options.do_sample', 'type' => 'boolean', 'input_type' => 'toggle', 'label' => 'Do Sample', 'default' => true],
                        ['key' => 'options.return_full_text', 'type' => 'boolean', 'input_type' => 'toggle', 'label' => 'Return Full Text', 'default' => false],
                    ],
                    'huggingface-qwen' => [
                        ['key' => 'options.max_new_tokens', 'type' => 'integer', 'input_type' => 'number', 'label' => 'Max Tokens', 'default' => 1000, 'min' => 1, 'max' => 4096],
                        ['key' => 'options.temperature', 'type' => 'float', 'input_type' => 'number', 'label' => 'Temperature', 'default' => 0.7, 'min' => 0, 'max' => 2, 'step' => 0.1],
                        ['key' => 'options.top_p', 'type' => 'float', 'input_type' => 'number', 'label' => 'Top P', 'default' => 0.95, 'min' => 0, 'max' => 1, 'step' => 0.05],
                        ['key' => 'options.top_k', 'type' => 'integer', 'input_type' => 'number', 'label' => 'Top K', 'default' => 50, 'min' => 1, 'max' => 100],
                        ['key' => 'options.do_sample', 'type' => 'boolean', 'input_type' => 'toggle', 'label' => 'Do Sample', 'default' => true],
                        ['key' => 'options.return_full_text', 'type' => 'boolean', 'input_type' => 'toggle', 'label' => 'Return Full Text', 'default' => false],
                    ],
                    'huggingface-mistral' => [
                        ['key' => 'options.max_new_tokens', 'type' => 'integer', 'input_type' => 'number', 'label' => 'Max Tokens', 'default' => 1000, 'min' => 1, 'max' => 4096],
                        ['key' => 'options.temperature', 'type' => 'float', 'input_type' => 'number', 'label' => 'Temperature', 'default' => 0.7, 'min' => 0, 'max' => 2, 'step' => 0.1],
                        ['key' => 'options.top_p', 'type' => 'float', 'input_type' => 'number', 'label' => 'Top P', 'default' => 0.95, 'min' => 0, 'max' => 1, 'step' => 0.05],
                        ['key' => 'options.top_k', 'type' => 'integer', 'input_type' => 'number', 'label' => 'Top K', 'default' => 50, 'min' => 1, 'max' => 100],
                        ['key' => 'options.do_sample', 'type' => 'boolean', 'input_type' => 'toggle', 'label' => 'Do Sample', 'default' => true],
                    ],
                ],
            ],
        ];

        return $metadata;
    }

    /**
     * Execute a custom action.
     *
     * @param  array<string, mixed>  $payload
     * @return array{success: bool, data?: array, errors?: array, message?: string, progress_id?: string}
     */
    public function executeAction(
        string $actionKey,
        array $payload,
        string $level,
        ?string $sectionKey = null,
        ?string $groupKey = null
    ): array {
        return match ($actionKey) {
            'test_connection' => $this->executeTestConnection(),
            default => [
                'success' => false,
                'message' => "Unknown action: {$actionKey}",
            ],
        };
    }

    /**
     * Execute test connection action.
     *
     * @return array{success: bool, message?: string}
     */
    protected function executeTestConnection(): array
    {
        try {
            $configured = $this->aiService->isConfigured();

            return [
                'success' => $configured,
                'message' => $configured ? 'AI connection successful' : 'AI not configured',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    /**
     * Handle an action for this provider (legacy support).
     *
     * @return array{success: bool, data?: array, errors?: array, message?: string}
     */
    public function handleAction(\App\Support\Settings\ValueObjects\SettingsAction $action): array
    {
        return match ($action->actionType) {
            'save' => $this->executeSaveAction($action->payload, null, null),
            'toggle' => $this->executeSaveAction($action->payload, null, null),
            default => [
                'success' => false,
                'message' => "Unknown action: {$action->actionType}",
            ],
        };
    }

    /**
     * Get the keys that should be treated as translatable.
     *
     * @return array<int, string>
     */
    public function getTranslatableKeys(): array
    {
        return [];
    }
}
