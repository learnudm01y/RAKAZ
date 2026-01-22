<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_slides',
        'hero_slides_tablet',
        'hero_slides_mobile',
        'cyber_sale_image',
        'cyber_sale_image_tablet',
        'cyber_sale_image_mobile',
        'cyber_sale_link',
        'cyber_sale_title',
        'cyber_sale_button_text',
        'cyber_sale_active',
        'gifts_section_title',
        'gifts_items',
        'gifts_section_active',
        'dg_banner_image',
        'dg_banner_image_tablet',
        'dg_banner_image_mobile',
        'dg_banner_link',
        'dg_banner_active',
        'gucci_spotlight_image',
        'gucci_spotlight_image_tablet',
        'gucci_spotlight_image_mobile',
        'gucci_spotlight_link',
        'gucci_spotlight_active',
        'featured_banner_image',
        'featured_banner_image_tablet',
        'featured_banner_image_mobile',
        'featured_banner_title',
        'featured_banner_subtitle',
        'featured_banner_link',
        'featured_banner_button_text',
        'featured_banner_active',
        'must_have_section_title',
        'must_have_section_active',
        'spotlight_banner_image',
        'spotlight_banner_title',
        'spotlight_banner_subtitle',
        'spotlight_banner_link',
        'spotlight_banner_button_text',
        'spotlight_banner_active',
        'discover_section_title',
        'discover_items',
        'discover_section_active',
        'perfect_present_section_title',
        'perfect_present_section_active',
        'membership_title',
        'membership_desc',
        'membership_link',
        'membership_image',
        'membership_section_active',
        'app_download_title',
        'app_download_subtitle',
        'app_image',
        'app_store_link',
        'google_play_link',
        'app_section_active',
        'locale',
        'is_active',
    ];

    protected $casts = [
        'hero_slides' => 'array',
        'hero_slides_tablet' => 'array',
        'hero_slides_mobile' => 'array',
        'cyber_sale_title' => 'array',
        'cyber_sale_button_text' => 'array',
        'cyber_sale_active' => 'boolean',
        'gifts_section_title' => 'array',
        'gifts_items' => 'array',
        'gifts_section_active' => 'boolean',
        'dg_banner_image' => 'array',
        'dg_banner_image_tablet' => 'array',
        'dg_banner_image_mobile' => 'array',
        'dg_banner_active' => 'boolean',
        'gucci_spotlight_image' => 'array',
        'gucci_spotlight_image_tablet' => 'array',
        'gucci_spotlight_image_mobile' => 'array',
        'gucci_spotlight_active' => 'boolean',
        'featured_banner_title' => 'array',
        'featured_banner_subtitle' => 'array',
        'featured_banner_button_text' => 'array',
        'featured_banner_active' => 'boolean',
        'must_have_section_title' => 'array',
        'must_have_section_active' => 'boolean',
        'spotlight_banner_title' => 'array',
        'spotlight_banner_subtitle' => 'array',
        'spotlight_banner_button_text' => 'array',
        'spotlight_banner_active' => 'boolean',
        'discover_section_title' => 'array',
        'discover_items' => 'array',
        'discover_section_active' => 'boolean',
        'perfect_present_section_title' => 'array',
        'perfect_present_section_active' => 'boolean',
        'membership_title' => 'array',
        'membership_desc' => 'array',
        'membership_section_active' => 'boolean',
        'app_download_title' => 'array',
        'app_download_subtitle' => 'array',
        'app_image' => 'array',
        'app_section_active' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get translated field value based on current locale
     */
    public function getTranslation($field)
    {
        $locale = app()->getLocale();
        $value = $this->$field;

        if (is_array($value)) {
            if (isset($value[$locale]) && $value[$locale] !== null) {
                return $value[$locale];
            }
            // Return fallback locale if current is null
            $fallbackLocale = $locale === 'ar' ? 'en' : 'ar';
            if (isset($value[$fallbackLocale]) && $value[$fallbackLocale] !== null) {
                return $value[$fallbackLocale];
            }
            // Return empty string if both are null
            return '';
        }

        return $value ?? '';
    }

    /**
     * Get active home page for current locale
     */
    public static function getActive()
    {
        // First try to get by current locale
        $page = static::where('locale', app()->getLocale())
            ->where('is_active', true)
            ->first();

        if ($page) {
            return $page;
        }

        // Then try Arabic as fallback
        $page = static::where('locale', 'ar')
            ->where('is_active', true)
            ->first();

        if ($page) {
            return $page;
        }

        // Finally, just get any active home page
        return static::where('is_active', true)->first();
    }
}
