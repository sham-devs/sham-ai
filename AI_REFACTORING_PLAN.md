# خطة إعادة تصميم مكتبة Sham-AI

## السياق

مكتبة `sham-ai` الحالية تعمل بمزود واحد فقط (PrismProvider) ولا تدعم إدارة متعدد الموديلات أو تصنيف القدرات. الهدف هو تحويلها إلى بوابة موحدة للذكاء الاصطناعي مع:

1. دعم متعدد الموديلات (إضافة/تفعيل/تعطيل/حذف)
2. نظام قدرات (Capabilities) قابل للتوسع
3. توحيد واجهات الاستخدام

## القدرات المدعومة في هذه المرحلة

| القدرة | الوصف |
|--------|-------|
| **Translation** | ترجمة النصوص بين اللغات |
| **Content Generation** | توليد المحتوى (نصوص، أوصاف، مقالات) |
| **SEO** | تحسين محركات البحث (meta tags، keywords، تحليل المحتوى) |

## الأمان
- تشفير كل مفتاح API بشكل منفصل في قاعدة البيانات باستخدام `encrypt()` في Laravel

## ملاحظات مهمة
- **لا توافقية مطلوبة**: النظام في مرحلة التطوير، انتقال كامل للجديد
- **لا يوجد موديل افتراضي**: اختيار الموديل يتم في المكتبة المستخدمة (مثل مكتبة الترجمة)
- **الموديلات قابلة للتكرار**: يمكن إضافة نفس الموديل بأسماء مختلفة

---

## هيكل الموديلات

### إعدادات الموديل
```php
[
    'id' => 'gpt-4o-main',           // معرف فريد
    'name' => 'GPT-4o Primary',       // اسم العرض (مخصص من المستخدم)
    'provider' => 'openai',           // المزود (openai, anthropic, gemini)
    'model' => 'gpt-4o',              // اسم الموديل الفعلي
    'enabled' => true,                // مفعّل أو لا
    'capabilities' => ['translation', 'content_generation', 'seo'], // القدرات المدعومة
    'config' => [                     // إعدادات إضافية
        'api_key' => '...',           // مشفر
        'temperature' => 0.3,
    ],
]
```

### إدارة الموديلات
- **إضافة**: إضافة موديل جديد من الموديلات المدعومة
- **تفعيل/تعطيل**: تحكم في توفر الموديل
- **حذف**: إزالة الموديل من النظام
- **تكرار**: يمكن إضافة نفس الموديل (مثلاً gpt-4o) بأسماء مختلفة لاستخدامات مختلفة

---

## المرحلة 1: نظام القدرات (Capabilities)

### 1.1 واجهة القدرة الأساسية
**الملف:** `src/Capabilities/CapabilityInterface.php`
```php
interface CapabilityInterface
{
    public static function getCapabilityName(): string;    // 'translation'
    public static function getCapabilityLabel(): string;   // 'Translation'
    public static function getCapabilityDescription(): string;
}
```

### 1.2 واجهة القدرات - الترجمة
**الملف:** `src/Capabilities/Contracts/TranslationCapabilityInterface.php`
```php
interface TranslationCapabilityInterface extends CapabilityInterface
{
    /**
     * التحقق من قدرة الموديل على الترجمة.
     */
    public function canTranslate(): bool;

    /**
     * ترجمة مصفوفة نصوص.
     *
     * @param TranslationRequest $request
     * @return TranslationResponse يحتوي على نفس المفاتيح مع قيم مترجمة
     *
     * مثال:
     * Input:  ['title' => 'Hello', 'desc' => 'World']
     * Output: ['title' => 'مرحبا', 'desc' => 'عالم']
     */
    public function translate(TranslationRequest $request): TranslationResponse;
}
```

**ملاحظة مهمة:**
- القدرة مسؤولة عن: إعداد البرومبت، إرساله للـ AI، تفسير الرد
- مكتبة الترجمة فقط ترسل المصفوفة وتستقبل المصفوفة المترجمة
- لا حاجة لـ PromptBuilder خارجي - كل شيء داخل الـ Capability

### 1.3 واجهة توليد المحتوى
**الملف:** `src/Capabilities/Contracts/ContentGenerationCapabilityInterface.php`
```php
interface ContentGenerationCapabilityInterface extends CapabilityInterface
{
    public function canGenerateContent(): bool;
    public function generate(ContentGenerationRequest $request): ContentGenerationResponse;
    public function getSupportedContentTypes(): array; // ['article', 'description', 'summary', ...]
}
```

