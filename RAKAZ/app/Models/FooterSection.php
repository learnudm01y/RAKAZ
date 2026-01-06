<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'title' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع العناصر
     */
    public function items()
    {
        return $this->hasMany(FooterItem::class)->orderBy('sort_order');
    }

    /**
     * العناصر النشطة فقط
     */
    public function activeItems()
    {
        return $this->hasMany(FooterItem::class)->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * الحصول على العنوان حسب اللغة
     */
    public function getTitle($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $title = $this->title;

        if (is_string($title)) {
            $title = json_decode($title, true);
        }

        return $title[$locale] ?? $title['ar'] ?? '';
    }

    /**
     * الأقسام النشطة مرتبة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * الحصول على جميع الأقسام مع عناصرها للفوتر
     */
    public static function getFooterData()
    {
        return static::with('activeItems')
            ->active()
            ->ordered()
            ->get();
    }
}
