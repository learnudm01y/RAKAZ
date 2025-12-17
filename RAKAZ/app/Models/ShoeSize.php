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

    /**
     * Get products with this shoe size
     */
    public function productShoeSizes()
    {
        return $this->hasMany(\App\Models\ProductShoeSize::class, 'shoe_size_id');
    }

    /**
     * Get products through pivot table
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_shoe_size')
                    ->withPivot('stock_quantity')
                    ->withTimestamps();
    }
}