### 1.4 واجهة SEO
**الملف:** `src/Capabilities/Contracts/SEOCapabilityInterface.php`
```php
interface SEOCapabilityInterface extends CapabilityInterface
{
    public function canAnalyzeSEO(): bool;
    public function analyzeSEO(SEORequest $request): SEOResponse;
    public function generateMetaTags(SEORequest $request): MetaTagsResponse;
    public function suggestKeywords(string $content, string $locale): array;
    public function improveContentForSEO(SEORequest $request): string;
}
```

### 1.5 DTOs للترجمة
**الملف:** `src/Capabilities/DTOs/TranslationRequest.php`
```php
readonly class TranslationRequest
{
    public function __construct(
        // المصفوفة المراد ترجمتها (key => text)
        public array $texts,
        // اللغة المصدر
        public string $fromLocale,
        // اللغة الهدف
        public string $toLocale,
        // الخيارات الإضافية
        public array $options = [],
    ) {}

    /**
     * الخيارات المدعومة:
     * - context: string    - سياق المحتوى ('government', 'ecommerce', 'blog', ...)
     * - tone: string       - النبرة ('formal', 'casual', 'professional')
     * - preserve_placeholders: bool - الحفاظ على المتغيرات (:name, {{var}})
     * - preserve_html: bool         - الحفاظ على وسوم HTML
     */
}
```

**الملف:** `src/Capabilities/DTOs/TranslationResponse.php`
```php
readonly class TranslationResponse
{
    public function __construct(
        public bool $successful,
        // المصفوفة المترجمة (نفس المفاتيح، قيم مترجمة)
        public array $translations = [],
        public ?string $error = null,
        public array $usage = [],
        public string $modelUsed = '',
    ) {}

    /**
     * translations = ['title' => 'العنوان', 'description' => 'الوصف']
     * نفس مفاتيح الـ texts المدخلة
     */
}
```

### 1.6 DTOs لتوليد المحتوى
**الملف:** `src/Capabilities/DTOs/ContentGenerationRequest.php`
```php
readonly class ContentGenerationRequest
{
    public function __construct(
        public string $type,           // 'article', 'description', 'summary'
        public string $topic,
        public string $locale,
        public array $context = [],
        public int $maxLength = 1000,
        public string $tone = 'professional', // 'professional', 'casual', 'formal'
    ) {}
}
```

### 1.7 DTOs للـ SEO
**الملف:** `src/Capabilities/DTOs/SEORequest.php`
```php
readonly class SEORequest
{
    public function __construct(
        public string $content,
        public string $locale,
        public ?string $title = null,
        public ?string $url = null,
        public array $targetKeywords = [],
        public array $options = [],
    ) {}
}
```

---

## المرحلة 2: نظام إدارة الموديلات

### 2.1 كائن الموديل
**الملف:** `src/Models/AIModel.php`
```php
readonly class AIModel
{
    public function __construct(
        public string $id,
        public string $name,
        public string $provider,
        public string $model,
        public bool $enabled,
        public array $capabilities = [],
        public array $config = [],
        public bool $isDefault = false,
        public int $priority = 0,
    ) {}

    public function supportsCapability(string $capability): bool;
}
```

### 2.2 سجل الموديلات
**الملف:** `src/Models/ModelRegistry.php`
```php
class ModelRegistry
{
    public function getAll(): Collection;                          // كل الموديلات
    public function getEnabled(): Collection;                       // المفعّلة فقط
    public function getByCapability(string $capability): Collection; // المفعّلة حسب القدرة
    public function get(string $id): ?AIModel;
    public function add(AIModel $model): void;                      // إضافة موديل
    public function update(string $id, array $data): void;          // تحديث موديل
    public function delete(string $id): void;                       // حذف موديل
    public function enable(string $id): void;                       // تفعيل
    public function disable(string $id): void;                      // تعطيل
}
```

### 2.3 الموديلات المدعومة (Available Models)
**الملف:** `src/Models/SupportedModels.php`

**التنظيم:** الموديلات منظمة حسب المزود لتستخدم في واجهة الإضافة:

