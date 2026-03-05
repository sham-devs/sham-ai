<?php

declare(strict_types=1);

return [
    'id' => 'المعرف',
    'messages' => [
        'test_connection' => 'اختبار الاتصال',
    ],
    'settings' => [
        'tab' => [
            'label' => 'إعداد الذكاء الاصطناعي',
            'title' => 'إعدادات الذكاء الاصطناعي',
            'description' => 'إعداد مزودي الذكاء الاصطناعي واستخداماتها.',
        ],
        'field' => [
            'provider' => [
                'label' => 'مزود الخدمة',
                'desc' => 'اختر مزود خدمة الذكاء الاصطناعي.',
            ],
            'search_term' => [
                'label' => 'بحث عن موديلات في الـ API (اختياري)',
                'placeholder' => 'مثال: llama, qwen, flux...',
                'desc' => 'أدخل كلمة مفتاحية للبحث عن موديلات محددة في Hugging Face أثناء المزامنة.',
            ],
            'models' => [
                'label' => 'نماذج الذكاء الاصطناعي',
                'desc' => 'إدارة نماذج متعددة وقدرات كل منها.',
            ],
        ],
        'models' => [
            'label' => 'نماذج الذكاء الاصطناعي',
            'add' => 'إضافة نموذج',
            'edit' => 'تعديل النموذج',
            'name' => 'الاسم',
            'enabled' => 'مفعل',
            'provider' => 'المزود',
            'model' => 'النموذج',
            'capabilities' => 'القدرات',
            'configure_desc' => 'إعداد خيارات نموذج الذكاء الاصطناعي وقدراته.',
            'empty_state' => 'لم يتم إعداد أي نماذج بعد.',
            'create' => 'أضف نموذجك الأول',
            'capabilities_info' => 'معلومات وقدرات النموذج',
            'base_url_desc' => 'اختياري. يُستخدم عند ربط بروكسي مخصص أو خادم محلي (مثل OpenAI Compatible API). إذا تُرك فارغاً، سيتم استخدام الرابط الافتراضي للمزود.',
        ],
        'provider_instructions' => [
            'how_to_find' => 'كيف تجد معرف النموذج',
            'example' => 'مثال',
            'openai' => [
                'instructions' => 'اذهب إلى صفحة Models، اختر الموديل، انسخ "Model ID"',
                'notes' => 'مثل: gpt-4o, gpt-5.2, o3, dall-e-3',
            ],
            'anthropic' => [
                'instructions' => 'اذهب إلى Console، اختر الموديل، انسخ "Model ID"',
                'notes' => 'مثل: claude-3-5-sonnet-latest',
            ],
            'google' => [
                'instructions' => 'اذهب إلى AI Studio، انسخ اسم الموديل',
                'notes' => 'مثل: gemini-2.0-flash-exp',
            ],
            'huggingface-flux' => [
                'instructions' => 'اذهب إلى HuggingFace، ابحث عن FLUX، انسخ "Model ID" كاملاً',
                'notes' => 'schnell = سريع، dev = جودة أعلى',
            ],
            'huggingface-nllb' => [
                'instructions' => 'انسخ "Model ID" للموديل المختار',
                'notes' => 'مثل: facebook/nllb-200-distilled-600M',
            ],
            'default' => [
                'instructions' => 'أدخل معرف الموديل يدوياً',
                'notes' => '',
            ],
        ],
        'capabilities' => [
            'text_generation' => 'توليد النصوص',
            'translation' => 'الترجمة',
            'seo' => 'تحليل SEO',
            'image_generation' => 'توليد الصور',
            'image_editing' => 'تحرير الصور',
        ],
        'capabilities_short' => [
            'text_generation' => 'نصوص',
            'translation' => 'ترجمة',
            'seo' => 'سيو',
            'image_generation' => 'صور',
            'image_editing' => 'تحرير',
        ],
        'capabilities_desc' => [
            'text_generation' => 'كتابة المحتوى والملخصات وتوليد النصوص',
            'translation' => 'ترجمة النصوص متعددة اللغات',
            'seo' => 'تحليل SEO وتوليد الوسوم الوصفية واقتراح الكلمات المفتاحية',
            'image_generation' => 'إنشاء الصور بالذكاء الاصطناعي من النصوص',
            'image_editing' => 'تحرير الصور وتحسينها بالذكاء الاصطناعي',
        ],
        'sections' => [
            'models' => [
                'title' => 'إدارة نماذج الذكاء الاصطناعي',
                'description' => 'إضافة وتعديل وحذف نماذج الذكاء الاصطناعي وإعدادات المزودين الخاصة بها.',
            ],
        ],
        'action' => [
            'save_section' => 'حفظ الإعدادات',
            'sync_models' => 'مزامنة النماذج من API',
            'reset_defaults' => 'إعادة الضبط',
            'confirm_reset' => 'هل أنت متأكد من إعادة جميع إعدادات الذكاء الاصطناعي إلى قيمها الافتراضية؟',
        ],
        'messages' => [
            'no_translation_models' => 'الذكاء الاصطناعي غير مفعل - لا توجد نماذج ترجمة متوفرة.',
        ],
        'errors' => [
            'permissions' => 'الموديل يتطلب صلاحيات خاصة أو توكن صالح (Gated).',
            'payment' => 'رصيد غير كافٍ لهذا الموديل في حساب المزود الخاص بك.',
            'rate_limit' => 'تم تجاوز حد الاستدعاء المسموح به. يرجى المحاولة لاحقاً.',
            'unavailable' => 'الموديل قيد التحميل حالياً أو أن السيرفر مثقل بالأعطال.',
            'generic' => 'حدث خطأ تقني أثناء تنفيذ الموديل.',
        ],
        'status' => [
            'payment_required' => 'موديل مدفوع (يتطلب رصيد)',
            'gated' => 'موديل مغلق (يتطلب تسجيل/صلاحيات)',
            'usable' => 'موديل متاح',
        ],
    ],
];
