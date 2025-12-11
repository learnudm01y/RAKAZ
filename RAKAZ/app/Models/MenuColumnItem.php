<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuColumnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_column_id',
        'category_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function column()
    {
        return $this->belongsTo(MenuColumn::class, 'menu_column_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        // استخدم اسم التصنيف
        if ($this->category) {
            return $this->category->getName($locale);
        }

        return '';
    }

    public function getLink()
    {
        // استخدم رابط التصنيف
        if ($this->category) {
            $slug = $this->category->getSlug(app()->getLocale());
            return url('/shop/category/' . $slug);
        }

        return '#';
    }
}