```php
class SupportedModels
{
    /**
     * الحصول على قائمة المزودات المدعومة.
     */
    public static function getProviders(): array
    {
        return [
            'openai' => 'OpenAI',
            'anthropic' => 'Anthropic',
            'gemini' => 'Google Gemini',
        ];
    }

    /**
     * الحصول على موديلات مزود محدد.
     * تستخدم عند اختيار المزود في واجهة الإضافة.
     */
    public static function getModelsForProvider(string $provider): array
    {
        return match ($provider) {
            'openai' => [
                ['model' => 'gpt-4o', 'name' => 'GPT-4o', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'gpt-4o-mini', 'name' => 'GPT-4o Mini', 'capabilities' => ['translation', 'content_generation']],
                ['model' => 'gpt-4-turbo', 'name' => 'GPT-4 Turbo', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'gpt-3.5-turbo', 'name' => 'GPT-3.5 Turbo', 'capabilities' => ['translation']],
            ],
            'anthropic' => [
                ['model' => 'claude-3-5-sonnet-20241022', 'name' => 'Claude 3.5 Sonnet', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'claude-3-opus-20240229', 'name' => 'Claude 3 Opus', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'claude-3-haiku-20240307', 'name' => 'Claude 3 Haiku', 'capabilities' => ['translation', 'content_generation']],
            ],
            'gemini' => [
                ['model' => 'gemini-1.5-pro', 'name' => 'Gemini 1.5 Pro', 'capabilities' => ['translation', 'content_generation', 'seo']],
                ['model' => 'gemini-1.5-flash', 'name' => 'Gemini 1.5 Flash', 'capabilities' => ['translation', 'content_generation']],
            ],
            default => [],
        };
    }

    /**
     * الحصول على معلومات موديل محدد.
     */
    public static function getModelInfo(string $provider, string $model): ?array
    {
        $models = self::getModelsForProvider($provider);
        return collect($models)->firstWhere('model', $model);
    }
}
```

### واجهة إضافة موديل:

```
┌─────────────────────────────────────────────────────────────┐
│  إضافة موديل جديد                                           │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  المزود:                                                    │
│  ┌─────────────────────────────────────────┐               │
│  │ OpenAI                          ▼      │               │
│  └─────────────────────────────────────────┘               │
│                                                             │
│  الموديل: (تتغير حسب المزود المختار)                       │
│  ┌─────────────────────────────────────────┐               │
│  │ GPT-4o                           ▼      │               │
│  └─────────────────────────────────────────┘               │
│  القدرات: translation, content_generation, seo             │
│                                                             │
│  اسم مخصص: (اختياري)                                        │
│  ┌─────────────────────────────────────────┐               │
│  │ GPT-4o للترجمة                          │               │
│  └─────────────────────────────────────────┘               │
│                                                             │
│  API Key:                                                   │
│  ┌─────────────────────────────────────────┐               │
│  │ ••••••••••••••••••                      │               │
│  └─────────────────────────────────────────┘               │
│                                                             │
│  [إضافة]  [إلغاء]                                          │
└─────────────────────────────────────────────────────────────┘
```

### API Endpoint:
```php
// GET /api/ai/models/available?provider=openai
// يرجع موديلات المزود المحدد للقائمة المنسدلة
```

---

## المرحلة 3: نظام الـ Adapters

### 3.1 الـ Adapter الأساسي
**الملف:** `src/Providers/Adapters/AbstractProviderAdapter.php`
```php
abstract class AbstractProviderAdapter implements AIProviderInterface
{
    public function getModel(): AIModel;
    abstract public function send(PromptInterface $prompt): AIResponseInterface;
    abstract public function isConfigured(): bool;
}
```

### 3.2 Prism Adapter مع قدرة الترجمة
**الملف:** `src/Providers/Adapters/PrismAdapter.php`
```php
class PrismAdapter extends AbstractProviderAdapter implements TranslationCapabilityInterface
{
    // AIProviderInterface
    public function send(PromptInterface $prompt): AIResponseInterface;
    public function isConfigured(): bool;

    // TranslationCapabilityInterface
    public function canTranslate(): bool;
    public function translate(TranslationRequest $request): TranslationResponse;
}
```

---

## المرحلة 4: تحديث AIService

**الملف:** `src/AIService.php`

