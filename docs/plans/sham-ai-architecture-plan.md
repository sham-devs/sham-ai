# خطة معمارية sham-ai المحدثة

## القرارات الأساسية

### 1. توحيد كل المزودين تحت Prism
- **كل المزودين يعملون من خلال Prism**
- المزودون المدمجون (Built-in): OpenAI, Anthropic, Google, DeepSeek, xAI, Mistral, Ollama
- المزودون المخصصون (Custom): Zhipu, HuggingFace Model Families

### 2. إلغاء OpenRouter
- OpenRouter ملغى تماماً من الخطة
- السبب: تعقيد إضافي بدون فائدة كبيرة

### 3. إلغاء البحث عن بعد (Remote Search)
- لا يوجد بحث ديناميكي عن موديلات
- إضافة الموديلات يدوياً فقط
- قوائم hardcoded للموديلات المقترحة

### 4. HuggingFace = عدة مزودين (Model Families)
- كل عائلة موديلات في HuggingFace تصبح مزود منفصل
- يرثون من BaseHuggingFaceProvider
- لكل عائلة handler خاص للـ payload

---

## البنية الجديدة

```
┌─────────────────────────────────────────────────────────────────────────┐
│                          sham-ai (موحد)                                   │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  AIService ──► ModelRegistry ──► AIModel                                 │
│       │                                    │                             │
│       ▼                                    ▼                             │
│  getAdapter($modelId)              supportsCapability()                  │
│       │                                                                   │
│       ▼                                                                   │
│  ┌─────────────────────────────────────────────────────────────────────┐ │
│  │                    PrismAdapter (موحد)                               │ │
│  │                         │                                            │ │
│  │                         ▼                                            │ │
│  │   Prism::text()->using($provider, $model)                           │ │
│  │                         │                                            │ │
│  │         ┌───────────────┴───────────────┐                           │ │
│  │         ▼                               ▼                           │ │
│  │   Built-in Providers              Custom Providers                  │ │
│  │   ┌───────────────┐              ┌────────────────────┐            │ │
│  │   │ OpenAI        │              │ ZhipuProvider      │            │ │
│  │   │ Anthropic     │              ├────────────────────┤            │ │
│  │   │ Google        │              │ HuggingFace        │            │ │
│  │   │ DeepSeek      │              │ ├── NllbProvider   │            │ │
│  │   │ xAI           │              │ ├── OpusMtProvider │            │ │
│  │   │ Mistral       │              │ ├── LlamaProvider  │            │ │
│  │   │ Ollama        │              │ ├── QwenProvider   │            │ │
│  │   └───────────────┘              │ ├── MistralProvider│            │ │
│  │                                  │ ├── FluxProvider   │            │ │
│  │                                  │ ├── SDProvider     │            │ │
│  │                                  │ └── SdxlProvider   │            │ │
│  │                                  └────────────────────┘            │ │
│  └─────────────────────────────────────────────────────────────────────┘ │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## المزودون المدعومون

### 1. Prism Built-in Providers

| المزود | الاسم في Prism | Capabilities | إضافة موديلات |
|--------|---------------|--------------|---------------|
| OpenAI | `openai` | text, translation, seo, image | hardcoded |
| Anthropic | `anthropic` | text, translation, seo | hardcoded |
| Google | `gemini` | text, translation, seo, image | hardcoded |
| DeepSeek | `deepseek` | text, translation, seo | hardcoded |
| xAI | `xai` | text, translation, seo | hardcoded |
| Mistral | `mistral` | text, translation, seo | hardcoded |
| Ollama | `ollama` | text, translation | hardcoded + local |

### 2. Custom Prism Providers

| المزود | Provider Class | Capabilities | إضافة موديلات |
|--------|---------------|--------------|---------------|
| Zhipu | `ZhipuProvider` | text, translation, seo, image | يدوية فقط |
| HuggingFace NLLB | `HuggingFace\NllbProvider` | translation | يدوية فقط |
| HuggingFace Opus-MT | `HuggingFace\OpusMtProvider` | translation | يدوية فقط |
| HuggingFace Llama | `HuggingFace\LlamaProvider` | text, translation | يدوية فقط |
| HuggingFace Qwen | `HuggingFace\QwenProvider` | text, translation | يدوية فقط |
| HuggingFace Mistral | `HuggingFace\MistralProvider` | text, translation | يدوية فقط |
| HuggingFace FLUX | `HuggingFace\FluxProvider` | image | يدوية فقط |
| HuggingFace SD | `HuggingFace\SDProvider` | image | يدوية فقط |
| HuggingFace SDXL | `HuggingFace\SdxlProvider` | image | يدوية فقط |

---

## معمارية HuggingFace

### BaseHuggingFaceProvider (Abstract)

```php
namespace Sham\AI\Prism\Providers\HuggingFace;

