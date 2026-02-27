<?php

declare(strict_types=1);

return array (
  'messages' => 
  array (
    'test_connection' => 'اختبار الاتصال',
  ),
  'settings' => 
  array (
    'tab' => 
    array (
      'label' => 'ترجمة الذكاء الاصطناعي',
      'title' => 'إعدادات الذكاء الاصطناعي',
      'description' => 'إعداد مزودي الذكاء الاصطناعي واستخداماتها.',
    ),
    'field' => 
    array (
      'enabled' => 
      array (
        'label' => 'تفعيل الذكاء الاصطناعي',
        'desc' => 'المفتاح الرئيسي لجميع وظائف الذكاء الاصطناعي.',
      ),
      'provider' => 
      array (
        'label' => 'مزود الخدمة',
        'desc' => 'اختر مزود خدمة الذكاء الاصطناعي.',
      ),
      'model' => 
      array (
        'label' => 'الموديل',
        'desc' => 'حدد النموذج المطلوب استخدامه.',
      ),
      'api_key' => 
      array (
        'label' => 'مفتاح API',
        'desc' => 'مفتاح API للمزود المختار.',
      ),
      'temperature' => 
      array (
        'label' => 'درجة العشوائية (Temperature)',
        'desc' => 'يتحكم في العشوائية: 0 محدد، 1 إبداعي.',
      ),
    ),
    'action' => 
    array (
      'save_section' => 'حفظ الإعدادات',
      'reset_defaults' => 'إعادة الضبط',
      'confirm_reset' => 'هل أنت متأكد من إعادة جميع إعدادات الذكاء الاصطناعي إلى قيمها الافتراضية؟',
    ),
  ),
);