### الطرق:
```php
class AIService
{
    // إدارة الموديلات
    public function getModels(): Collection;                        // كل الموديلات المضافة
    public function getEnabledModels(): Collection;                 // المفعّلة فقط
    public function getModelsByCapability(string $capability): Collection; // المفعّلة ذات القدرة المطلوبة
    public function getModel(string $modelId): ?AIModel;
    public function addModel(array $data): AIModel;
    public function updateModel(string $modelId, array $data): void;
    public function deleteModel(string $modelId): void;
    public function enableModel(string $modelId): void;
    public function disableModel(string $modelId): void;

    // الحصول على adapter لموديل محدد
    public function getAdapter(string $modelId): AbstractProviderAdapter;

    // الحصول على adapter مع التحقق من القدرة
    public function getAdapterWithCapability(string $modelId, string $capabilityInterface): ?CapabilityInterface;

    // الحصول على الموديلات المتاحة للإضافة (من القائمة المدعومة)
    public function getSupportedModels(): array;
}
```

### مثال الاستخدام:
```php
// ═══════════════════════════════════════════════════════════════
// 1. إدارة الموديلات (من إعدادات AI)
// ═══════════════════════════════════════════════════════════════

// إضافة موديل جديد
$newModel = $aiService->addModel([
    'name' => 'GPT-4o للترجمة',  // اسم مخصص
    'provider' => 'openai',
    'model' => 'gpt-4o',
    'capabilities' => ['translation', 'content_generation', 'seo'],
    'config' => ['api_key' => 'sk-...'],
]);

// تفعيل/تعطيل/حذف موديل
$aiService->enableModel('gpt-4o-main');
$aiService->disableModel('gpt-4o-main');
$aiService->deleteModel('gpt-4o-main');

// ═══════════════════════════════════════════════════════════════
// 2. اختيار الموديل (من إعدادات مكتبة الترجمة)
// ═══════════════════════════════════════════════════════════════

// الحصول على الموديلات المفعّلة التي تدعم الترجمة
$translationModels = $aiService->getModelsByCapability('translation');
// عرضها في قائمة منسدلة للاختيار

// ═══════════════════════════════════════════════════════════════
// 3. استخدام الترجمة (من مكتبة الترجمة)
// ═══════════════════════════════════════════════════════════════

// الحصول على الموديل المختار من الإعدادات
$modelId = $translationSettings->get('translation.ai_model');

// الحصول على adapter مع التحقق من القدرة
$adapter = $aiService->getAdapterWithCapability(
    $modelId,
    TranslationCapabilityInterface::class
);

// إعداد الطلب
$request = new TranslationRequest(
    texts: ['title' => 'Hello World', 'desc' => 'Welcome'],
    fromLocale: 'en',
    toLocale: 'ar',
    options: [
        'context' => 'government',
        'tone' => 'formal',
        'preserve_placeholders' => true,
        'preserve_html' => true,
    ],
);

// الترجمة
$response = $adapter->translate($request);

// النتيجة: ['title' => 'مرحبا بالعالم', 'desc' => 'أهلاً بك']
$translations = $response->translations;

// ═══════════════════════════════════════════════════════════════
// 4. استخدام SEO (مثال)
// ═══════════════════════════════════════════════════════════════

$seoModels = $aiService->getModelsByCapability('seo');
$modelId = $seoSettings->get('seo.ai_model');

$adapter = $aiService->getAdapterWithCapability($modelId, SEOCapabilityInterface::class);
$metaTags = $adapter->generateMetaTags(new SEORequest(
    content: $pageContent,
    locale: 'ar',
));
```

---

## المرحلة 5: تحديث الإعدادات

**الملف:** `src/Settings/Concerns/AISettingsFields.php`

### الحقول:
| المفتاح | النوع | الوصف |
|---------|-------|-------|
| `ai.enabled` | boolean | تفعيل الذكاء الاصطناعي |
| `ai.models` | array | قائمة الموديلات المضافة |

### واجهة الإدارة:
- عرض الموديلات المضافة مع إمكانية التفعيل/التعطيل/الحذف
- زر "إضافة موديل" يعرض قائمة الموديلات المدعومة
- عند الإضافة: اختيار الموديل من القائمة + إدخال اسم مخصص + API key

---

## المرحلة 6: تحديث مكتبة الترجمة

### التبسيط الجديد:
مكتبة الترجمة لم تعد تعد البرومبت - فقط ترسل النصوص وتستقبل الترجمة.

### إعدادات مكتبة الترجمة
**الملف:** `sham-translation/src/Settings/TranslationSettingsFields.php`

إضافة حقل جديد:
| المفتاح | النوع | الوصف |
|---------|-------|-------|
| `translation.ai_model` | string | الموديل المستخدم للترجمة |

