<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'footer_section_id',
        'title',
        'link',
        'link_type',
        'route_name',
        'menu_id',
        'category_id',
        'page_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'title' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع القسم
     */
    public function section()
    {
        return $this->belongsTo(FooterSection::class, 'footer_section_id');
    }

    /**
     * العلاقة مع القائمة
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * العلاقة مع التصنيف
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * العلاقة مع الصفحة
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
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
     * الحصول على الرابط الفعلي
     */
    public function getUrl()
    {
        switch ($this->link_type) {
            case 'route':
                try {
                    return route($this->route_name);
                } catch (\Exception $e) {
                    return '#';
                }

            case 'menu':
                if ($this->menu) {
                    return route('all.menus', ['menu' => $this->menu->id]);
                }
                return '#';

            case 'category':
                if ($this->category) {
                    // استخدام getSlug() للحصول على slug اللغة الحالية
                    $slug = $this->category->getSlug();
                    return route('category.show', ['slug' => $slug]);
                }
                return '#';

            case 'page':
                if ($this->page) {
                    // التوجيه للصفحة بناءً على الـ slug
                    $slug = $this->page->slug;
                    if ($slug === 'home') {
                        return route('home');
                    } elseif ($slug === 'about-us') {
                        return route('about');
                    } elseif ($slug === 'contact-us') {
                        return route('contact');
                    } elseif ($slug === 'privacy-policy') {
                        return route('privacy.policy');
                    } else {
                        // صفحة عامة
                        return url('/' . $slug);
                    }
                }
                return '#';

            case 'custom':
            default:
                return $this->link ?? '#';
        }
    }

    /**
     * الحصول على اسم العرض للعنصر (للقوائم والتصنيفات)
     */
    public function getDisplayTitle($locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        // إذا كان له عنوان مخصص، استخدمه
        $customTitle = $this->getTitle($locale);
        if (!empty($customTitle)) {
            return $customTitle;
        }

        // وإلا، استخدم عنوان القائمة أو التصنيف أو الصفحة
        switch ($this->link_type) {
            case 'menu':
                if ($this->menu) {
                    return $this->menu->getName($locale);
                }
                break;

            case 'category':
                if ($this->category) {
                    $name = $this->category->name;
                    if (is_array($name)) {
                        return $name[$locale] ?? $name['ar'] ?? '';
                    }
                    return $name;
                }
                break;

            case 'page':
                if ($this->page) {
                    return $locale === 'ar' ? $this->page->title_ar : $this->page->title_en;
                }
                break;
        }

        return '';
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
