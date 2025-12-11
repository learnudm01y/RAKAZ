<?php

$files = [
    'about' => 'I:/unit test/Rakaz/RAKAZ/resources/views/admin/about/edit.blade.php',
    'privacy' => 'I:/unit test/Rakaz/RAKAZ/resources/views/admin/privacy/edit.blade.php',
    'contact' => 'I:/unit test/Rakaz/RAKAZ/resources/views/admin/contact/edit.blade.php',
];

foreach ($files as $type => $file) {
    if (!file_exists($file)) {
        echo "File not found: $file\n";
        continue;
    }

    $content = file_get_contents($file);

    // Replace all <span class="ar-text">...</span><span class="en-text">...</span> patterns
    // with Laravel translation function

    // Pattern 1: In labels with two spans
    $content = preg_replace_callback(
        '/<label>\s*<span class="ar-text">(.*?)<\/span>\s*<span class="en-text">(.*?)<\/span>\s*<\/label>/s',
        function($matches) {
            // Try to map Arabic text to translation key
            $arText = trim($matches[1]);
            $key = mapToTranslationKey($arText);
            return '<label>{{ __(\'' . $key . '\') }}</label>';
        },
        $content
    );

    // Pattern 2: In section titles with icons
    $content = preg_replace_callback(
        '/<div class="form-section-title">\s*<span class="ar-text">(.*?)<\/span>\s*<span class="en-text">(.*?)<\/span>\s*<\/div>/s',
        function($matches) {
            $arText = trim($matches[1]);
            // Extract icon if present
            if (preg_match('/<i class="[^"]*"><\/i>\s*(.*)/', $arText, $iconMatch)) {
                $icon = preg_match('/<i class="([^"]*)"><\/i>/', $arText, $i) ? $i[0] : '';
                $text = trim($iconMatch[1]);
                $key = mapToTranslationKey($text);
                return '<div class="form-section-title">' . $icon . ' {{ __(\''. $key .'\') }}</div>';
            }
            $key = mapToTranslationKey($arText);
            return '<div class="form-section-title">{{ __(\'' . $key . '\') }}</div>';
        },
        $content
    );

    // Pattern 3: In buttons and other elements
    $content = preg_replace_callback(
        '/<span class="ar-text">(.*?)<\/span>\s*<span class="en-text">(.*?)<\/span>/s',
        function($matches) {
            $arText = trim(strip_tags($matches[1]));
            $enText = trim(strip_tags($matches[2]));

            // Map to translation key
            $key = mapTextToKey($arText, $enText);
            return '{{ __(\'' . $key . '\') }}';
        },
        $content
    );

    file_put_contents($file, $content);
    echo "Processed: $file\n";
}

function mapToTranslationKey($text) {
    $map = [
        'عنوان البانر' => 'admin.about.hero_title',
        'نص البانر الفرعي' => 'admin.about.hero_subtitle',
        'عنوان القصة' => 'admin.about.story_title',
        'محتوى القصة' => 'admin.about.story_content',
        'عنوان قسم القيم' => 'admin.about.values_title',
        'العنوان' => 'admin.about.value_title',
        'الوصف' => 'admin.about.value_description',
        'الأيقونة' => 'admin.about.value_icon',
        'قسم البانر الرئيسي' => 'admin.about.hero_section',
        'قسم القصة' => 'admin.about.story_section',
        'قسم القيم' => 'admin.about.values_section',
        'قسم الإحصائيات' => 'admin.about.statistics_section',

        // Privacy
        'عنوان الصفحة' => 'admin.privacy.hero_title',
        'النص الفرعي' => 'admin.privacy.hero_subtitle',
        'محتوى سياسة الخصوصية' => 'admin.privacy.content',
        'قسم البانر' => 'admin.privacy.hero_section',
        'المحتوى الرئيسي' => 'admin.privacy.content_section',

        // Contact
        'رقم الهاتف' => 'admin.contact.phone',
        'البريد الإلكتروني' => 'admin.contact.email',
        'العنوان' => 'admin.contact.address',
        'واتساب' => 'admin.contact.whatsapp',
        'معلومات التواصل' => 'admin.contact.info_section',
        'روابط التواصل الاجتماعي' => 'admin.contact.social_section',

        // Common
        'حفظ التغييرات' => 'admin.save',
        'معاينة' => 'admin.preview',
        'إغلاق' => 'admin.close',
        'الحالة' => 'admin.status',
        'نشط' => 'admin.active',
        'غير نشط' => 'admin.inactive',
    ];

    $text = trim(strip_tags($text));
    return $map[$text] ?? 'admin.label_' . md5($text);
}

function mapTextToKey($arText, $enText) {
    // Similar mapping function
    return mapToTranslationKey($arText);
}

echo "All files processed successfully!\n";