### تحديث ShamAIProvider
**الملف:** `sham-translation/src/Providers/ShamAIProvider.php`

```php
public function translate(TranslationPrompt $prompt): array
{
    // 1. الحصول على الموديل المختار من الإعدادات
    $modelId = $this->settings->get('translation.ai_model');

    if (!$modelId) {
        Log::warning('لم يتم اختيار موديل للترجمة');
        return [];
    }

    // 2. الحصول على adapter مع التحقق من قدرة الترجمة
    $adapter = $this->aiService->getAdapterWithCapability(
        $modelId,
        TranslationCapabilityInterface::class
    );

    if (!$adapter) {
        Log::warning('الموديل المختار لا يدعم الترجمة أو غير مفعّل');
        return [];
    }

    // 3. إعداد الطلب - فقط النصوص واللغات والخيارات
    $request = new TranslationRequest(
        texts: $this->extractTexts($prompt),
        fromLocale: $prompt->fromLocale,
        toLocale: $prompt->toLocale,
        options: [
            'context' => $prompt->context['type'] ?? null,
            'tone' => 'formal', // أو من الإعدادات
            'preserve_placeholders' => true,
            'preserve_html' => true,
        ],
    );

    // 4. استدعاء القدرة
    $response = $adapter->translate($request);

    // 5. إرجاع النتيجة
    return $response->successful ? $response->translations : [];
}
```

### حذف الملفات غير الضرورية:
- `sham-translation/.../AIPromptBuilderService.php` - لم يعد مطلوباً
- `app/Services/Translation/CustomTranslationPromptBuilder.php` - لم يعد مطلوباً

**ملاحظة:** إذا أردنا دعم سياق مخصص للموديلات المختلفة، يمكن إضافة ذلك كخيار في الـ TranslationRequest.

---

## هيكل المجلدات النهائي

```
sham-ai/
├── src/
│   ├── AIService.php
│   ├── AIServiceProvider.php
│   ├── AIPackage.php
│   ├── Capabilities/
│   │   ├── CapabilityInterface.php
│   │   ├── Contracts/
│   │   │   ├── TranslationCapabilityInterface.php
│   │   │   ├── ContentGenerationCapabilityInterface.php
│   │   │   └── SEOCapabilityInterface.php
│   │   └── DTOs/
│   │       ├── TranslationRequest.php
│   │       ├── TranslationResponse.php
│   │       ├── ContentGenerationRequest.php
│   │       ├── ContentGenerationResponse.php
│   │       ├── SEORequest.php
│   │       ├── SEOResponse.php
│   │       └── MetaTagsResponse.php
│   ├── Contracts/ (موجود - للتوافقية)
│   ├── Models/
│   │   ├── AIModel.php
│   │   └── ModelRegistry.php
│   ├── Providers/
│   │   ├── PrismProvider.php (deprecated)
│   │   └── Adapters/
│   │       ├── AbstractProviderAdapter.php
│   │       └── PrismAdapter.php
│   ├── Prompts/
│   │   ├── TranslationPrompt.php (موجود)
│   │   ├── FileTranslationPrompt.php (موجود)
│   │   ├── ContentGenerationPrompt.php (جديد)
│   │   └── SEOPrompt.php (جديد)
│   ├── Responses/
│   │   └── PrismResponse.php (موجود)
│   └── Settings/
│       ├── AISettingsProvider.php
│       └── Concerns/
│           ├── AISettingsCards.php
│           └── AISettingsFields.php
```

---

## ملفات التعديل الرئيسية

| الملف | التعديل |
|-------|---------|
| `src/AIService.php` | إعادة كتابة كاملة |
| `src/Settings/Concerns/AISettingsFields.php` | تحديث الحقول |
| `src/AIServiceProvider.php` | تحديث التسجيل |
| `sham-translation/.../ShamAIProvider.php` | استخدام النظام الجديد |
| `sham-translation/.../TranslationSettingsFields.php` | إضافة حقل اختيار الموديل |

## ملفات جديدة

