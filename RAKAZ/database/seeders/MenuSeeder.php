<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuColumn;
use App\Models\MenuColumnItem;
use App\Models\Category;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Create sample menu 1: Kandoras
        $menu1 = Menu::create([
            'name' => [
                'ar' => 'الكنادير',
                'en' => 'KANDORAS',
            ],
            'image_title' => [
                'ar' => 'تشكيلة الكنادير الجديدة',
                'en' => 'New Kandoras Collection',
            ],
            'image_description' => [
                'ar' => 'اكتشف أحدث تصاميم الكنادير',
                'en' => 'Discover the latest kandoras designs',
            ],
            'link' => null,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        // Column 1: Types
        $column1 = $menu1->columns()->create([
            'title' => [
                'ar' => 'أنواع الكنادير',
                'en' => 'TYPES',
            ],
            'sort_order' => 0,
            'is_active' => true,
        ]);

        // Column 2: Occasions
        $column2 = $menu1->columns()->create([
            'title' => [
                'ar' => 'المناسبات',
                'en' => 'OCCASIONS',
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Column 3: Materials
        $column3 = $menu1->columns()->create([
            'title' => [
                'ar' => 'الخامات',
                'en' => 'MATERIALS',
            ],
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Add items to columns (custom links as examples)
        $column1->items()->create([
            'custom_name' => [
                'ar' => 'كنادير تقليدية',
                'en' => 'Traditional Kandoras',
            ],
            'custom_link' => '/shop/traditional-kandoras',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $column1->items()->create([
            'custom_name' => [
                'ar' => 'كنادير عصرية',
                'en' => 'Modern Kandoras',
            ],
            'custom_link' => '/shop/modern-kandoras',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $column2->items()->create([
            'custom_name' => [
                'ar' => 'كنادير زفاف',
                'en' => 'Wedding Kandoras',
            ],
            'custom_link' => '/shop/wedding-kandoras',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $column2->items()->create([
            'custom_name' => [
                'ar' => 'كنادير يومية',
                'en' => 'Daily Kandoras',
            ],
            'custom_link' => '/shop/daily-kandoras',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $column3->items()->create([
            'custom_name' => [
                'ar' => 'قطن',
                'en' => 'Cotton',
            ],
            'custom_link' => '/shop/cotton-kandoras',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        // Create sample menu 2: Accessories
        $menu2 = Menu::create([
            'name' => [
                'ar' => 'الاكسسوارات',
                'en' => 'ACCESSORIES',
            ],
            'link' => '/shop/accessories',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Create sample menu 3: Sale
        $menu3 = Menu::create([
            'name' => [
                'ar' => 'التخفيضات',
                'en' => 'SALE',
            ],
            'link' => '/shop/sale',
            'sort_order' => 2,
            'is_active' => true,
        ]);
    }
}
