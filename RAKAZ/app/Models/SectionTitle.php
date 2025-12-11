<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionTitle extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'title_ar',
        'title_en',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the title in the current locale
     */
    public function getTitle($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->{"title_{$locale}"} ?? $this->title_ar;
    }

    /**
     * Get section title by key
     */
    public static function getByKey($key, $locale = null)
    {
        $section = static::where('section_key', $key)
            ->where('active', true)
            ->first();

        return $section ? $section->getTitle($locale) : null;
    }
}
