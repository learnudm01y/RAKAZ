<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedSection extends Model
{
    use HasFactory;

    protected $table = 'featured_section';

    protected $fillable = [
        'title',
        'link_url',
        'link_text',
        'is_active',
    ];

    protected $casts = [
        'title' => 'array',
        'link_text' => 'array',
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'featured_section_products')
            ->withPivot('order')
            ->orderBy('featured_section_products.order');
    }

    public function getTitle($locale = 'ar')
    {
        return $this->title[$locale] ?? $this->title['ar'] ?? '';
    }

    public function getLinkText($locale = 'ar')
    {
        return $this->link_text[$locale] ?? $this->link_text['ar'] ?? '';
    }
}
