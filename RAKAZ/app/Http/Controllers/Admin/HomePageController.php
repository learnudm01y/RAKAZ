<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePage;
use App\Models\SectionTitle;
use App\Models\DiscoverItem;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

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
    protected ImageCompressionService $imageService;

    public function __construct(ImageCompressionService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Helper method to handle image upload with compression
     * Checks for pre-compressed image first, then falls back to direct compression
     */
    protected function handleImageUpload(Request $request, string $compressedKey, string $fileKey, string $directory): ?string
    {
        // Check if we have a pre-compressed image path from AJAX
        if ($request->has($compressedKey) && !empty($request->input($compressedKey))) {
            $tempPath = $request->input($compressedKey);
            $tempFullPath = storage_path('app/public/' . $tempPath);

            if (file_exists($tempFullPath)) {
                // Move from temp to permanent directory
                $filename = basename($tempPath);
                $newPath = $directory . '/' . $filename;
                $newFullPath = storage_path('app/public/' . $newPath);

                // Ensure directory exists
                $dirPath = dirname($newFullPath);
                if (!is_dir($dirPath)) {
                    mkdir($dirPath, 0755, true);
                }

                // Move file
                rename($tempFullPath, $newFullPath);

                return '/storage/' . $newPath;
            }
        }

        // Fall back to direct file upload with compression
        if ($request->hasFile($fileKey)) {
            $path = $this->imageService->compressAndStore($request->file($fileKey), $directory);
            return '/storage/' . $path;
        }

        return null;
    }

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

                // Check for pre-compressed image first, then regular file upload
                $compressedPath = $this->handleImageUpload($request, "compressed_hero_slide_image.{$index}", "hero_slide_image.{$index}", 'home-page/hero');
                if ($compressedPath) {
                    $slideData['image'] = $compressedPath;
                } else {
                    $slideData['image'] = $slide['image'] ?? '';
                }

                $heroSlides[] = $slideData;

                // Handle Tablet Image
                $tabletSlideData = [
                    'link' => $slide['link'] ?? '#',
                    'alt' => $slide['alt'] ?? 'Hero Banner'
                ];

                $compressedTabletPath = $this->handleImageUpload($request, "compressed_hero_slide_tablet_image.{$index}", "hero_slide_tablet_image.{$index}", 'home-page/hero/tablet');
                if ($compressedTabletPath) {
                    $tabletSlideData['image'] = $compressedTabletPath;
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

                $compressedMobilePath = $this->handleImageUpload($request, "compressed_hero_slide_mobile_image.{$index}", "hero_slide_mobile_image.{$index}", 'home-page/hero/mobile');
                if ($compressedMobilePath) {
                    $mobileSlideData['image'] = $compressedMobilePath;
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
        $cyberSalePath = $this->handleImageUpload($request, 'compressed_cyber_sale_image', 'cyber_sale_image', 'home-page/cyber-sale');
        if ($cyberSalePath) {
            $data['cyber_sale_image'] = $cyberSalePath;
        } else {
            $data['cyber_sale_image'] = $request->input('cyber_sale_image_current');
        }

        // Handle Cyber Sale Tablet Image
        $cyberSaleTabletPath = $this->handleImageUpload($request, 'compressed_cyber_sale_image_tablet', 'cyber_sale_image_tablet', 'home-page/cyber-sale/tablet');
        if ($cyberSaleTabletPath) {
            $data['cyber_sale_image_tablet'] = $cyberSaleTabletPath;
        } else {
            $data['cyber_sale_image_tablet'] = $request->input('cyber_sale_image_tablet_current');
        }

        // Handle Cyber Sale Mobile Image
        $cyberSaleMobilePath = $this->handleImageUpload($request, 'compressed_cyber_sale_image_mobile', 'cyber_sale_image_mobile', 'home-page/cyber-sale/mobile');
        if ($cyberSaleMobilePath) {
            $data['cyber_sale_image_mobile'] = $cyberSaleMobilePath;
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

                // Handle Single Image Upload with compression (used for both languages)
                $giftImagePath = $this->handleImageUpload($request, "compressed_gift_image.{$index}", "gift_image.{$index}", 'home-page/gifts');
                if ($giftImagePath) {
                    $itemData['image']['ar'] = $giftImagePath;
                    $itemData['image']['en'] = $giftImagePath;
                    Log::info("Gift #{$index} image uploaded successfully: {$giftImagePath}");
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
        $dgArPath = $this->handleImageUpload($request, 'compressed_dg_banner_image_ar', 'dg_banner_image_ar', 'home-page/dg-banner');
        if ($dgArPath) {
            $dgBannerImage['ar'] = $dgArPath;
            Log::info("DG Banner (AR) image uploaded: " . $dgBannerImage['ar']);
        }

        // Handle DG Banner Image English
        $dgEnPath = $this->handleImageUpload($request, 'compressed_dg_banner_image_en', 'dg_banner_image_en', 'home-page/dg-banner');
        if ($dgEnPath) {
            $dgBannerImage['en'] = $dgEnPath;
            Log::info("DG Banner (EN) image uploaded: " . $dgBannerImage['en']);
        }

        $data['dg_banner_image'] = $dgBannerImage;

        // Handle DG Banner Tablet Images
        $currentDgTabletImage = $homePage->dg_banner_image_tablet ?? ['ar' => '', 'en' => ''];
        $dgBannerTabletImage = [
            'ar' => is_array($currentDgTabletImage) ? ($currentDgTabletImage['ar'] ?? '') : '',
            'en' => is_array($currentDgTabletImage) ? ($currentDgTabletImage['en'] ?? '') : '',
        ];

        $dgTabletArPath = $this->handleImageUpload($request, 'compressed_dg_banner_image_tablet_ar', 'dg_banner_image_tablet_ar', 'home-page/dg-banner/tablet');
        if ($dgTabletArPath) {
            $dgBannerTabletImage['ar'] = $dgTabletArPath;
        }

        $dgTabletEnPath = $this->handleImageUpload($request, 'compressed_dg_banner_image_tablet_en', 'dg_banner_image_tablet_en', 'home-page/dg-banner/tablet');
        if ($dgTabletEnPath) {
            $dgBannerTabletImage['en'] = $dgTabletEnPath;
        }

        $data['dg_banner_image_tablet'] = $dgBannerTabletImage;

        // Handle DG Banner Mobile Images
        $currentDgMobileImage = $homePage->dg_banner_image_mobile ?? ['ar' => '', 'en' => ''];
        $dgBannerMobileImage = [
            'ar' => is_array($currentDgMobileImage) ? ($currentDgMobileImage['ar'] ?? '') : '',
            'en' => is_array($currentDgMobileImage) ? ($currentDgMobileImage['en'] ?? '') : '',
        ];

        $dgMobileArPath = $this->handleImageUpload($request, 'compressed_dg_banner_image_mobile_ar', 'dg_banner_image_mobile_ar', 'home-page/dg-banner/mobile');
        if ($dgMobileArPath) {
            $dgBannerMobileImage['ar'] = $dgMobileArPath;
        }

        $dgMobileEnPath = $this->handleImageUpload($request, 'compressed_dg_banner_image_mobile_en', 'dg_banner_image_mobile_en', 'home-page/dg-banner/mobile');
        if ($dgMobileEnPath) {
            $dgBannerMobileImage['en'] = $dgMobileEnPath;
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
        $gucciArPath = $this->handleImageUpload($request, 'compressed_gucci_spotlight_image_ar', 'gucci_spotlight_image_ar', 'home-page/gucci-spotlight');
        if ($gucciArPath) {
            $gucciSpotlightImage['ar'] = $gucciArPath;
            Log::info("Gucci Spotlight (AR) image uploaded: " . $gucciSpotlightImage['ar']);
        }

        // Handle Gucci Spotlight Image English
        $gucciEnPath = $this->handleImageUpload($request, 'compressed_gucci_spotlight_image_en', 'gucci_spotlight_image_en', 'home-page/gucci-spotlight');
        if ($gucciEnPath) {
            $gucciSpotlightImage['en'] = $gucciEnPath;
            Log::info("Gucci Spotlight (EN) image uploaded: " . $gucciSpotlightImage['en']);
        }

        $data['gucci_spotlight_image'] = $gucciSpotlightImage;

        // Handle Gucci Spotlight Tablet Images
        $currentGucciTabletImage = $homePage->gucci_spotlight_image_tablet ?? ['ar' => '', 'en' => ''];
        $gucciSpotlightTabletImage = [
            'ar' => is_array($currentGucciTabletImage) ? ($currentGucciTabletImage['ar'] ?? '') : '',
            'en' => is_array($currentGucciTabletImage) ? ($currentGucciTabletImage['en'] ?? '') : '',
        ];

        $gucciTabletArPath = $this->handleImageUpload($request, 'compressed_gucci_spotlight_image_tablet_ar', 'gucci_spotlight_image_tablet_ar', 'home-page/gucci-spotlight/tablet');
        if ($gucciTabletArPath) {
            $gucciSpotlightTabletImage['ar'] = $gucciTabletArPath;
        }

        $gucciTabletEnPath = $this->handleImageUpload($request, 'compressed_gucci_spotlight_image_tablet_en', 'gucci_spotlight_image_tablet_en', 'home-page/gucci-spotlight/tablet');
        if ($gucciTabletEnPath) {
            $gucciSpotlightTabletImage['en'] = $gucciTabletEnPath;
        }

        $data['gucci_spotlight_image_tablet'] = $gucciSpotlightTabletImage;

        // Handle Gucci Spotlight Mobile Images
        $currentGucciMobileImage = $homePage->gucci_spotlight_image_mobile ?? ['ar' => '', 'en' => ''];
        $gucciSpotlightMobileImage = [
            'ar' => is_array($currentGucciMobileImage) ? ($currentGucciMobileImage['ar'] ?? '') : '',
            'en' => is_array($currentGucciMobileImage) ? ($currentGucciMobileImage['en'] ?? '') : '',
        ];

        $gucciMobileArPath = $this->handleImageUpload($request, 'compressed_gucci_spotlight_image_mobile_ar', 'gucci_spotlight_image_mobile_ar', 'home-page/gucci-spotlight/mobile');
        if ($gucciMobileArPath) {
            $gucciSpotlightMobileImage['ar'] = $gucciMobileArPath;
        }

        $gucciMobileEnPath = $this->handleImageUpload($request, 'compressed_gucci_spotlight_image_mobile_en', 'gucci_spotlight_image_mobile_en', 'home-page/gucci-spotlight/mobile');
        if ($gucciMobileEnPath) {
            $gucciSpotlightMobileImage['en'] = $gucciMobileEnPath;
        }

        $data['gucci_spotlight_image_mobile'] = $gucciSpotlightMobileImage;
        $data['gucci_spotlight_link'] = $request->input('gucci_spotlight_link', '#');
        $data['gucci_spotlight_active'] = $request->boolean('gucci_spotlight_active');
        Log::info('Gucci Spotlight saved: ' . json_encode($gucciSpotlightImage));

        // Handle Featured Banner
        $featuredPath = $this->handleImageUpload($request, 'compressed_featured_banner_image', 'featured_banner_image', 'home-page/featured');
        if ($featuredPath) {
            $data['featured_banner_image'] = $featuredPath;
        } else {
            $data['featured_banner_image'] = $request->input('featured_banner_image_current');
        }

        // Handle Featured Banner Tablet Image
        $featuredTabletPath = $this->handleImageUpload($request, 'compressed_featured_banner_image_tablet', 'featured_banner_image_tablet', 'home-page/featured/tablet');
        if ($featuredTabletPath) {
            $data['featured_banner_image_tablet'] = $featuredTabletPath;
        } else {
            $data['featured_banner_image_tablet'] = $request->input('featured_banner_image_tablet_current');
        }

        // Handle Featured Banner Mobile Image
        $featuredMobilePath = $this->handleImageUpload($request, 'compressed_featured_banner_image_mobile', 'featured_banner_image_mobile', 'home-page/featured/mobile');
        if ($featuredMobilePath) {
            $data['featured_banner_image_mobile'] = $featuredMobilePath;
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
        $spotlightPath = $this->handleImageUpload($request, 'compressed_spotlight_banner_image', 'spotlight_banner_image', 'home-page/spotlight');
        if ($spotlightPath) {
            $data['spotlight_banner_image'] = $spotlightPath;
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

                // Handle image upload with compression
                $discoverImagePath = $this->handleImageUpload($request, "compressed_discover_image.{$index}", "discover_image.{$index}", 'home-page/discover');
                if ($discoverImagePath) {
                    $itemData['image'] = $discoverImagePath;
                    Log::info("Discover image uploaded", ['index' => $index, 'path' => $itemData['image']]);
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

        // Handle Membership Image upload with compression
        $membershipPath = $this->handleImageUpload($request, 'compressed_membership_image', 'membership_image', 'home-page/membership');
        if ($membershipPath) {
            $data['membership_image'] = $membershipPath;
            Log::info("Membership image uploaded: " . $data['membership_image']);
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

        // Handle Google Play Badge Image with compression
        $androidPath = $this->handleImageUpload($request, 'compressed_google_play_image', 'google_play_image', 'home-page/app/android');
        if ($androidPath) {
            $appImages['android'] = $androidPath;
            Log::info("Google Play badge image uploaded: " . $appImages['android']);
        } else {
            $appImages['android'] = $request->input('google_play_image_current', '');
        }

        // Handle App Store Badge Image with compression
        $iosPath = $this->handleImageUpload($request, 'compressed_app_store_image', 'app_store_image', 'home-page/app/ios');
        if ($iosPath) {
            $appImages['ios'] = $iosPath;
            Log::info("App Store badge image uploaded: " . $appImages['ios']);
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
