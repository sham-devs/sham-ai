# HuggingFace SDXL

تعلم كيفية إعداد واستخدام نماذج Stable Diffusion XL لتوليد الصور عبر HuggingFace باستخدام Sham AI.

## خطوات الاستخدام

### 1. الحصول على مفتاح API من Hugging Face
1. اذهب إلى [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. أنشئ حسابًا إذا لم يكن لديك واحد
3. أنشئ **Access Token** جديد بصلاحيات **read**

### 2. كيفية إيجاد معرف النموذج (Model ID)
عند إضافة نموذج SDXL مخصص، يجب استخدام **Model ID** بدقة (مثال: `stabilityai/stable-diffusion-xl-base-1.0`).

إليك كيفية العثور عليه:
1. اذهب إلى [HuggingFace Hub](https://huggingface.co/stabilityai).
2. ابحث عن إصدار SDXL الذي تريده.
3. اضغط على أيقونة النسخ بجانب اسم النموذج في أعلى الصفحة.

**أمثلة لمعرفات الموديلات:**
- `stabilityai/stable-diffusion-xl-base-1.0`
- `stabilityai/stable-diffusion-xl-refiner-1.0`
