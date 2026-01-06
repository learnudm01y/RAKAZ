<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    /**
     * الحصول على قيمة إعداد معين
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $setting->value ?? $default;
    }

    /**
     * تعيين قيمة إعداد
     */
    public static function set($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * إعدادات الفوتر الافتراضية
     */
    public static function getDefaults()
    {
        return [
            'newsletter_title_ar' => 'اشترك في نشرتنا الإخبارية',
            'newsletter_title_en' => 'Subscribe to our newsletter',
            'newsletter_placeholder_ar' => 'أدخل عنوان بريدك الإلكتروني هنا',
            'newsletter_placeholder_en' => 'Enter your email address',
            'newsletter_button_ar' => 'اشترك',
            'newsletter_button_en' => 'Subscribe',
            'social_title_ar' => 'تابع ركاز',
            'social_title_en' => 'Follow Rakaz',
            'apps_title_ar' => 'تطبيقات ركاز',
            'apps_title_en' => 'Rakaz Apps',
            'customer_service_phone' => '800 717171',
            'whatsapp_number' => '+971 55 300 7879',
            'copyright_ar' => 'ركاز LLC. 2025. جميع الحقوق محفوظة',
            'copyright_en' => 'Rakaz LLC. 2025. All Rights Reserved',
            'show_newsletter' => true,
            'show_social_links' => true,
            'show_apps_section' => true,
            'show_contact_info' => true,
        ];
    }

    /**
     * الحصول على جميع الإعدادات
     */
    public static function getAllSettings()
    {
        $defaults = static::getDefaults();
        $dbSettings = static::all();

        $settings = $defaults;

        foreach ($dbSettings as $setting) {
            $value = $setting->value;
            // If value is an array with a single element, extract it
            if (is_array($value) && count($value) === 1) {
                $value = reset($value);
            }
            $settings[$setting->key] = $value;
        }

        return $settings;
    }
}
