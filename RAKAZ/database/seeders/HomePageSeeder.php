<?php

namespace Database\Seeders;

use App\Models\HomePage;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Arabic Version
        HomePage::create([
            'locale' => 'ar',
            'is_active' => true,

            // Hero Slides
            'hero_slides' => [
                [
                    'image' => '/assets/images/MW_FWB_DSK_UAE_EN copy.jpg',
                    'link' => '#',
                    'alt' => 'Hero Banner 1'
                ],
                [
                    'image' => '/assets/images/New folder/updated.jpg',
                    'link' => '#',
                    'alt' => 'Hero Banner 2'
                ],
                [
                    'image' => '/assets/images/New folder/2_fa42623b-b79c-423e-be3d-a63ede9ff974.jpg',
                    'link' => '#',
                    'alt' => 'Hero Banner 3'
                ],
            ],

            // Cyber Sale Section
            'cyber_sale_image' => '/assets/images/New folder/updated.jpg',
            'cyber_sale_link' => '#',
            'cyber_sale_title' => [
                'ar' => 'تخفيضات سايبر',
                'en' => 'Cyber Sale'
            ],
            'cyber_sale_button_text' => [
                'ar' => 'تسوق الآن',
                'en' => 'Shop Now'
            ],
            'cyber_sale_active' => true,

            // Gifts Section
            'gifts_section_title' => [
                'ar' => 'هدايا مثالية للرجل الإماراتي',
                'en' => 'Perfect Gifts for the Emirati Man'
            ],
            'gifts_items' => [
                [
                    'image' => '/assets/images/New folder/Emirati_Gold_Edition_White.jpg',
                    'title' => [
                        'ar' => 'كنادر فاخرة',
                        'en' => 'Luxury Kanduras'
                    ],
                    'link' => '#'
                ],
                [
                    'image' => '/assets/images/New folder/49.jpg',
                    'title' => [
                        'ar' => 'شالات قطنية',
                        'en' => 'Cotton Shawls'
                    ],
                    'link' => '#'
                ],
                [
                    'image' => '/assets/images/New folder/50.jpg',
                    'title' => [
                        'ar' => 'فانيل فاخرة',
                        'en' => 'Luxury Undershirts'
                    ],
                    'link' => '#'
                ],
            ],
            'gifts_section_active' => true,

            // Featured Banner (D&G Position)
            'featured_banner_image' => '/assets/images/New folder/Kuwaiti_blue_image_3_treated.jpg',
            'featured_banner_title' => [
                'ar' => 'مجموعة راقية من الكنادر الفاخرة',
                'en' => 'Elegant Collection of Luxury Kanduras'
            ],
            'featured_banner_subtitle' => [
                'ar' => 'مجموعة منسقة من التصاميم التعبيرية والحرفية المدروسة، تعيد تعريف الرفاهية للموسم',
                'en' => 'A curated collection of expressive designs and thoughtful craftsmanship, redefining luxury for the season'
            ],
            'featured_banner_link' => '#',
            'featured_banner_button_text' => [
                'ar' => 'اقرأ وتسوق',
                'en' => 'Read & Shop'
            ],
            'featured_banner_active' => true,

            // Must Have Section
            'must_have_section_title' => [
                'ar' => 'كنادر مميزة',
                'en' => 'Featured Kanduras'
            ],
            'must_have_section_active' => true,

            // Spotlight Banner (Gucci Position)
            'spotlight_banner_image' => '/assets/images/New folder/2847-1.jpg',
            'spotlight_banner_title' => [
                'ar' => 'تسليط الضوء على: ركاز',
                'en' => 'Spotlight on: Rakaz'
            ],
            'spotlight_banner_subtitle' => [
                'ar' => 'من الكنادير التقليدية إلى الشالات الفاخرة، اكتشف الهدايا المصممة لجعل كل احتفال لا يُنسى',
                'en' => 'From traditional kanduras to luxury shawls, discover gifts designed to make every celebration unforgettable'
            ],
            'spotlight_banner_link' => '#',
            'spotlight_banner_button_text' => [
                'ar' => 'تسوق المجموعة',
                'en' => 'Shop Collection'
            ],
            'spotlight_banner_active' => true,

            // Discover Section
            'discover_section_title' => [
                'ar' => 'اكتشف المزيد',
                'en' => 'Discover More'
            ],
            'discover_items' => [
                [
                    'image' => '/assets/images/New folder/2_a55ea98a-5224-4f21-89ba-31b1401de210.jpg',
                    'title' => [
                        'ar' => 'الكنادير',
                        'en' => 'Kanduras'
                    ],
                    'link' => '#',
                    'type' => 'small'
                ],
                [
                    'image' => '/assets/images/New folder/2_acc63d9c-4519-45a3-b84a-bdf425ad0c91.jpg',
                    'title' => [
                        'ar' => 'الشالات',
                        'en' => 'Shawls'
                    ],
                    'link' => '#',
                    'type' => 'small'
                ],
                [
                    'image' => '/assets/images/New folder/2_c0dd1f4b-73a9-4a8e-9522-f1e542d8581e.jpg',
                    'title' => [
                        'ar' => 'السراويل',
                        'en' => 'Trousers'
                    ],
                    'link' => '#',
                    'type' => 'small'
                ],
                [
                    'image' => '/assets/images/New folder/2_d940e25f-40f1-46ed-8519-ccc494df05c8.jpg',
                    'title' => [
                        'ar' => 'الفانيل',
                        'en' => 'Undershirts'
                    ],
                    'link' => '#',
                    'type' => 'wide'
                ],
                [
                    'image' => '/assets/images/New folder/2_ed79c0d9-9f7b-4bf2-81af-041aaa5b7f8b.jpg',
                    'title' => [
                        'ar' => 'الوزارات',
                        'en' => 'Bisht'
                    ],
                    'link' => '#',
                    'type' => 'wide'
                ],
            ],
            'discover_section_active' => true,

            // Perfect Present Section
            'perfect_present_section_title' => [
                'ar' => 'الهدية المثالية',
                'en' => 'The Perfect Present'
            ],
            'perfect_present_section_active' => true,

            // Membership Section
            'membership_logo_text' => [
                'ar' => 'RAKAZ',
                'en' => 'RAKAZ'
            ],
            'membership_description' => [
                'ar' => 'استمتع بالمكافآت الحصرية والعروض المخصصة للأعضاء فقط',
                'en' => 'Enjoy exclusive rewards and offers for members only'
            ],
            'membership_button_text' => [
                'ar' => 'انضم أو سجل الدخول',
                'en' => 'Join or Sign In'
            ],
            'membership_button_link' => '#',
            'membership_section_active' => true,

            // App Download Section
            'app_download_title' => [
                'ar' => 'احصل على خصم 10% على طلبك الأول من التطبيق',
                'en' => 'Get 10% off your first order from the app'
            ],
            'app_download_subtitle' => [
                'ar' => 'أدخل الرمز APP10 عند الدفع',
                'en' => 'Enter code APP10 at checkout'
            ],
            'app_store_link' => '#',
            'google_play_link' => '#',
            'app_section_active' => true,
        ]);

        // English Version
        HomePage::create([
            'locale' => 'en',
            'is_active' => true,

            // Hero Slides
            'hero_slides' => [
                [
                    'image' => '/assets/images/MW_FWB_DSK_UAE_EN copy.jpg',
                    'link' => '#',
                    'alt' => 'Hero Banner 1'
                ],
                [
                    'image' => '/assets/images/New folder/updated.jpg',
                    'link' => '#',
                    'alt' => 'Hero Banner 2'
                ],
                [
                    'image' => '/assets/images/New folder/2_fa42623b-b79c-423e-be3d-a63ede9ff974.jpg',
                    'link' => '#',
                    'alt' => 'Hero Banner 3'
                ],
            ],

            // Cyber Sale Section
            'cyber_sale_image' => '/assets/images/New folder/updated.jpg',
            'cyber_sale_link' => '#',
            'cyber_sale_title' => [
                'ar' => 'تخفيضات سايبر',
                'en' => 'Cyber Sale'
            ],
            'cyber_sale_button_text' => [
                'ar' => 'تسوق الآن',
                'en' => 'Shop Now'
            ],
            'cyber_sale_active' => true,

            // Gifts Section
            'gifts_section_title' => [
                'ar' => 'هدايا مثالية للرجل الإماراتي',
                'en' => 'Perfect Gifts for the Emirati Man'
            ],
            'gifts_items' => [
                [
                    'image' => '/assets/images/New folder/Emirati_Gold_Edition_White.jpg',
                    'title' => [
                        'ar' => 'كنادر فاخرة',
                        'en' => 'Luxury Kanduras'
                    ],
                    'link' => '#'
                ],
                [
                    'image' => '/assets/images/New folder/49.jpg',
                    'title' => [
                        'ar' => 'شالات قطنية',
                        'en' => 'Cotton Shawls'
                    ],
                    'link' => '#'
                ],
                [
                    'image' => '/assets/images/New folder/50.jpg',
                    'title' => [
                        'ar' => 'فانيل فاخرة',
                        'en' => 'Luxury Undershirts'
                    ],
                    'link' => '#'
                ],
            ],
            'gifts_section_active' => true,

            // Featured Banner
            'featured_banner_image' => '/assets/images/New folder/Kuwaiti_blue_image_3_treated.jpg',
            'featured_banner_title' => [
                'ar' => 'مجموعة راقية من الكنادر الفاخرة',
                'en' => 'Elegant Collection of Luxury Kanduras'
            ],
            'featured_banner_subtitle' => [
                'ar' => 'مجموعة منسقة من التصاميم التعبيرية والحرفية المدروسة، تعيد تعريف الرفاهية للموسم',
                'en' => 'A curated collection of expressive designs and thoughtful craftsmanship, redefining luxury for the season'
            ],
            'featured_banner_link' => '#',
            'featured_banner_button_text' => [
                'ar' => 'اقرأ وتسوق',
                'en' => 'Read & Shop'
            ],
            'featured_banner_active' => true,

            // Must Have Section
            'must_have_section_title' => [
                'ar' => 'كنادر مميزة',
                'en' => 'Featured Kanduras'
            ],
            'must_have_section_active' => true,

            // Spotlight Banner
            'spotlight_banner_image' => '/assets/images/New folder/2847-1.jpg',
            'spotlight_banner_title' => [
                'ar' => 'تسليط الضوء على: ركاز',
                'en' => 'Spotlight on: Rakaz'
            ],
            'spotlight_banner_subtitle' => [
                'ar' => 'من الكنادير التقليدية إلى الشالات الفاخرة، اكتشف الهدايا المصممة لجعل كل احتفال لا يُنسى',
                'en' => 'From traditional kanduras to luxury shawls, discover gifts designed to make every celebration unforgettable'
            ],
            'spotlight_banner_link' => '#',
            'spotlight_banner_button_text' => [
                'ar' => 'تسوق المجموعة',
                'en' => 'Shop Collection'
            ],
            'spotlight_banner_active' => true,

            // Discover Section
            'discover_section_title' => [
                'ar' => 'اكتشف المزيد',
                'en' => 'Discover More'
            ],
            'discover_items' => [
                [
                    'image' => '/assets/images/New folder/2_a55ea98a-5224-4f21-89ba-31b1401de210.jpg',
                    'title' => [
                        'ar' => 'الكنادير',
                        'en' => 'Kanduras'
                    ],
                    'link' => '#',
                    'type' => 'small'
                ],
                [
                    'image' => '/assets/images/New folder/2_acc63d9c-4519-45a3-b84a-bdf425ad0c91.jpg',
                    'title' => [
                        'ar' => 'الشالات',
                        'en' => 'Shawls'
                    ],
                    'link' => '#',
                    'type' => 'small'
                ],
                [
                    'image' => '/assets/images/New folder/2_c0dd1f4b-73a9-4a8e-9522-f1e542d8581e.jpg',
                    'title' => [
                        'ar' => 'السراويل',
                        'en' => 'Trousers'
                    ],
                    'link' => '#',
                    'type' => 'small'
                ],
                [
                    'image' => '/assets/images/New folder/2_d940e25f-40f1-46ed-8519-ccc494df05c8.jpg',
                    'title' => [
                        'ar' => 'الفانيل',
                        'en' => 'Undershirts'
                    ],
                    'link' => '#',
                    'type' => 'wide'
                ],
                [
                    'image' => '/assets/images/New folder/2_ed79c0d9-9f7b-4bf2-81af-041aaa5b7f8b.jpg',
                    'title' => [
                        'ar' => 'الوزارات',
                        'en' => 'Bisht'
                    ],
                    'link' => '#',
                    'type' => 'wide'
                ],
            ],
            'discover_section_active' => true,

            // Perfect Present Section
            'perfect_present_section_title' => [
                'ar' => 'الهدية المثالية',
                'en' => 'The Perfect Present'
            ],
            'perfect_present_section_active' => true,

            // Membership Section
            'membership_logo_text' => [
                'ar' => 'RAKAZ',
                'en' => 'RAKAZ'
            ],
            'membership_description' => [
                'ar' => 'استمتع بالمكافآت الحصرية والعروض المخصصة للأعضاء فقط',
                'en' => 'Enjoy exclusive rewards and offers for members only'
            ],
            'membership_button_text' => [
                'ar' => 'انضم أو سجل الدخول',
                'en' => 'Join or Sign In'
            ],
            'membership_button_link' => '#',
            'membership_section_active' => true,

            // App Download Section
            'app_download_title' => [
                'ar' => 'احصل على خصم 10% على طلبك الأول من التطبيق',
                'en' => 'Get 10% off your first order from the app'
            ],
            'app_download_subtitle' => [
                'ar' => 'أدخل الرمز APP10 عند الدفع',
                'en' => 'Enter code APP10 at checkout'
            ],
            'app_store_link' => '#',
            'google_play_link' => '#',
            'app_section_active' => true,
        ]);
    }
}