| الملف | الوصف |
|-------|-------|
| `src/Capabilities/CapabilityInterface.php` | واجهة القدرة الأساسية |
| `src/Capabilities/Contracts/TranslationCapabilityInterface.php` | واجهة الترجمة |
| `src/Capabilities/Contracts/ContentGenerationCapabilityInterface.php` | واجهة توليد المحتوى |
| `src/Capabilities/Contracts/SEOCapabilityInterface.php` | واجهة SEO |
| `src/Capabilities/DTOs/TranslationRequest.php` | طلب الترجمة |
| `src/Capabilities/DTOs/TranslationResponse.php` | رد الترجمة |
| `src/Capabilities/DTOs/ContentGenerationRequest.php` | طلب توليد المحتوى |
| `src/Capabilities/DTOs/ContentGenerationResponse.php` | رد توليد المحتوى |
| `src/Capabilities/DTOs/SEORequest.php` | طلب SEO |
| `src/Capabilities/DTOs/SEOResponse.php` | رد SEO |
| `src/Capabilities/DTOs/MetaTagsResponse.php` | رد Meta Tags |
| `src/Models/AIModel.php` | كائن الموديل |
| `src/Models/ModelRegistry.php` | سجل الموديلات |
| `src/Models/SupportedModels.php` | قائمة الموديلات المدعومة |
| `src/Providers/Adapters/AbstractProviderAdapter.php` | الـ adapter الأساسي |
| `src/Providers/Adapters/PrismAdapter.php` | prism adapter مع كل القدرات |
| `src/Prompts/ContentGenerationPrompt.php` | برومبت توليد المحتوى |
| `src/Prompts/SEOPrompt.php` | برومبت SEO |

## ملفات الحذف

| الملف | السبب |
|-------|-------|
| `sham-ai/src/Providers/PrismProvider.php` | استبدال بـ PrismAdapter |
| `sham-ai/src/Contracts/AIPromptBuilderInterface.php` | لم يعد مطلوباً - Capability تعالج البرومبت |
| `sham-translation/.../AIPromptBuilderService.php` | لم يعد مطلوباً |
| `app/Services/Translation/CustomTranslationPromptBuilder.php` | لم يعد مطلوباً |

---

## خطوات التنفيذ

1. **إنشاء نظام القدرات** - CapabilityInterface + DTOs
2. **إنشاء نظام الموديلات** - AIModel + ModelRegistry + SupportedModels
3. **إنشاء الـ Adapters** - AbstractProviderAdapter + PrismAdapter
4. **إعادة كتابة AIService** - الطرق الجديدة كاملة
5. **تحديث الإعدادات** - حقول جديدة + واجهة الإدارة
6. **تحديث sham-translation** - استخدام النظام الجديد + حقل اختيار الموديل
7. **إنشاء Prompts الجديدة** - ContentGenerationPrompt + SEOPrompt
8. **حذف الملفات القديمة** - PrismProvider وغيرها
9. **اختبارات** - اختبارات جديدة للنظام الجديد

---

## التحقق

1. **اختبارات الوحدة:**
   - `php artisan test --compact tests/Unit/AIServiceTest.php` (جديد)
   - `php artisan test --compact tests/Unit/ModelRegistryTest.php` (جديد)
   - `php artisan test --compact tests/Unit/CapabilitiesTest.php` (جديد)

2. **اختبارات التكامل:**
   - `php artisan test --compact tests/Feature/AIIntegrationTest.php`
   - `php artisan test --compact tests/Unit/ShamTranslationIntegrationTest.php`

3. **اختبارات يدوية:**
   - إضافة موديل جديد من القائمة المدعومة
   - تفعيل/تعطيل/حذف موديل
   - اختيار موديل للترجمة من إعدادات مكتبة الترجمة
   - اختبار الترجمة
   - اختبار توليد المحتوى
   - اختبار SEO
   - التحقق من تشفير API keys

---

## برومبت التنفيذ

```
نفذ خطة إعادة تصميم مكتبة Sham-AI من الملف:
/home/basel/Development/ShamPackages/sham-ai/AI_REFACTORING_PLAN.md

الهدف: تحويل المكتبة إلى بوابة موحدة للذكاء الاصطناعي مع:
1. دعم متعدد الموديلات (إضافة/تفعيل/تعطيل/حذف)
2. نظام قدرات (Capabilities): Translation, ContentGeneration, SEO
3. كل قدرة مسؤولة عن إعداد البرومبت والرد

النقاط المهمة:
- لا توافقية مطلوبة - انتقال كامل للجديد
- اختيار المزود أولاً ثم الموديل من قائمة منسدلة
- تشفير API keys
- مكتبة الترجمة فقط ترسل مصفوفة وتستقبل مصفوفة مترجمة

ابدأ بالمرحلة 1: إنشاء نظام القدرات (Capabilities)
```
