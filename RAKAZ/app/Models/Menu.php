<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'image_title',
        'image_description',
        'link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'image_title' => 'array',
        'image_description' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function columns()
    {
        return $this->hasMany(MenuColumn::class)->orderBy('sort_order');
    }

    public function activeColumns()
    {
        return $this->hasMany(MenuColumn::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->name[$locale] ?? $this->name['ar'] ?? '';
    }

    public function getImageTitle($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->image_title[$locale] ?? $this->image_title['ar'] ?? '';
    }

    public function getImageDescription($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->image_description[$locale] ?? $this->image_description['ar'] ?? '';
    }
}
