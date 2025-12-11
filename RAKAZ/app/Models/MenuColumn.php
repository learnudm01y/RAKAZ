<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'title',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'title' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function items()
    {
        return $this->hasMany(MenuColumnItem::class)->orderBy('sort_order');
    }

    public function activeItems()
    {
        return $this->hasMany(MenuColumnItem::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function getTitle($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->title[$locale] ?? $this->title['ar'] ?? '';
    }
}
