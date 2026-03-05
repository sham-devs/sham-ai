# HuggingFace Mistral

تعلم كيفية إعداد واستخدام نماذج Mistral لتوليد النصوص عبر HuggingFace باستخدام Sham AI.

## خطوات الاستخدام

### 1. الحصول على مفتاح API من Hugging Face
1. اذهب إلى [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. أنشئ حسابًا إذا لم يكن لديك واحد
3. أنشئ **Access Token** جديد بصلاحيات **read**

### 2. كيفية إيجاد معرف النموذج (Model ID)
عند إضافة نموذج Mistral مخصص، يجب استخدام **Model ID** بدقة (مثال: `mistralai/Mistral-7B-Instruct-v0.3`).

إليك كيفية العثور عليه:
1. اذهب إلى [HuggingFace Hub](https://huggingface.co/mistralai).
2. ابحث عن إصدار Mistral الذي تريده. تأكد من اختيار نسخة `Instruct` أو `Chat`.
3. اضغط على أيقونة النسخ بجانب اسم النموذج في أعلى الصفحة.

**أمثلة لمعرفات الموديلات:**
- `mistralai/Mistral-7B-Instruct-v0.3`
- `mistralai/Mixtral-8x7B-Instruct-v0.1`
