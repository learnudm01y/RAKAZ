<?php

namespace App\Livewire\Forms;

use App\Models\HomePage;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class HomePageForm extends Form
{
    public ?HomePage $homePage;

    // Hero Slides
    public $hero_slides = [];
    public $new_hero_image;

    // Cyber Sale
    public $cyber_sale_image;
    public $cyber_sale_link = '#';
    public $cyber_sale_title_ar;
    public $cyber_sale_title_en;
    public $cyber_sale_button_text_ar;
    public $cyber_sale_button_text_en;
    public $cyber_sale_active = true;

    // Gifts Section
    public $gifts_section_title_ar;
    public $gifts_section_title_en;
    public $gifts_items = [];
    public $gifts_section_active = true;

    // Featured Banner
    public $featured_banner_image;
    public $featured_banner_link = '#';
    public $featured_banner_title_ar;
    public $featured_banner_title_en;
    public $featured_banner_subtitle_ar;
    public $featured_banner_subtitle_en;
    public $featured_banner_button_text_ar;
    public $featured_banner_button_text_en;
    public $featured_banner_active = true;

    // Must Have Section
    public $must_have_section_title_ar;
    public $must_have_section_title_en;
    public $must_have_section_active = true;

    // Spotlight Banner
    public $spotlight_banner_image;
    public $spotlight_banner_link = '#';
    public $spotlight_banner_title_ar;
    public $spotlight_banner_title_en;
    public $spotlight_banner_subtitle_ar;
    public $spotlight_banner_subtitle_en;
    public $spotlight_banner_button_text_ar;
    public $spotlight_banner_button_text_en;
    public $spotlight_banner_active = true;

    // Discover Section
    public $discover_section_title_ar;
    public $discover_section_title_en;
    public $discover_items = [];
    public $discover_section_active = true;

    // Perfect Present Section
    public $perfect_present_section_title_ar;
    public $perfect_present_section_title_en;
    public $perfect_present_section_active = true;

    // Membership Section
    public $membership_logo_text_ar;
    public $membership_logo_text_en;
    public $membership_description_ar;
    public $membership_description_en;
    public $membership_button_text_ar;
    public $membership_button_text_en;
    public $membership_button_link = '#';
    public $membership_section_active = true;

    // App Download Section
    public $app_download_title_ar;
    public $app_download_title_en;
    public $app_download_subtitle_ar;
    public $app_download_subtitle_en;
    public $app_store_link = '#';
    public $google_play_link = '#';
    public $app_section_active = true;

    public $locale = 'ar';
    public $is_active = true;

    public function setHomePage(HomePage $homePage)
    {
        $this->homePage = $homePage;

        // Hero Slides
        $this->hero_slides = $homePage->hero_slides ?? [];

        // Cyber Sale
        $this->cyber_sale_image = $homePage->cyber_sale_image;
        $this->cyber_sale_link = $homePage->cyber_sale_link ?? '#';
        $this->cyber_sale_title_ar = $homePage->cyber_sale_title['ar'] ?? '';
        $this->cyber_sale_title_en = $homePage->cyber_sale_title['en'] ?? '';
        $this->cyber_sale_button_text_ar = $homePage->cyber_sale_button_text['ar'] ?? '';
        $this->cyber_sale_button_text_en = $homePage->cyber_sale_button_text['en'] ?? '';
        $this->cyber_sale_active = $homePage->cyber_sale_active;

        // Gifts Section
        $this->gifts_section_title_ar = $homePage->gifts_section_title['ar'] ?? '';
        $this->gifts_section_title_en = $homePage->gifts_section_title['en'] ?? '';
        $this->gifts_items = $homePage->gifts_items ?? [];
        $this->gifts_section_active = $homePage->gifts_section_active;

        // Featured Banner
        $this->featured_banner_image = $homePage->featured_banner_image;
        $this->featured_banner_link = $homePage->featured_banner_link ?? '#';
        $this->featured_banner_title_ar = $homePage->featured_banner_title['ar'] ?? '';
        $this->featured_banner_title_en = $homePage->featured_banner_title['en'] ?? '';
        $this->featured_banner_subtitle_ar = $homePage->featured_banner_subtitle['ar'] ?? '';
        $this->featured_banner_subtitle_en = $homePage->featured_banner_subtitle['en'] ?? '';
        $this->featured_banner_button_text_ar = $homePage->featured_banner_button_text['ar'] ?? '';
        $this->featured_banner_button_text_en = $homePage->featured_banner_button_text['en'] ?? '';
        $this->featured_banner_active = $homePage->featured_banner_active;

        // Must Have Section
        $this->must_have_section_title_ar = $homePage->must_have_section_title['ar'] ?? '';
        $this->must_have_section_title_en = $homePage->must_have_section_title['en'] ?? '';
        $this->must_have_section_active = $homePage->must_have_section_active;

        // Spotlight Banner
        $this->spotlight_banner_image = $homePage->spotlight_banner_image;
        $this->spotlight_banner_link = $homePage->spotlight_banner_link ?? '#';
        $this->spotlight_banner_title_ar = $homePage->spotlight_banner_title['ar'] ?? '';
        $this->spotlight_banner_title_en = $homePage->spotlight_banner_title['en'] ?? '';
        $this->spotlight_banner_subtitle_ar = $homePage->spotlight_banner_subtitle['ar'] ?? '';
        $this->spotlight_banner_subtitle_en = $homePage->spotlight_banner_subtitle['en'] ?? '';
        $this->spotlight_banner_button_text_ar = $homePage->spotlight_banner_button_text['ar'] ?? '';
        $this->spotlight_banner_button_text_en = $homePage->spotlight_banner_button_text['en'] ?? '';
        $this->spotlight_banner_active = $homePage->spotlight_banner_active;

        // Discover Section
        $this->discover_section_title_ar = $homePage->discover_section_title['ar'] ?? '';
        $this->discover_section_title_en = $homePage->discover_section_title['en'] ?? '';
        $this->discover_items = $homePage->discover_items ?? [];
        $this->discover_section_active = $homePage->discover_section_active;

        // Perfect Present Section
        $this->perfect_present_section_title_ar = $homePage->perfect_present_section_title['ar'] ?? '';
        $this->perfect_present_section_title_en = $homePage->perfect_present_section_title['en'] ?? '';
        $this->perfect_present_section_active = $homePage->perfect_present_section_active;

        // Membership Section
        $this->membership_logo_text_ar = $homePage->membership_logo_text['ar'] ?? '';
        $this->membership_logo_text_en = $homePage->membership_logo_text['en'] ?? '';
        $this->membership_description_ar = $homePage->membership_description['ar'] ?? '';
        $this->membership_description_en = $homePage->membership_description['en'] ?? '';
        $this->membership_button_text_ar = $homePage->membership_button_text['ar'] ?? '';
        $this->membership_button_text_en = $homePage->membership_button_text['en'] ?? '';
        $this->membership_button_link = $homePage->membership_button_link ?? '#';
        $this->membership_section_active = $homePage->membership_section_active;

        // App Download Section
        $this->app_download_title_ar = $homePage->app_download_title['ar'] ?? '';
        $this->app_download_title_en = $homePage->app_download_title['en'] ?? '';
        $this->app_download_subtitle_ar = $homePage->app_download_subtitle['ar'] ?? '';
        $this->app_download_subtitle_en = $homePage->app_download_subtitle['en'] ?? '';
        $this->app_store_link = $homePage->app_store_link ?? '#';
        $this->google_play_link = $homePage->google_play_link ?? '#';
        $this->app_section_active = $homePage->app_section_active;

        $this->locale = $homePage->locale;
        $this->is_active = $homePage->is_active;
    }

    public function store()
    {
        $data = $this->prepareData();
        return HomePage::create($data);
    }

    public function update()
    {
        $data = $this->prepareData();
        $this->homePage->update($data);
        return $this->homePage;
    }

    private function prepareData()
    {
        return [
            'hero_slides' => $this->hero_slides,
            'cyber_sale_image' => $this->cyber_sale_image,
            'cyber_sale_link' => $this->cyber_sale_link,
            'cyber_sale_title' => [
                'ar' => $this->cyber_sale_title_ar,
                'en' => $this->cyber_sale_title_en,
            ],
            'cyber_sale_button_text' => [
                'ar' => $this->cyber_sale_button_text_ar,
                'en' => $this->cyber_sale_button_text_en,
            ],
            'cyber_sale_active' => $this->cyber_sale_active,
            'gifts_section_title' => [
                'ar' => $this->gifts_section_title_ar,
                'en' => $this->gifts_section_title_en,
            ],
            'gifts_items' => $this->gifts_items,
            'gifts_section_active' => $this->gifts_section_active,
            'featured_banner_image' => $this->featured_banner_image,
            'featured_banner_link' => $this->featured_banner_link,
            'featured_banner_title' => [
                'ar' => $this->featured_banner_title_ar,
                'en' => $this->featured_banner_title_en,
            ],
            'featured_banner_subtitle' => [
                'ar' => $this->featured_banner_subtitle_ar,
                'en' => $this->featured_banner_subtitle_en,
            ],
            'featured_banner_button_text' => [
                'ar' => $this->featured_banner_button_text_ar,
                'en' => $this->featured_banner_button_text_en,
            ],
            'featured_banner_active' => $this->featured_banner_active,
            'must_have_section_title' => [
                'ar' => $this->must_have_section_title_ar,
                'en' => $this->must_have_section_title_en,
            ],
            'must_have_section_active' => $this->must_have_section_active,
            'spotlight_banner_image' => $this->spotlight_banner_image,
            'spotlight_banner_link' => $this->spotlight_banner_link,
            'spotlight_banner_title' => [
                'ar' => $this->spotlight_banner_title_ar,
                'en' => $this->spotlight_banner_title_en,
            ],
            'spotlight_banner_subtitle' => [
                'ar' => $this->spotlight_banner_subtitle_ar,
                'en' => $this->spotlight_banner_subtitle_en,
            ],
            'spotlight_banner_button_text' => [
                'ar' => $this->spotlight_banner_button_text_ar,
                'en' => $this->spotlight_banner_button_text_en,
            ],
            'spotlight_banner_active' => $this->spotlight_banner_active,
            'discover_section_title' => [
                'ar' => $this->discover_section_title_ar,
                'en' => $this->discover_section_title_en,
            ],
            'discover_items' => $this->discover_items,
            'discover_section_active' => $this->discover_section_active,
            'perfect_present_section_title' => [
                'ar' => $this->perfect_present_section_title_ar,
                'en' => $this->perfect_present_section_title_en,
            ],
            'perfect_present_section_active' => $this->perfect_present_section_active,
            'membership_logo_text' => [
                'ar' => $this->membership_logo_text_ar,
                'en' => $this->membership_logo_text_en,
            ],
            'membership_description' => [
                'ar' => $this->membership_description_ar,
                'en' => $this->membership_description_en,
            ],
            'membership_button_text' => [
                'ar' => $this->membership_button_text_ar,
                'en' => $this->membership_button_text_en,
            ],
            'membership_button_link' => $this->membership_button_link,
            'membership_section_active' => $this->membership_section_active,
            'app_download_title' => [
                'ar' => $this->app_download_title_ar,
                'en' => $this->app_download_title_en,
            ],
            'app_download_subtitle' => [
                'ar' => $this->app_download_subtitle_ar,
                'en' => $this->app_download_subtitle_en,
            ],
            'app_store_link' => $this->app_store_link,
            'google_play_link' => $this->google_play_link,
            'app_section_active' => $this->app_section_active,
            'locale' => $this->locale,
            'is_active' => $this->is_active,
        ];
    }

    public function handleImageUpload($file, $directory = 'home-page')
    {
        if ($file instanceof TemporaryUploadedFile) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs($directory, $filename, 'public');
            return '/storage/' . $path;
        }
        return $file;
    }
}
