<?php

declare(strict_types=1);

namespace Sham\AI\Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class AISettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'ai.enabled',
                'value' => 'false',
                'type' => 'bool',
                'group' => 'ai',
                'input_type' => 'toggle',
                'display_order' => 1,
                'en' => ['label' => 'sham-ai::ai.enabled', 'description' => 'Main toggle for all AI-powered functionality.'],
                'ar' => ['label' => 'sham-ai::ai.enabled', 'description' => 'المفتاح الرئيسي لجميع وظائف الذكاء الاصطناعي.'],
            ],
            [
                'key' => 'ai.provider',
                'value' => 'openai',
                'type' => 'string',
                'group' => 'ai',
                'input_type' => 'select',
                'display_order' => 2,
                'validation_rules' => ['required', 'string', 'in:openai,anthropic,gemini'],
                'options' => ['openai' => 'OpenAI', 'anthropic' => 'Anthropic', 'gemini' => 'Google Gemini'],
                'en' => ['label' => 'sham-ai::ai.provider', 'description' => 'Select the AI service provider.'],
                'ar' => ['label' => 'sham-ai::ai.provider', 'description' => 'اختر مزود خدمة الذكاء الاصطناعي.'],
            ],
            [
                'key' => 'ai.model',
                'value' => 'gpt-4o',
                'type' => 'string',
                'group' => 'ai',
                'input_type' => 'text',
                'display_order' => 3,
                'validation_rules' => ['required', 'string', 'max:255'],
                'en' => ['label' => 'sham-ai::ai.model', 'description' => 'Specify the model to use (e.g., gpt-4o, claude-3-5-sonnet).'],
                'ar' => ['label' => 'sham-ai::ai.model', 'description' => 'حدد النموذج المطلوب استخدامه.'],
            ],
            [
                'key' => 'ai.api_key',
                'value' => null,
                'type' => 'string',
                'group' => 'ai',
                'input_type' => 'password',
                'is_encrypted' => true,
                'is_sensitive' => true,
                'display_order' => 4,
                'validation_rules' => ['nullable', 'string', 'max:255'],
                'en' => ['label' => 'sham-ai::ai.api_key', 'description' => 'API key for the selected provider.'],
                'ar' => ['label' => 'sham-ai::ai.api_key', 'description' => 'مفتاح API للمزود المختار.'],
            ],
            [
                'key' => 'ai.translation.enabled',
                'value' => 'true',
                'type' => 'bool',
                'group' => 'ai',
                'input_type' => 'toggle',
                'display_order' => 5,
                'en' => ['label' => 'sham-ai::ai.translation_enabled', 'description' => 'Allow using AI for translating content.'],
                'ar' => ['label' => 'sham-ai::ai.translation_enabled', 'description' => 'السماح باستخدام الذكاء الاصطناعي لترجمة المحتوى.'],
            ],
            [
                'key' => 'ai.temperature',
                'value' => '0.3',
                'type' => 'float',
                'group' => 'ai',
                'input_type' => 'slider',
                'options' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                'display_order' => 6,
                'validation_rules' => ['required', 'numeric', 'min:0', 'max:1'],
                'en' => ['label' => 'sham-ai::ai.temperature', 'description' => 'Controls randomness: 0 is deterministic, 1 is creative.'],
                'ar' => ['label' => 'sham-ai::ai.temperature', 'description' => 'يتحكم في العشوائية: 0 محدد، 1 إبداعي.'],
            ],
        ];

        foreach ($settings as $setting) {
            $baseAttributes = collect($setting)->except(['en', 'ar'])->toArray();
            $translationAttributes = collect($setting)->only(['en', 'ar'])->toArray();

            $model = Setting::updateOrCreate(
                ['key' => $setting['key']],
                $baseAttributes
            );

            foreach ($translationAttributes as $locale => $values) {
                $model->translateOrNew($locale)->fill($values);
            }

            if (! empty($translationAttributes)) {
                $model->save();
            }
        }
    }
}
