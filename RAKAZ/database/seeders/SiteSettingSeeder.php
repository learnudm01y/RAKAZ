<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Stats settings
            [
                'key' => 'branches_count',
                'type' => 'number',
                'value_ar' => '10',
                'value_en' => '10',
                'group' => 'stats',
                'order' => 1
            ],
            [
                'key' => 'products_count',
                'type' => 'number',
                'value_ar' => '500',
                'value_en' => '500',
                'group' => 'stats',
                'order' => 2
            ],
            [
                'key' => 'customers_count',
                'type' => 'number',
                'value_ar' => '50000',
                'value_en' => '50000',
                'group' => 'stats',
                'order' => 3
            ],
            [
                'key' => 'years_experience',
                'type' => 'number',
                'value_ar' => '15',
                'value_en' => '15',
                'group' => 'stats',
                'order' => 4
            ],

            // Stats Labels
            [
                'key' => 'branches_label',
                'type' => 'text',
                'value_ar' => 'فرع',
                'value_en' => 'Branches',
                'group' => 'stats',
                'order' => 5
            ],
            [
                'key' => 'products_label',
                'type' => 'text',
                'value_ar' => 'منتج',
                'value_en' => 'Products',
                'group' => 'stats',
                'order' => 6
            ],
            [
                'key' => 'customers_label',
                'type' => 'text',
                'value_ar' => 'عميل سعيد',
                'value_en' => 'Happy Customers',
                'group' => 'stats',
                'order' => 7
            ],
            [
                'key' => 'years_label',
                'type' => 'text',
                'value_ar' => 'سنة خبرة',
                'value_en' => 'Years Experience',
                'group' => 'stats',
                'order' => 8
            ],

            // Services
            [
                'key' => 'service_1_icon',
                'type' => 'text',
                'value_ar' => 'fa-solid fa-headset',
                'value_en' => 'fa-solid fa-headset',
                'group' => 'services',
                'order' => 1
            ],
            [
                'key' => 'service_1_title',
                'type' => 'text',
                'value_ar' => 'خدمة ما بعد البيع',
                'value_en' => 'After Sales Service',
                'group' => 'services',
                'order' => 2
            ],
            [
                'key' => 'service_1_desc',
                'type' => 'textarea',
                'value_ar' => 'نوفر دعم فني متميز بعد البيع',
                'value_en' => 'We provide excellent technical support after sales',
                'group' => 'services',
                'order' => 3
            ],

            [
                'key' => 'service_2_icon',
                'type' => 'text',
                'value_ar' => 'fa-solid fa-truck-fast',
                'value_en' => 'fa-solid fa-truck-fast',
                'group' => 'services',
                'order' => 4
            ],
            [
                'key' => 'service_2_title',
                'type' => 'text',
                'value_ar' => 'توصيل سريع',
                'value_en' => 'Fast Delivery',
                'group' => 'services',
                'order' => 5
            ],
            [
                'key' => 'service_2_desc',
                'type' => 'textarea',
                'value_ar' => 'نضمن توصيل سريع وآمن لجميع الطلبات',
                'value_en' => 'We guarantee fast and secure delivery for all orders',
                'group' => 'services',
                'order' => 6
            ],

            [
                'key' => 'service_3_icon',
                'type' => 'text',
                'value_ar' => 'fa-solid fa-pen-ruler',
                'value_en' => 'fa-solid fa-pen-ruler',
                'group' => 'services',
                'order' => 7
            ],
            [
                'key' => 'service_3_title',
                'type' => 'text',
                'value_ar' => 'طلبات مخصصة',
                'value_en' => 'Custom Orders',
                'group' => 'services',
                'order' => 8
            ],
            [
                'key' => 'service_3_desc',
                'type' => 'textarea',
                'value_ar' => 'إمكانية تصميم منتجات حسب الطلب',
                'value_en' => 'Ability to design products on demand',
                'group' => 'services',
                'order' => 9
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
