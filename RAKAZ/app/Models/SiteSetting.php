<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'value_ar',
        'value_en',
        'group',
        'order'
    ];

    /**
     * Get value based on current locale
     */
    public function getValue()
    {
        $locale = session('locale', 'ar');
        return $locale === 'ar' ? $this->value_ar : $this->value_en;
    }

    /**
     * Get settings by group
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->orderBy('order')->get();
    }

    /**
     * Get single setting value
     */
    public static function getSetting($key)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->getValue() : null;
    }
}
