<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot function
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name_en ?: $brand->name_ar);
            }
        });

        static::updating(function ($brand) {
            if (($brand->isDirty('name_en') || $brand->isDirty('name_ar')) && !$brand->isDirty('slug')) {
                $brand->slug = Str::slug($brand->name_en ?: $brand->name_ar);
            }
        });
    }

    /**
     * Get the brand name based on current locale
     */
    public function getName($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $locale === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get products with this brand
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

