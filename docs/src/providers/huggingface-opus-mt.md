# HuggingFace Opus-MT

تعلم كيفية إعداد واستخدام نماذج Opus-MT للترجمة عبر HuggingFace باستخدام Sham AI.

## خطوات الاستخدام

### 1. الحصول على مفتاح API من Hugging Face
1. اذهب إلى [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. أنشئ حسابًا إذا لم يكن لديك واحد
3. أنشئ **Access Token** جديد بصلاحيات **read**

### 2. كيفية إيجاد معرف النموذج (Model ID)
تعتمد نماذج Opus-MT على زوج اللغات (من-إلى).

إليك كيفية العثور على معرف الموديل:
1. اذهب إلى [HuggingFace Hub](https://huggingface.co/Helsinki-NLP).
2. ابحث عن الزوج اللغوي الذي تحتاجه (مثلاً: `opus-mt-en-ar` للترجمة من الإنجليزية للعربية).
3. اضغط على أيقونة النسخ بجانب اسم الموديل.

**أمثلة لمعرفات الموديلات:**
- `Helsinki-NLP/opus-mt-en-ar` (من الإنجليزية إلى العربية)
- `Helsinki-NLP/opus-mt-ar-en` (من العربية إلى الإنجليزية)
