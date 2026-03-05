# HuggingFace Stable Diffusion

تعلم كيفية إعداد واستخدام نماذج Stable Diffusion لتوليد الصور عبر HuggingFace باستخدام Sham AI.

## خطوات الاستخدام

### 1. الحصول على مفتاح API من Hugging Face
1. اذهب إلى [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. أنشئ حسابًا إذا لم يكن لديك واحد
3. أنشئ **Access Token** جديد بصلاحيات **read**

### 2. كيفية إيجاد معرف النموذج (Model ID)
عند إضافة نموذج Stable Diffusion مخصص، يجب استخدام **Model ID** بدقة (مثال: `stable-diffusion-v1-5/stable-diffusion-v1-5`).

إليك كيفية العثور عليه:
1. اذهب إلى [HuggingFace Hub](https://huggingface.co/models?search=stable-diffusion).
2. ابحث عن إصدار Stable Diffusion الذي تريده (v1.5, v2.1, إلخ).
3. اضغط على أيقونة النسخ بجانب اسم النموذج في أعلى الصفحة.

**أمثلة لمعرفات الموديلات:**
- `stable-diffusion-v1-5/stable-diffusion-v1-5`
- `stabilityai/stable-diffusion-2-1`
