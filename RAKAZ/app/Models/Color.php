<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hex_code',
        'sort_order',
        'product_count',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get translated name based on current locale
     */
    public function getTranslatedNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->name[$locale] ?? ($this->name['en'] ?? '');
    }
}
