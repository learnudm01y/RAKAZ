<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePage;
use App\Models\SectionTitle;
use App\Models\DiscoverItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Home Page Content Management Controller
 *
 * CRITICAL CONCEPT: Content Locale vs Dashboard Locale Separation
 * ================================================================
 * This controller manages CONTENT in multiple languages (ar/en)
 * The 'locale' parameter throughout this controller refers to CONTENT language ONLY
 *
 * Dashboard Language (Interface):
 * - Controlled by session (app()->getLocale())
 * - Changed via user menu toggle button
 * - Set by SetLocale middleware
 * - Independent from content editing
 *
 * Content Language:
 * - Controlled by 'locale' parameter in URLs/forms
 * - Determines which content version (ar/en) to edit
 * - Does NOT affect dashboard interface language
 *
 * Example Scenario:
 * - Admin has dashboard in Arabic (session locale = 'ar')
 * - Admin selects to edit English content (URL param locale = 'en')
 * - Admin sees Arabic interface, edits English content
 * - After save, dashboard stays Arabic, continues editing English content
 *
 * THIS SEPARATION IS CRITICAL AND MUST NEVER BE VIOLATED
 */
class HomePageController extends Controller
{
    public function edit()
    {
        // CRITICAL ARCHITECTURE CHANGE: Using 'content_lang' instead of 'locale'
        // This prevents ANY conflict with dashboard language (session 'locale')
        // content_lang = which content version to edit (ar/en)
        // session locale = dashboard interface language
        // COMPLETE SEPARATION - NO CONFLICT POSSIBLE
        $contentLang = request('content_lang', 'ar');
        $homePage = HomePage::where('locale', $contentLang)->where('is_active', true)->first();

        if (!$homePage) {
            $homePage = HomePage::create([
                'locale' => $contentLang,
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

        return view('admin.pages.home-edit', compact('homePage', 'contentLang', 'giftsTitle', 'discoverItems'));
    }

    public function update(Request $request)
    {
        // ARCHITECTURE FIX: Using 'content_lang' completely separates from 'locale'
        // content_lang = content version parameter (NEVER touches session)
        // locale = dashboard language (session only, NEVER from request)
        $contentLang = $request->input('content_lang', 'ar');
        $dashboardLocale = session('locale', 'ar');

        Log::info('HomePageController update - Complete separation', [
            'dashboard_locale' => $dashboardLocale,
            'content_lang' => $contentLang,
            'note' => 'Different parameter names = zero conflict possible'
        ]);

        $homePage = HomePage::where('locale', $contentLang)->where('is_active', true)->firstOrFail();

        $data = [
            'locale' => $contentLang,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle Hero Slides
        if ($request->has('hero_slides')) {
            $heroSlides = [];
            $heroSlidesTablet = [];
            $heroSlidesMobile = [];

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

                // Handle Tablet Image
                $tabletSlideData = [
                    'link' => $slide['link'] ?? '#',
                    'alt' => $slide['alt'] ?? 'Hero Banner'
                ];

                if ($request->hasFile("hero_slide_tablet_image.{$index}")) {
                    $path = $request->file("hero_slide_tablet_image.{$index}")->store('home-page/hero/tablet', 'public');
                    $tabletSlideData['image'] = '/storage/' . $path;
                } else {
                    $existingTablet = $homePage->hero_slides_tablet[$index] ?? null;
                    $tabletSlideData['image'] = $existingTablet['image'] ?? '';
                }

                $heroSlidesTablet[] = $tabletSlideData;

                // Handle Mobile Image
                $mobileSlideData = [
                    'link' => $slide['link'] ?? '#',
                    'alt' => $slide['alt'] ?? 'Hero Banner'
                ];

                if ($request->hasFile("hero_slide_mobile_image.{$index}")) {
                    $path = $request->file("hero_slide_mobile_image.{$index}")->store('home-page/hero/mobile', 'public');
                    $mobileSlideData['image'] = '/storage/' . $path;
                } else {
                    $existingMobile = $homePage->hero_slides_mobile[$index] ?? null;
                    $mobileSlideData['image'] = $existingMobile['image'] ?? '';
                }

                $heroSlidesMobile[] = $mobileSlideData;
            }

            $data['hero_slides'] = $heroSlides;
            $data['hero_slides_tablet'] = $heroSlidesTablet;
            $data['hero_slides_mobile'] = $heroSlidesMobile;
        }

        // Handle Cyber Sale Section
        if ($request->hasFile('cyber_sale_image')) {
            $path = $request->file('cyber_sale_image')->store('home-page/cyber-sale', 'public');
            $data['cyber_sale_image'] = '/storage/' . $path;
        } else {
            $data['cyber_sale_image'] = $request->input('cyber_sale_image_current');
        }

        // Handle Cyber Sale Tablet Image
        if ($request->hasFile('cyber_sale_image_tablet')) {
            $path = $request->file('cyber_sale_image_tablet')->store('home-page/cyber-sale/tablet', 'public');
            $data['cyber_sale_image_tablet'] = '/storage/' . $path;
        } else {
            $data['cyber_sale_image_tablet'] = $request->input('cyber_sale_image_tablet_current');
        }

        // Handle Cyber Sale Mobile Image
        if ($request->hasFile('cyber_sale_image_mobile')) {
            $path = $request->file('cyber_sale_image_mobile')->store('home-page/cyber-sale/mobile', 'public');
            $data['cyber_sale_image_mobile'] = '/storage/' . $path;
        } else {
            $data['cyber_sale_image_mobile'] = $request->input('cyber_sale_image_mobile_current');
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

        // Handle DG Banner Tablet Images
        $currentDgTabletImage = $homePage->dg_banner_image_tablet ?? ['ar' => '', 'en' => ''];
        $dgBannerTabletImage = [
            'ar' => is_array($currentDgTabletImage) ? ($currentDgTabletImage['ar'] ?? '') : '',
            'en' => is_array($currentDgTabletImage) ? ($currentDgTabletImage['en'] ?? '') : '',
        ];

        if ($request->hasFile('dg_banner_image_tablet_ar')) {
            $path = $request->file('dg_banner_image_tablet_ar')->store('home-page/dg-banner/tablet', 'public');
            $dgBannerTabletImage['ar'] = '/storage/' . $path;
        }

        if ($request->hasFile('dg_banner_image_tablet_en')) {
            $path = $request->file('dg_banner_image_tablet_en')->store('home-page/dg-banner/tablet', 'public');
            $dgBannerTabletImage['en'] = '/storage/' . $path;
        }

        $data['dg_banner_image_tablet'] = $dgBannerTabletImage;

        // Handle DG Banner Mobile Images
        $currentDgMobileImage = $homePage->dg_banner_image_mobile ?? ['ar' => '', 'en' => ''];
        $dgBannerMobileImage = [
            'ar' => is_array($currentDgMobileImage) ? ($currentDgMobileImage['ar'] ?? '') : '',
            'en' => is_array($currentDgMobileImage) ? ($currentDgMobileImage['en'] ?? '') : '',
        ];

        if ($request->hasFile('dg_banner_image_mobile_ar')) {
            $path = $request->file('dg_banner_image_mobile_ar')->store('home-page/dg-banner/mobile', 'public');
            $dgBannerMobileImage['ar'] = '/storage/' . $path;
        }

        if ($request->hasFile('dg_banner_image_mobile_en')) {
            $path = $request->file('dg_banner_image_mobile_en')->store('home-page/dg-banner/mobile', 'public');
            $dgBannerMobileImage['en'] = '/storage/' . $path;
        }

        $data['dg_banner_image_mobile'] = $dgBannerMobileImage;
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

        // Handle Gucci Spotlight Tablet Images
        $currentGucciTabletImage = $homePage->gucci_spotlight_image_tablet ?? ['ar' => '', 'en' => ''];
        $gucciSpotlightTabletImage = [
            'ar' => is_array($currentGucciTabletImage) ? ($currentGucciTabletImage['ar'] ?? '') : '',
            'en' => is_array($currentGucciTabletImage) ? ($currentGucciTabletImage['en'] ?? '') : '',
        ];

        if ($request->hasFile('gucci_spotlight_image_tablet_ar')) {
            $path = $request->file('gucci_spotlight_image_tablet_ar')->store('home-page/gucci-spotlight/tablet', 'public');
            $gucciSpotlightTabletImage['ar'] = '/storage/' . $path;
        }

        if ($request->hasFile('gucci_spotlight_image_tablet_en')) {
            $path = $request->file('gucci_spotlight_image_tablet_en')->store('home-page/gucci-spotlight/tablet', 'public');
            $gucciSpotlightTabletImage['en'] = '/storage/' . $path;
        }

        $data['gucci_spotlight_image_tablet'] = $gucciSpotlightTabletImage;

        // Handle Gucci Spotlight Mobile Images
        $currentGucciMobileImage = $homePage->gucci_spotlight_image_mobile ?? ['ar' => '', 'en' => ''];
        $gucciSpotlightMobileImage = [
            'ar' => is_array($currentGucciMobileImage) ? ($currentGucciMobileImage['ar'] ?? '') : '',
            'en' => is_array($currentGucciMobileImage) ? ($currentGucciMobileImage['en'] ?? '') : '',
        ];

        if ($request->hasFile('gucci_spotlight_image_mobile_ar')) {
            $path = $request->file('gucci_spotlight_image_mobile_ar')->store('home-page/gucci-spotlight/mobile', 'public');
            $gucciSpotlightMobileImage['ar'] = '/storage/' . $path;
        }

        if ($request->hasFile('gucci_spotlight_image_mobile_en')) {
            $path = $request->file('gucci_spotlight_image_mobile_en')->store('home-page/gucci-spotlight/mobile', 'public');
            $gucciSpotlightMobileImage['en'] = '/storage/' . $path;
        }

        $data['gucci_spotlight_image_mobile'] = $gucciSpotlightMobileImage;
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

        // Handle Featured Banner Tablet Image
        if ($request->hasFile('featured_banner_image_tablet')) {
            $path = $request->file('featured_banner_image_tablet')->store('home-page/featured/tablet', 'public');
            $data['featured_banner_image_tablet'] = '/storage/' . $path;
        } else {
            $data['featured_banner_image_tablet'] = $request->input('featured_banner_image_tablet_current');
        }

        // Handle Featured Banner Mobile Image
        if ($request->hasFile('featured_banner_image_mobile')) {
            $path = $request->file('featured_banner_image_mobile')->store('home-page/featured/mobile', 'public');
            $data['featured_banner_image_mobile'] = '/storage/' . $path;
        } else {
            $data['featured_banner_image_mobile'] = $request->input('featured_banner_image_mobile_current');
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

        Log::info('HomePageController update - Success', [
            'content_saved_for_lang' => $contentLang,
            'dashboard_locale' => session('locale'),
            'note' => 'No conflict - different parameter names'
        ]);

        // Return to same page with content_lang parameter preserved
        return back()->with('success', __('labels.homepage.updated_successfully'));
    }

    /**
     * Delete an image from home page
     * Handles deletion of images from storage and database
     */
    public function deleteImage(Request $request)
    {
        try {
            $contentLang = $request->input('content_lang', 'ar');
            $imageType = $request->input('image_type'); // hero, cyber_sale, dg_banner, etc.
            $imageIndex = $request->input('image_index'); // For arrays like hero_slides
            $deviceType = $request->input('device_type', 'desktop'); // desktop, tablet, mobile

            Log::info('Delete image request', [
                'content_lang' => $contentLang,
                'image_type' => $imageType,
                'image_index' => $imageIndex,
                'device_type' => $deviceType
            ]);

            $homePage = HomePage::where('locale', $contentLang)->where('is_active', true)->firstOrFail();

            // Handle different image types
            switch ($imageType) {
                case 'hero':
                    $this->deleteHeroImage($homePage, $imageIndex, $deviceType);
                    break;
                case 'cyber_sale':
                    $this->deleteCyberSaleImage($homePage, $deviceType);
                    break;
                case 'dg_banner':
                    $this->deleteDgBannerImage($homePage, $deviceType);
                    break;
                case 'gucci_spotlight':
                    $this->deleteGucciSpotlightImage($homePage, $deviceType);
                    break;
                case 'featured_banner':
                    $this->deleteFeaturedBannerImage($homePage, $deviceType);
                    break;
                case 'gift':
                    $this->deleteGiftImage($homePage, $imageIndex);
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Invalid image type'], 400);
            }

            return response()->json([
                'success' => true,
                'message' => __('labels.homepage.image_deleted_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting image', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }

    private function deleteHeroImage($homePage, $index, $deviceType)
    {
        if ($deviceType === 'desktop') {
            $slides = $homePage->hero_slides;
            if (isset($slides[$index]['image'])) {
                $this->deleteFileFromStorage($slides[$index]['image']);
                unset($slides[$index]['image']);
                $homePage->hero_slides = array_values($slides);
            }
        } elseif ($deviceType === 'tablet') {
            $slides = $homePage->hero_slides_tablet;
            if (isset($slides[$index]['image'])) {
                $this->deleteFileFromStorage($slides[$index]['image']);
                unset($slides[$index]['image']);
                $homePage->hero_slides_tablet = array_values($slides);
            }
        } elseif ($deviceType === 'mobile') {
            $slides = $homePage->hero_slides_mobile;
            if (isset($slides[$index]['image'])) {
                $this->deleteFileFromStorage($slides[$index]['image']);
                unset($slides[$index]['image']);
                $homePage->hero_slides_mobile = array_values($slides);
            }
        }
        $homePage->save();
    }

    private function deleteCyberSaleImage($homePage, $deviceType)
    {
        if ($deviceType === 'desktop') {
            $this->deleteFileFromStorage($homePage->cyber_sale_image);
            $homePage->cyber_sale_image = null;
        } elseif ($deviceType === 'tablet') {
            $this->deleteFileFromStorage($homePage->cyber_sale_image_tablet);
            $homePage->cyber_sale_image_tablet = null;
        } elseif ($deviceType === 'mobile') {
            $this->deleteFileFromStorage($homePage->cyber_sale_image_mobile);
            $homePage->cyber_sale_image_mobile = null;
        }
        $homePage->save();
    }

    private function deleteDgBannerImage($homePage, $deviceType)
    {
        if ($deviceType === 'desktop') {
            $banners = $homePage->dg_banner_image;
            if (isset($banners['image'])) {
                $this->deleteFileFromStorage($banners['image']);
                unset($banners['image']);
                $homePage->dg_banner_image = $banners;
            }
        } elseif ($deviceType === 'tablet') {
            $banners = $homePage->dg_banner_image_tablet;
            if (isset($banners['image'])) {
                $this->deleteFileFromStorage($banners['image']);
                unset($banners['image']);
                $homePage->dg_banner_image_tablet = $banners;
            }
        } elseif ($deviceType === 'mobile') {
            $banners = $homePage->dg_banner_image_mobile;
            if (isset($banners['image'])) {
                $this->deleteFileFromStorage($banners['image']);
                unset($banners['image']);
                $homePage->dg_banner_image_mobile = $banners;
            }
        }
        $homePage->save();
    }

    private function deleteGucciSpotlightImage($homePage, $deviceType)
    {
        if ($deviceType === 'desktop') {
            $spotlights = $homePage->gucci_spotlight_image;
            if (isset($spotlights['image'])) {
                $this->deleteFileFromStorage($spotlights['image']);
                unset($spotlights['image']);
                $homePage->gucci_spotlight_image = $spotlights;
            }
        } elseif ($deviceType === 'tablet') {
            $spotlights = $homePage->gucci_spotlight_image_tablet;
            if (isset($spotlights['image'])) {
                $this->deleteFileFromStorage($spotlights['image']);
                unset($spotlights['image']);
                $homePage->gucci_spotlight_image_tablet = $spotlights;
            }
        } elseif ($deviceType === 'mobile') {
            $spotlights = $homePage->gucci_spotlight_image_mobile;
            if (isset($spotlights['image'])) {
                $this->deleteFileFromStorage($spotlights['image']);
                unset($spotlights['image']);
                $homePage->gucci_spotlight_image_mobile = $spotlights;
            }
        }
        $homePage->save();
    }

    private function deleteFeaturedBannerImage($homePage, $deviceType)
    {
        if ($deviceType === 'desktop') {
            $this->deleteFileFromStorage($homePage->featured_banner_image);
            $homePage->featured_banner_image = null;
        } elseif ($deviceType === 'tablet') {
            $this->deleteFileFromStorage($homePage->featured_banner_image_tablet);
            $homePage->featured_banner_image_tablet = null;
        } elseif ($deviceType === 'mobile') {
            $this->deleteFileFromStorage($homePage->featured_banner_image_mobile);
            $homePage->featured_banner_image_mobile = null;
        }
        $homePage->save();
    }

    private function deleteGiftImage($homePage, $index)
    {
        $gifts = $homePage->gifts_items;
        if (isset($gifts[$index]['image'])) {
            $this->deleteFileFromStorage($gifts[$index]['image']);
            unset($gifts[$index]['image']);
            $homePage->gifts_items = array_values($gifts);
            $homePage->save();
        }
    }

    private function deleteFileFromStorage($filePath)
    {
        if ($filePath && Storage::disk('public')->exists(str_replace('/storage/', '', $filePath))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $filePath));
            Log::info('File deleted from storage', ['path' => $filePath]);
        }
    }}
