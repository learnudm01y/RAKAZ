<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_translations',
        'sort_order',
        'product_count',
        'is_active',
    ];

    protected $casts = [
        'name_translations' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get translated name based on current locale
     */
    public function getTranslatedNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->name_translations[$locale] ?? $this->name;
    }
}
