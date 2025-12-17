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

    /**
     * Get products with this size
     */
    public function productSizes()
    {
        return $this->hasMany(\App\Models\ProductSize::class, 'size_id');
    }

    /**
     * Get products through pivot table
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_size')
                    ->withPivot('stock_quantity')
                    ->withTimestamps();
    }
}
