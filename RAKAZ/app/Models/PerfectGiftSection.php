<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfectGiftSection extends Model
{
    use HasFactory;

    protected $table = 'perfect_gift_section';

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
        return $this->belongsToMany(Product::class, 'perfect_gift_section_products')
            ->withPivot('order')
            ->orderBy('perfect_gift_section_products.order');
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
