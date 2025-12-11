<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePage;
use App\Models\SectionTitle;
use App\Models\DiscoverItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HomePageController extends Controller
{
    public function edit()
    {
        // IMPORTANT: This 'locale' parameter is for CONTENT LANGUAGE SELECTION ONLY
        // It determines which content version (Arabic or English) the admin wants to edit
        // It does NOT change the dashboard interface language (which comes from session)
        // Dashboard language is controlled by SetLocale middleware using session value
        $locale = request('locale', 'ar');
        $homePage = HomePage::where('locale', $locale)->where('is_active', true)->first();

        if (!$homePage) {
            $homePage = HomePage::create([
                'locale' => $locale,
                'is_active' => true,
                'hero_slides' => [],
                'gifts_items' => [],
                'discover_items' => [],
            ]);
        }

        // Get section titles
        $giftsTitle = SectionTitle::where('section_key', 'gifts_section')->first();

        // Get discover items
        $discoverItems = DiscoverItem::active()->ordered()->get();

        return view('admin.pages.home-edit', compact('homePage', 'locale', 'giftsTitle', 'discoverItems'));
    }

    public function update(Request $request)
    {
        // IMPORTANT: Content locale vs Dashboard locale separation
        // The 'locale' parameter here refers to which CONTENT version is being edited (ar/en)
        // It does NOT affect the dashboard interface language (controlled by session)
        // After redirect, user stays in same dashboard language but continues editing same content version
        $locale = $request->input('locale', $request->get('locale', app()->getLocale()));

        Log::info('HomePageController update - Locale received', [
            'locale_from_input' => $request->input('locale'),
            'locale_from_get' => $request->get('locale'),
            'final_locale' => $locale,
            'all_request_data' => $request->except(['hero_image', 'cyber_sale_image', 'gifts_image', 'discover_image', 'spotlight_image'])
        ]);

        $homePage = HomePage::where('locale', $locale)->where('is_active', true)->firstOrFail();

        $data = [
            'locale' => $locale,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle Hero Slides
        if ($request->has('hero_slides')) {
            $heroSlides = [];
            foreach ($request->hero_slides as $index => $slide) {
                $slideData = [
                    'link' => $slide['link'] ?? '#',
                    'alt' => $slide['alt'] ?? 'Hero Banner'
                ];

                if ($request->hasFile("hero_slide_image.{$index}")) {
                    $path = $request->file("hero_slide_image.{$index}")->store('home-page/hero', 'public');
                    $slideData['image'] = '/storage/' . $path;
                } else {
                    $slideData['image'] = $slide['image'] ?? '';
                }

                $heroSlides[] = $slideData;
            }
            $data['hero_slides'] = $heroSlides;
        }

        // Handle Cyber Sale Section
        if ($request->hasFile('cyber_sale_image')) {
            $path = $request->file('cyber_sale_image')->store('home-page/cyber-sale', 'public');
            $data['cyber_sale_image'] = '/storage/' . $path;
        } else {
            $data['cyber_sale_image'] = $request->input('cyber_sale_image_current');
        }

        $data['cyber_sale_link'] = $request->input('cyber_sale_link');
        $data['cyber_sale_title'] = [
            'ar' => $request->input('cyber_sale_title_ar'),
            'en' => $request->input('cyber_sale_title_en'),
        ];
        $data['cyber_sale_button_text'] = [
            'ar' => $request->input('cyber_sale_button_text_ar'),
            'en' => $request->input('cyber_sale_button_text_en'),
        ];
        $data['cyber_sale_active'] = $request->boolean('cyber_sale_active');

        // Handle Gifts Section Title in section_titles table
        SectionTitle::updateOrCreate(
            ['section_key' => 'gifts_section'],
            [
                'title_ar' => $request->input('gifts_section_title_ar'),
                'title_en' => $request->input('gifts_section_title_en'),
                'active' => $request->boolean('gifts_section_active'),
            ]
        );

        if ($request->has('gifts_items')) {
            $giftsItems = [];
            foreach ($request->gifts_items as $index => $item) {
                $currentImage = $item['image']['ar'] ?? $item['image']['en'] ?? '';

                $itemData = [
                    'title' => [
                        'ar' => $item['title']['ar'] ?? '',
                        'en' => $item['title']['en'] ?? '',
                    ],
                    'link' => $item['link'] ?? '#',
                    'image' => [
                        'ar' => $currentImage,
                        'en' => $currentImage
                    ]
                ];

                // Handle Single Image Upload (used for both languages)
                if ($request->hasFile("gift_image.{$index}")) {
                    try {
                        $file = $request->file("gift_image.{$index}");
                        $path = $file->store('home-page/gifts', 'public');
                        $uploadedImage = '/storage/' . $path;
                        $itemData['image']['ar'] = $uploadedImage;
                        $itemData['image']['en'] = $uploadedImage;
                        Log::info("Gift #{$index} image uploaded successfully: {$uploadedImage}");
                    } catch (\Exception $e) {
                        Log::error("Failed to upload Gift #{$index} image: " . $e->getMessage());
                    }
                }

                $giftsItems[] = $itemData;
            }
            $data['gifts_items'] = $giftsItems;
            Log::info('Gifts section saved: ' . count($giftsItems) . ' items');
        }
        $data['gifts_section_active'] = $request->boolean('gifts_section_active');

        // Handle DG Banner
        $currentDgImage = $homePage->dg_banner_image ?? ['ar' => '', 'en' => ''];
        $dgBannerImage = [
            'ar' => is_array($currentDgImage) ? ($currentDgImage['ar'] ?? '') : $currentDgImage,
            'en' => is_array($currentDgImage) ? ($currentDgImage['en'] ?? '') : $currentDgImage,
        ];

        // Handle DG Banner Image Arabic
        if ($request->hasFile('dg_banner_image_ar')) {
            try {
                $file = $request->file('dg_banner_image_ar');
                $path = $file->store('home-page/dg-banner', 'public');
                $dgBannerImage['ar'] = '/storage/' . $path;
                Log::info("DG Banner (AR) image uploaded: " . $dgBannerImage['ar']);
            } catch (\Exception $e) {
                Log::error("Failed to upload DG Banner (AR) image: " . $e->getMessage());
            }
        }

        // Handle DG Banner Image English
        if ($request->hasFile('dg_banner_image_en')) {
            try {
                $file = $request->file('dg_banner_image_en');
                $path = $file->store('home-page/dg-banner', 'public');
                $dgBannerImage['en'] = '/storage/' . $path;
                Log::info("DG Banner (EN) image uploaded: " . $dgBannerImage['en']);
            } catch (\Exception $e) {
                Log::error("Failed to upload DG Banner (EN) image: " . $e->getMessage());
            }
        }

        $data['dg_banner_image'] = $dgBannerImage;
        $data['dg_banner_link'] = $request->input('dg_banner_link', '#');
        $data['dg_banner_active'] = $request->boolean('dg_banner_active');
        Log::info('DG Banner saved: ' . json_encode($dgBannerImage));

        // Handle Gucci Spotlight
        $currentGucciImage = $homePage->gucci_spotlight_image ?? ['ar' => '', 'en' => ''];
        $gucciSpotlightImage = [
            'ar' => is_array($currentGucciImage) ? ($currentGucciImage['ar'] ?? '') : $currentGucciImage,
            'en' => is_array($currentGucciImage) ? ($currentGucciImage['en'] ?? '') : $currentGucciImage,
        ];

        // Handle Gucci Spotlight Image Arabic
        if ($request->hasFile('gucci_spotlight_image_ar')) {
            try {
                $file = $request->file('gucci_spotlight_image_ar');
                $path = $file->store('home-page/gucci-spotlight', 'public');
                $gucciSpotlightImage['ar'] = '/storage/' . $path;
                Log::info("Gucci Spotlight (AR) image uploaded: " . $gucciSpotlightImage['ar']);
            } catch (\Exception $e) {
                Log::error("Failed to upload Gucci Spotlight (AR) image: " . $e->getMessage());
            }
        }

        // Handle Gucci Spotlight Image English
        if ($request->hasFile('gucci_spotlight_image_en')) {
            try {
                $file = $request->file('gucci_spotlight_image_en');
                $path = $file->store('home-page/gucci-spotlight', 'public');
                $gucciSpotlightImage['en'] = '/storage/' . $path;
                Log::info("Gucci Spotlight (EN) image uploaded: " . $gucciSpotlightImage['en']);
            } catch (\Exception $e) {
                Log::error("Failed to upload Gucci Spotlight (EN) image: " . $e->getMessage());
            }
        }

        $data['gucci_spotlight_image'] = $gucciSpotlightImage;
        $data['gucci_spotlight_link'] = $request->input('gucci_spotlight_link', '#');
        $data['gucci_spotlight_active'] = $request->boolean('gucci_spotlight_active');
        Log::info('Gucci Spotlight saved: ' . json_encode($gucciSpotlightImage));

        // Handle Featured Banner
        if ($request->hasFile('featured_banner_image')) {
            $path = $request->file('featured_banner_image')->store('home-page/featured', 'public');
            $data['featured_banner_image'] = '/storage/' . $path;
        } else {
            $data['featured_banner_image'] = $request->input('featured_banner_image_current');
        }

        $data['featured_banner_link'] = $request->input('featured_banner_link');
        $data['featured_banner_title'] = [
            'ar' => $request->input('featured_banner_title_ar'),
            'en' => $request->input('featured_banner_title_en'),
        ];
        $data['featured_banner_subtitle'] = [
            'ar' => $request->input('featured_banner_subtitle_ar'),
            'en' => $request->input('featured_banner_subtitle_en'),
        ];
        $data['featured_banner_button_text'] = [
            'ar' => $request->input('featured_banner_button_text_ar'),
            'en' => $request->input('featured_banner_button_text_en'),
        ];
        $data['featured_banner_active'] = $request->boolean('featured_banner_active');

        // Handle Must Have Section
        $data['must_have_section_title'] = [
            'ar' => $request->input('must_have_section_title_ar'),
            'en' => $request->input('must_have_section_title_en'),
        ];
        $data['must_have_section_active'] = $request->boolean('must_have_section_active');

        // Handle Spotlight Banner
        if ($request->hasFile('spotlight_banner_image')) {
            $path = $request->file('spotlight_banner_image')->store('home-page/spotlight', 'public');
            $data['spotlight_banner_image'] = '/storage/' . $path;
        } else {
            $data['spotlight_banner_image'] = $request->input('spotlight_banner_image_current');
        }

        $data['spotlight_banner_link'] = $request->input('spotlight_banner_link');
        $data['spotlight_banner_title'] = [
            'ar' => $request->input('spotlight_banner_title_ar'),
            'en' => $request->input('spotlight_banner_title_en'),
        ];
        $data['spotlight_banner_subtitle'] = [
            'ar' => $request->input('spotlight_banner_subtitle_ar'),
            'en' => $request->input('spotlight_banner_subtitle_en'),
        ];
        $data['spotlight_banner_button_text'] = [
            'ar' => $request->input('spotlight_banner_button_text_ar'),
            'en' => $request->input('spotlight_banner_button_text_en'),
        ];
        $data['spotlight_banner_active'] = $request->boolean('spotlight_banner_active');

        // Handle Discover Section
        $data['discover_section_title'] = [
            'ar' => $request->input('discover_section_title_ar'),
            'en' => $request->input('discover_section_title_en'),
        ];

        // Handle Discover Items - Save to separate table
        if ($request->has('discover_items')) {
            Log::info('Processing discover items', ['items' => $request->discover_items]);

            foreach ($request->discover_items as $index => $item) {
                $itemData = [
                    'title' => [
                        'ar' => $item['title']['ar'] ?? '',
                        'en' => $item['title']['en'] ?? '',
                    ],
                    'link' => $item['link'] ?? '#',
                    'sort_order' => $index,
                    'active' => true,
                ];

                // Handle image upload
                if ($request->hasFile("discover_image.{$index}")) {
                    try {
                        $file = $request->file("discover_image.{$index}");
                        $path = $file->store('home-page/discover', 'public');
                        $itemData['image'] = '/storage/' . $path;
                        Log::info("Discover image uploaded", ['index' => $index, 'path' => $itemData['image']]);
                    } catch (\Exception $e) {
                        Log::error("Failed to upload discover image", ['index' => $index, 'error' => $e->getMessage()]);
                    }
                } else {
                    $itemData['image'] = $item['image'] ?? '';
                }

                // Check if item exists by checking if we have an ID in the request
                if (isset($item['id']) && $item['id']) {
                    $discoverItem = DiscoverItem::find($item['id']);
                    if ($discoverItem) {
                        $discoverItem->update($itemData);
                        Log::info("Updated discover item", ['id' => $item['id']]);
                    } else {
                        DiscoverItem::create($itemData);
                        Log::info("Created new discover item (ID not found)");
                    }
                } else {
                    DiscoverItem::create($itemData);
                    Log::info("Created new discover item");
                }
            }
        }
        $data['discover_section_active'] = $request->boolean('discover_section_active');

        // Handle Perfect Present Section
        $data['perfect_present_section_title'] = [
            'ar' => $request->input('perfect_present_section_title_ar'),
            'en' => $request->input('perfect_present_section_title_en'),
        ];
        $data['perfect_present_section_active'] = $request->boolean('perfect_present_section_active');

        // Handle Membership Section
        $data['membership_title'] = [
            'ar' => $request->input('membership_title_ar'),
            'en' => $request->input('membership_title_en'),
        ];
        $data['membership_desc'] = [
            'ar' => $request->input('membership_desc_ar'),
            'en' => $request->input('membership_desc_en'),
        ];
        $data['membership_link'] = $request->input('membership_link', '#');
        $data['membership_section_active'] = $request->boolean('membership_section_active');

        // Handle Membership Image upload
        if ($request->hasFile('membership_image')) {
            try {
                $file = $request->file('membership_image');
                $path = $file->store('home-page/membership', 'public');
                $data['membership_image'] = '/storage/' . $path;
                Log::info("Membership image uploaded: " . $data['membership_image']);
            } catch (\Exception $e) {
                Log::error("Failed to upload membership image: " . $e->getMessage());
                $data['membership_image'] = $request->input('membership_image_current', '');
            }
        } else {
            $data['membership_image'] = $request->input('membership_image_current', '');
        }

        // Handle App Download Section
        $data['app_download_title'] = [
            'ar' => $request->input('app_download_title_ar'),
            'en' => $request->input('app_download_title_en'),
        ];
        $data['app_download_subtitle'] = [
            'ar' => $request->input('app_download_subtitle_ar'),
            'en' => $request->input('app_download_subtitle_en'),
        ];
        $data['app_store_link'] = $request->input('app_store_link');
        $data['google_play_link'] = $request->input('google_play_link');
        $data['app_section_active'] = $request->boolean('app_section_active');

        // Handle App Badge Images (Google Play & App Store)
        $appImages = [];
        if ($homePage->app_image && is_array($homePage->app_image)) {
            $appImages = $homePage->app_image;
        }

        // Handle Google Play Badge Image
        if ($request->hasFile('google_play_image')) {
            try {
                $file = $request->file('google_play_image');
                $path = $file->store('home-page/app/android', 'public');
                $appImages['android'] = '/storage/' . $path;
                Log::info("Google Play badge image uploaded: " . $appImages['android']);
            } catch (\Exception $e) {
                Log::error("Failed to upload Google Play badge: " . $e->getMessage());
            }
        } else {
            $appImages['android'] = $request->input('google_play_image_current', '');
        }

        // Handle App Store Badge Image
        if ($request->hasFile('app_store_image')) {
            try {
                $file = $request->file('app_store_image');
                $path = $file->store('home-page/app/ios', 'public');
                $appImages['ios'] = '/storage/' . $path;
                Log::info("App Store badge image uploaded: " . $appImages['ios']);
            } catch (\Exception $e) {
                Log::error("Failed to upload App Store badge: " . $e->getMessage());
            }
        } else {
            $appImages['ios'] = $request->input('app_store_image_current', '');
        }

        $data['app_image'] = $appImages;

        $homePage->update($data);

        // IMPORTANT: Keep the current locale from the request to maintain user's choice
        $currentLocale = $request->input('locale', $request->get('locale', 'ar'));

        Log::info('HomePageController update - Redirecting', [
            'redirect_locale' => $currentLocale
        ]);

        return redirect()->route('admin.home.edit', ['locale' => $currentLocale])
            ->with('success', 'تم تحديث محتوى الصفحة الرئيسية بنجاح');
    }
}
