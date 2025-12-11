<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'meta_description_ar',
        'meta_description_en',
        'meta_keywords_ar',
        'meta_keywords_en',
        'status',
        'order',
        // Hero Section
        'hero_title_ar',
        'hero_title_en',
        'hero_subtitle_ar',
        'hero_subtitle_en',
        'hero_image',
        // Story Section
        'story_title_ar',
        'story_title_en',
        'story_content_ar',
        'story_content_en',
        'story_image',
        // Values Section
        'values_title_ar',
        'values_title_en',
        // Value Cards
        'value1_title_ar', 'value1_title_en', 'value1_description_ar', 'value1_description_en', 'value1_icon',
        'value2_title_ar', 'value2_title_en', 'value2_description_ar', 'value2_description_en', 'value2_icon',
        'value3_title_ar', 'value3_title_en', 'value3_description_ar', 'value3_description_en', 'value3_icon',
        'value4_title_ar', 'value4_title_en', 'value4_description_ar', 'value4_description_en', 'value4_icon',
    ];

    protected $casts = [
        'status' => 'string',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
