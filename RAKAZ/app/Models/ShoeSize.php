<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoeSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'size',
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
        if ($this->name_translations && isset($this->name_translations[$locale])) {
            return $this->name_translations[$locale];
        }
        return $this->size;
    }
}
