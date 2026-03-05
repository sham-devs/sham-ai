# HuggingFace Llama

تعلم كيفية إعداد واستخدام نماذج Llama الخاصة بشركة Meta عبر HuggingFace باستخدام Sham AI.

## خطوات الاستخدام

### 1. الحصول على مفتاح API من Hugging Face
1. اذهب إلى [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. أنشئ حسابًا إذا لم يكن لديك واحد
3. أنشئ **Access Token** جديد بصلاحيات **read**

### 2. كيفية إيجاد معرف النموذج (Model ID)
عند إضافة نموذج Llama مخصص، يجب استخدام **Model ID** بدقة (مثال: `meta-llama/Llama-3.1-8B-Instruct`).

إليك كيفية العثور عليه:
1. اذهب إلى [HuggingFace Hub](https://huggingface.co/meta-llama).
2. ابحث عن إصدار Llama الذي تريده. تأكد من اختيار نسخة `Instruct` أو `Chat`.
3. اضغط على أيقونة النسخ بجانب اسم النموذج في أعلى الصفحة.

**أمثلة لمعرفات الموديلات:**
- `meta-llama/Llama-3.2-3B-Instruct`
- `meta-llama/Llama-3.1-8B-Instruct`
- `meta-llama/Llama-3.1-70B-Instruct`

