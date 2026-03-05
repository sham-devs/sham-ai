# HuggingFace FLUX

تعلم كيفية إعداد واستخدام نماذج FLUX لتوليد الصور من شركة Black Forest Labs عبر HuggingFace باستخدام Sham AI.

## خطوات الاستخدام

### 1. الحصول على مفتاح API من Hugging Face
1. اذهب إلى [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. أنشئ حسابًا إذا لم يكن لديك واحد
3. أنشئ **Access Token** جديد بصلاحيات **read**

### 2. كيفية إيجاد معرف النموذج (Model ID)
عند إضافة نموذج FLUX مخصص، يجب استخدام **Model ID** بدقة (مثال: `black-forest-labs/FLUX.1-schnell`).

إليك كيفية العثور عليه:
1. اذهب إلى [HuggingFace Hub](https://huggingface.co/black-forest-labs).
2. ابحث عن إصدار FLUX الذي تريده (schnell, dev, إلخ).
3. اضغط على أيقونة النسخ بجانب اسم النموذج في أعلى الصفحة.

**أمثلة لمعرفات الموديلات:**
- `black-forest-labs/FLUX.1-schnell` (الإصدار الأسرع)
- `black-forest-labs/FLUX.1-dev` (جودة أعلى، ولكن قد يتطلب صلاحيات وصول خاصة)
