# مزودو الخدمة (Providers)

Sham AI يتكامل مع مجموعة واسعة من المزودين من خلال Prism. اتبع التعليمات أدناه لإعداد واستخدام النماذج.

## كيف يعمل النظام
النظام لا يستخدم "اشتراك" بالمعنى التقليدي، بل يعتمد على إضافة نماذج AI مع مفتاح API خاص بك.

## مزودو Hugging Face المتاحون
| المزود | الاستخدام |
| :--- | :--- |
| **huggingface-nllb** | الترجمة (نماذج NLLB) |
| **huggingface-opus-mt** | الترجمة (نماذج Opus-MT) |
| **huggingface-llama** | توليد النصوص |
| **huggingface-qwen** | توليد النصوص |
| **huggingface-mistral** | توليد النصوص |
| **huggingface-flux** | توليد الصور |
| **huggingface-sd** | توليد الصور (Stable Diffusion) |
| **huggingface-sdxl** | توليد الصور (SDXL) |

## خطوات الاستخدام

### 1. الحصول على مفتاح API من Hugging Face
1. اذهب إلى [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. أنشئ حسابًا إذا لم يكن لديك واحد
3. أنشئ **Access Token** جديد بصلاحيات **read**

### 2. إضافة النموذج عبر إعدادات النظام
من خلال واجهة الإعدادات في النظام، يمكنك:
- إضافة نموذج جديد
- اختيار المزود (مثل `huggingface-llama`)
- إدخال مفتاح API
- تحديد اسم النموذج الفعلي (مثل `meta-llama/Llama-3.2-3B-Instruct`)

### 3. استخدام النموذج برمجيًا

```php
use Sham\AI\AIService;

// الترجمة
$response = app(AIService::class)->translate(
    ['Hello World'],
    'en',
    'ar'
);

// توليد الصور (باستخدام flux/sd/sdxl)
$response = app(AIService::class)->generateImage([
    'prompt' => 'A beautiful sunset over mountains',
    'provider' => 'huggingface-flux',
    'model' => 'black-forest-labs/FLUX.1-schnell'
]);
```

## ملاحظات مهمة
- **النماذج المجانية vs المدفوعة**: بعض نماذج Hugging Face مجانية والبعض يتطلب اشتراك Pro.
- **النماذج المقيدة (Gated)**: بعض النماذج مثل Llama تتطلب طلب وصول وموافقة.
- **الحدود**: الحسابات المجانية لها حدود على عدد الطلبات.

---

## مزودون آخرون
- [OpenAI](/providers/openai)
- [Anthropic](/providers/anthropic)
- [Google](/providers/google)
- [xAI](/providers/xai)
- [Mistral](/providers/mistral)
- [Zhipu](/providers/zhipu)
- [Ollama](/providers/ollama)
- [DeepSeek](/providers/deepseek)
