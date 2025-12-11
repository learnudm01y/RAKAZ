<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivacyPolicyPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_title_ar',
        'hero_title_en',
        'hero_subtitle_ar',
        'hero_subtitle_en',
        'content_ar',
        'content_en',
        'section_1_title_ar',
        'section_1_title_en',
        'section_1_content_ar',
        'section_1_content_en',
        'section_2_title_ar',
        'section_2_title_en',
        'section_2_content_ar',
        'section_2_content_en',
        'section_3_title_ar',
        'section_3_title_en',
        'section_3_content_ar',
        'section_3_content_en',
        'section_4_title_ar',
        'section_4_title_en',
        'section_4_content_ar',
        'section_4_content_en',
        'meta_description_ar',
        'meta_description_en',
        'meta_keywords_ar',
        'meta_keywords_en',
        'status',
    ];
}