abstract class BaseHuggingFaceProvider extends Provider
{
    protected string $baseUrl = 'https://api-inference.huggingface.co/models/';

    public function __construct(
        protected string $apiKey,
    ) {}

    /**
     * كشف نوع العائلة من اسم الموديل
     */
    public static function detectFamily(string $modelId): ?string
    {
        $families = [
            'nllb' => 'facebook/nllb-',
            'opus-mt' => 'Helsinki-NLP/opus-mt-',
            'llama' => 'meta-llama/Llama-',
            'qwen' => 'Qwen/Qwen-',
            'mistral' => 'mistralai/Mistral-',
            'flux' => 'black-forest-labs/FLUX-',
            'sd' => 'stabilityai/stable-diffusion-',
            'sdxl' => 'stabilityai/sdxl-',
        ];

        foreach ($families as $family => $prefix) {
            if (str_starts_with($modelId, $prefix)) {
                return $family;
            }
        }

        return null;
    }

    /**
     * إرسال طلب إلى HuggingFace API
     */
    protected function sendRequest(string $modelId, array $payload): array
    {
        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl . $modelId, $payload);

        if (!$response->successful()) {
            throw new \RuntimeException(
                "HuggingFace API Error: {$response->status()} - {$response->body()}"
            );
        }

        return $response->json();
    }
}
```

### مثال: NllbProvider (Translation)

```php
namespace Sham\AI\Prism\Providers\HuggingFace;

class NllbProvider extends BaseHuggingFaceProvider
{
    public function text(TextRequest $request): TextResponse
    {
        // NLLB موديلات ترجمة
        $payload = [
            'inputs' => $request->prompt,
            'parameters' => [
                'src_lang' => $this->extractSourceLang($request),
                'tgt_lang' => $this->extractTargetLang($request),
            ],
        ];

        $data = $this->sendRequest($this->model, $payload);
        $text = $data[0]['translation_text'] ?? '';

        return new TextResponse(text: $text);
    }
}
```

### مثال: LlamaProvider (Text Generation)

```php
namespace Sham\AI\Prism\Providers\HuggingFace;

class LlamaProvider extends BaseHuggingFaceProvider
{
    public function text(TextRequest $request): TextResponse
    {
        // Llama 3.x chat format
        $prompt = "<|begin_of_text|><|start_header_id|>user<|end_header_id|>\n\n"
            . $request->prompt
            . "<|eot_id|><|start_header_id|>assistant<|end_header_id|>\n\n";

        $payload = [
            'inputs' => $prompt,
            'parameters' => [
                'max_new_tokens' => $request->maxTokens ?? 1024,
                'temperature' => $request->temperature ?? 0.7,
                'return_full_text' => false,
            ],
        ];

        $data = $this->sendRequest($this->model, $payload);
        $text = $data[0]['generated_text'] ?? '';

        return new TextResponse(text: $text);
    }
}
```

### مثال: FluxProvider (Image Generation)

```php
namespace Sham\AI\Prism\Providers\HuggingFace;

