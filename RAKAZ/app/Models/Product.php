<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sizing_info',
        'design_details',
        'category_id',
        'brand_id',
        'price',
        'sale_price',
        'cost',
        'sku',
        'stock_quantity',
        'manage_stock',
        'stock_status',
        'low_stock_threshold',
        'main_image',
        'hover_image',
        'gallery_images',
        'weight',
        'dimensions',
        'colors',
        'sizes',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'is_featured',
        'is_new',
        'is_on_sale',
        'sort_order',
        'tags',
        'specifications',
        'brand',
        'manufacturer',
        'available_from',
        'available_until',
        'views_count',
        'sales_count',
        'rating_average',
        'rating_count',
    ];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
        'description' => 'array',
        'sizing_info' => 'array',
        'design_details' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'gallery_images' => 'array',
        'weight' => 'array',
        'dimensions' => 'array',
        'colors' => 'array',
        'sizes' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'meta_keywords' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_on_sale' => 'boolean',
        'manage_stock' => 'boolean',
        'tags' => 'array',
        'specifications' => 'array',
        'available_from' => 'date',
        'available_until' => 'date',
        'rating_average' => 'decimal:2',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that owns the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the sizes for the product (many-to-many relationship)
     */
    public function productSizes()
    {
        return $this->belongsToMany(Size::class, 'product_size')
                    ->withPivot('stock_quantity')
                    ->withTimestamps();
    }

    /**
     * Get the shoe sizes for the product (many-to-many relationship)
     */
    public function productShoeSizes()
    {
        return $this->belongsToMany(ShoeSize::class, 'product_shoe_size')
                    ->withPivot('stock_quantity')
                    ->withTimestamps();
    }

    /**
     * Get the colors for the product (many-to-many relationship)
     */
    public function productColors()
    {
        return $this->belongsToMany(Color::class, 'color_product')
                    ->withPivot('stock_quantity')
                    ->withTimestamps();
    }

    /**
     * Get color images for the product
     */
    public function colorImages()
    {
        return $this->hasMany(ProductColorImage::class)->ordered();
    }

    /**
     * Get images for a specific color
     */
    public function getColorImages($colorId)
    {
        return $this->colorImages()->where('color_id', $colorId)->get();
    }

    /**
     * Get primary image for a specific color
     */
    public function getColorPrimaryImage($colorId)
    {
        return $this->colorImages()
            ->where('color_id', $colorId)
            ->where('is_primary', true)
            ->first();
    }

    /**
     * Get name by locale
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
     * Get slug by locale
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
     * Get description by locale
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
     * Get sizing info by locale
     */
    public function getSizingInfo($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'ar') {
            return $this->sizing_info['ar'] ?? $this->sizing_info['en'] ?? '';
        }

        return $this->sizing_info[$locale] ?? $this->sizing_info['en'] ?? '';
    }

    /**
     * Get design details by locale
     */
    public function getDesignDetails($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'ar') {
            return $this->design_details['ar'] ?? $this->design_details['en'] ?? '';
        }

        return $this->design_details[$locale] ?? $this->design_details['en'] ?? '';
    }

    /**
     * Get the final price (sale price if available, otherwise regular price)
     */
    public function getFinalPrice()
    {
        return $this->sale_price && $this->sale_price < $this->price
            ? $this->sale_price
            : $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentage()
    {
        if (!$this->sale_price || $this->sale_price >= $this->price) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Check if product has discount
     */
    public function hasDiscount()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        if (!$this->manage_stock) {
            return true;
        }

        return $this->stock_quantity > 0 && $this->stock_status === 'in_stock';
    }

    /**
     * Check if stock is low
     */
    public function isLowStock()
    {
        if (!$this->manage_stock || !$this->low_stock_threshold) {
            return false;
        }

        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for new products
     */
    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    /**
     * Scope for products on sale
     */
    public function scopeOnSale($query)
    {
        return $query->where('is_on_sale', true);
    }

    /**
     * Scope for products in stock
     */
    public function scopeInStock($query)
    {
        return $query->where(function($q) {
            $q->where('manage_stock', false)
              ->orWhere(function($q2) {
                  $q2->where('stock_quantity', '>', 0)
                     ->where('stock_status', 'in_stock');
              });
        });
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->whereRaw("JSON_EXTRACT(name, '$.ar') LIKE ?", ["%{$search}%"])
              ->orWhereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$search}%"])
              ->orWhere('sku', 'LIKE', "%{$search}%")
              ->orWhere('brand', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment sales count
     */
    public function incrementSales($quantity = 1)
    {
        $this->increment('sales_count', $quantity);
    }

    /**
     * Decrease stock quantity
     */
    public function decreaseStock($quantity = 1)
    {
        if ($this->manage_stock) {
            $this->decrement('stock_quantity', $quantity);

            // Update stock status if out of stock
            if ($this->stock_quantity <= 0) {
                $this->update(['stock_status' => 'out_of_stock']);
            }
        }
    }

    /**
     * Increase stock quantity
     */
    public function increaseStock($quantity = 1)
    {
        if ($this->manage_stock) {
            $this->increment('stock_quantity', $quantity);

            // Update stock status to in_stock if it was out of stock
            if ($this->stock_status === 'out_of_stock' && $this->stock_quantity > 0) {
                $this->update(['stock_status' => 'in_stock']);
            }
        }
    }
}
