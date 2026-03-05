# HuggingFace Qwen

تعلم كيفية إعداد واستخدام نماذج Qwen لتوليد النصوص من Alibaba Cloud عبر HuggingFace باستخدام Sham AI.

## خطوات الاستخدام

### 1. الحصول على مفتاح API من Hugging Face
1. اذهب إلى [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. أنشئ حسابًا إذا لم يكن لديك واحد
3. أنشئ **Access Token** جديد بصلاحيات **read**

### 2. كيفية إيجاد معرف النموذج (Model ID)
عند إضافة نموذج Qwen مخصص، يجب استخدام **Model ID** بدقة (مثال: `Qwen/Qwen2.5-72B-Instruct`).

إليك كيفية العثور عليه:
1. اذهب إلى [HuggingFace Hub](https://huggingface.co/Qwen).
2. ابحث عن إصدار Qwen الذي تريده. تأكد من اختيار نسخة `Instruct` أو `Chat`.
3. اضغط على أيقونة النسخ بجانب اسم النموذج في أعلى الصفحة.

**أمثلة لمعرفات الموديلات:**
- `Qwen/Qwen2.5-72B-Instruct`
- `Qwen/Qwen2.5-7B-Instruct`
