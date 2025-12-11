<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscoverItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'link',
        'title',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'title' => 'array',
        'active' => 'boolean',
    ];

    /**
     * Get the title in a specific locale
     */
    public function getTitle($locale = 'ar')
    {
        return $this->title[$locale] ?? $this->title['ar'] ?? '';
    }

    /**
     * Scope for active items
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope for ordered items
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
