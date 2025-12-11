<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_title_ar',
        'hero_title_en',
        'hero_subtitle_ar',
        'hero_subtitle_en',
        'phone',
        'email',
        'address_ar',
        'address_en',
        'map_url',
        'working_hours_title_ar',
        'working_hours_title_en',
        'working_hours_ar',
        'working_hours_en',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'whatsapp',
        'additional_info_ar',
        'additional_info_en',
        'meta_description_ar',
        'meta_description_en',
        'meta_keywords_ar',
        'meta_keywords_en',
        'status',
    ];
}
