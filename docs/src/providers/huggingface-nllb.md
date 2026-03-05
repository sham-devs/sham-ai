# HuggingFace NLLB

تعلم كيفية إعداد واستخدام نماذج NLLB (No Language Left Behind) للترجمة من Meta عبر HuggingFace باستخدام Sham AI.

## خطوات الاستخدام

### 1. الحصول على مفتاح API من Hugging Face
1. اذهب إلى [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. أنشئ حسابًا إذا لم يكن لديك واحد
3. أنشئ **Access Token** جديد بصلاحيات **read**

### 2. كيفية إيجاد معرف النموذج (Model ID)
بشكل افتراضي، يستخدم Sham AI المحرك المقطر (distilled) بـ 600 مليون بارامتر، ولكن يمكنك استخدام نسخ أخرى.

إليك كيفية العثور على معرف الموديل:
1. اذهب إلى [HuggingFace Hub](https://huggingface.co/models?search=nllb).
2. اختر نسخة NLLB التي ترغب بها.
3. اضغط على أيقونة النسخ بجانب اسم الموديل.

**أمثلة لمعرفات الموديلات:**
- `facebook/nllb-200-distilled-600M` (الافتراضي والموصى به للسرعة)
- `facebook/nllb-200-1.3B` (جودة أعلى، يتطلب موارد أكثر)