class FluxProvider extends BaseHuggingFaceProvider
{
    public function images(ImageRequest $request): ImageResponse
    {
        $payload = [
            'inputs' => $request->prompt,
            'parameters' => [
                'num_inference_steps' => 4, // FLUX schnell سريع
            ],
        ];

        // HuggingFace returns binary image data
        $response = Http::withToken($this->apiKey)
            ->post($this->baseUrl . $this->model, $payload);

        if (!$response->successful()) {
            throw new \RuntimeException("API Error: {$response->status()}");
        }

        $imageData = base64_encode($response->body());

        return new ImageResponse(
            images: ['data:image/png;base64,' . $imageData]
        );
    }
}
```

---

## تسجيل المزودين

### في AIServiceProvider.php

```php
public function boot(): void
{
    // تسجيل Zhipu
    $this->app['prism-manager']->extend('zhipu', function ($app, $config) {
        return new ZhipuProvider(
            apiKey: $config['api_key'] ?? '',
        );
    });

    // تسجيل HuggingFace Model Families
    $huggingFaceProviders = [
        'huggingface-nllb' => NllbProvider::class,
        'huggingface-opus-mt' => OpusMtProvider::class,
        'huggingface-llama' => LlamaProvider::class,
        'huggingface-qwen' => QwenProvider::class,
        'huggingface-mistral' => MistralHFProvider::class,
        'huggingface-flux' => FluxProvider::class,
        'huggingface-sd' => SDProvider::class,
        'huggingface-sdxl' => SdxlProvider::class,
    ];

    foreach ($huggingFaceProviders as $name => $class) {
        $this->app['prism-manager']->extend($name, function ($app, $config) use ($class) {
            return new $class(
                apiKey: $config['api_key'] ?? '',
            );
        });
    }
}
```

---

## الملفات المطلوبة

### ملفات جديدة

```
src/
├── Prism/
│   └── Providers/
│       ├── ZhipuProvider.php
│       └── HuggingFace/
│           ├── BaseHuggingFaceProvider.php
│           ├── NllbProvider.php
│           ├── OpusMtProvider.php
│           ├── LlamaProvider.php
│           ├── QwenProvider.php
│           ├── MistralProvider.php
│           ├── FluxProvider.php
│           ├── SDProvider.php
│           └── SdxlProvider.php
```

### ملفات معدلة

```
src/
├── AIServiceProvider.php      # تسجيل المزودين الجدد
├── Models/SupportedModels.php # إزالة openrouter، تحديث huggingface
├── Services/ModelSyncService.php # إزالة البحث عن بعد
└── Providers/Adapters/PrismAdapter.php # دعم المزودين الجدد
```

---

## SupportedModels المحدث

```php
public static function getProviders(): array
{
    return [
        // Prism Built-in
        'openai' => 'OpenAI',
        'anthropic' => 'Anthropic',
        'google' => 'Google',
        'deepseek' => 'DeepSeek',
        'xai' => 'xAI (Grok)',
        'mistral' => 'Mistral',
        'ollama' => 'Ollama (Local)',

        // Custom Providers
        'zhipu' => 'Zhipu (GLM)',

        // HuggingFace Model Families
        'huggingface-nllb' => 'HuggingFace NLLB (Translation)',
        'huggingface-opus-mt' => 'HuggingFace Opus-MT (Translation)',
        'huggingface-llama' => 'HuggingFace Llama (Text)',
        'huggingface-qwen' => 'HuggingFace Qwen (Text)',
        'huggingface-mistral' => 'HuggingFace Mistral (Text)',
        'huggingface-flux' => 'HuggingFace FLUX (Image)',
        'huggingface-sd' => 'HuggingFace Stable Diffusion (Image)',
        'huggingface-sdxl' => 'HuggingFace SDXL (Image)',
    ];
}
```

---

## خريطة الموديلات (Model Mapping)

### كيفية تحديد المزود من اسم الموديل

```php
// في PrismAdapter
protected function mapProvider(string $provider, string $model): string
{
    // HuggingFace Model Families
    if (str_starts_with($model, 'facebook/nllb-')) {
        return 'huggingface-nllb';
    }
    if (str_starts_with($model, 'Helsinki-NLP/opus-mt-')) {
        return 'huggingface-opus-mt';
    }
    if (str_starts_with($model, 'meta-llama/Llama-')) {
        return 'huggingface-llama';
    }
    if (str_starts_with($model, 'Qwen/Qwen-')) {
        return 'huggingface-qwen';
    }
    if (str_starts_with($model, 'mistralai/Mistral-')) {
        return 'huggingface-mistral';
    }
    if (str_starts_with($model, 'black-forest-labs/FLUX-')) {
        return 'huggingface-flux';
    }
    if (str_starts_with($model, 'stabilityai/stable-diffusion-')) {
        return 'huggingface-sd';
    }
    if (str_starts_with($model, 'stabilityai/sdxl-')) {
        return 'huggingface-sdxl';
    }

    // باقي المزودين
    return $provider;
}
```

---

## خطوات التنفيذ

### المرحلة 1: البنية الأساسية (ساعة واحدة)
- [ ] إنشاء `src/Prism/Providers/` directory
- [ ] إنشاء `BaseHuggingFaceProvider.php`
- [ ] تحديث `AIServiceProvider.php` لتسجيل المزودين

### المرحلة 2: Zhipu Provider (30 دقيقة)
- [ ] إنشاء `ZhipuProvider.php`
- [ ] OpenAI-compatible API
- [ ] اختبار الاتصال

### المرحلة 3: HuggingFace Providers (3 ساعات)
- [ ] `NllbProvider.php` (Translation)
- [ ] `OpusMtProvider.php` (Translation)
- [ ] `LlamaProvider.php` (Text)
- [ ] `QwenProvider.php` (Text)
- [ ] `MistralProvider.php` (Text)
- [ ] `FluxProvider.php` (Image)
- [ ] `SDProvider.php` (Image)
- [ ] `SdxlProvider.php` (Image)

### المرحلة 4: التحديثات (ساعة واحدة)
- [ ] تحديث `SupportedModels.php`
- [ ] تحديث `PrismAdapter.php` لدعم model mapping
- [ ] تحديث `ModelSyncService.php` (إزالة remote sync)

### المرحلة 5: الاختبار (ساعة واحدة)
- [ ] اختبار Zhipu
- [ ] اختبار HuggingFace Translation (NLLB, Opus-MT)
- [ ] اختبار HuggingFace Text (Llama, Qwen)
- [ ] اختبار HuggingFace Image (FLUX, SD)

---

## الاستخدام

```php
// ترجمة مع NLLB
$response = Prism::text()
    ->using('huggingface-nllb', 'facebook/nllb-200-distilled-600M')
    ->withPrompt('Hello World')
    ->asText();

// توليد نص مع Llama
$response = Prism::text()
    ->using('huggingface-llama', 'meta-llama/Llama-3.1-8B-Instruct')
    ->withPrompt('Write a poem')
    ->asText();

// توليد صورة مع FLUX
$response = Prism::images()
    ->using('huggingface-flux', 'black-forest-labs/FLUX.1-schnell')
    ->withPrompt('A cat in space')
    ->asImages();

// استخدام Zhipu
$response = Prism::text()
    ->using('zhipu', 'glm-4')
    ->withPrompt('مرحبا بالعالم')
    ->asText();
```

---

## ملاحظات مهمة

1. **لا يوجد بحث عن بعد** - كل الموديلات تُضاف يدوياً
2. **HuggingFace ليس مزود واحد** - كل عائلة مزود منفصل
3. **PrismAdapter موحد** - يحدد المزود المناسب من اسم الموديل
4. **API Keys مشتركة** - كل مزودين HuggingFace يستخدمون نفس API Key
