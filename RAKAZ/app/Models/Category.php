<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
        'description' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get all products in this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all descendants (children, grandchildren, etc.)
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get only root categories (no parent).
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get only categories that have products.
     */
    public function scopeWithProducts($query)
    {
        return $query->whereHas('products', function ($q) {
            $q->where('is_active', true);
        });
    }

    /**
     * Get categories ordered by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the category name in the specified locale.
     */
    public function getName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'ar') {
            return $this->name['ar'] ?? $this->name['en'] ?? '';
        }

        return $this->name[$locale] ?? $this->name['en'] ?? '';
    }

    /**
     * Get the category slug in the specified locale.
     */
    public function getSlug($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'ar') {
            return $this->slug['ar'] ?? $this->slug['en'] ?? '';
        }

        return $this->slug[$locale] ?? $this->slug['en'] ?? '';
    }

    /**
     * Get the category description in the specified locale.
     */
    public function getDescription($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'ar') {
            return $this->description['ar'] ?? $this->description['en'] ?? '';
        }

        return $this->description[$locale] ?? $this->description['en'] ?? '';
    }

    /**
     * Check if category has children.
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get the category level (0 = root, 1 = sub, 2 = sub-sub).
     */
    public function getLevel()
    {
        $level = 0;
        $parent = $this->parent;

        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }

        return $level;
    }

    /**
     * Get breadcrumb trail.
     */
    public function getBreadcrumb($locale = null)
    {
        $breadcrumb = collect([$this->getName($locale)]);
        $parent = $this->parent;

        while ($parent) {
            $breadcrumb->prepend($parent->getName($locale));
            $parent = $parent->parent;
        }

        return $breadcrumb;
    }
}
