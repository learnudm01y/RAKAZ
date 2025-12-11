<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('home_pages', function (Blueprint $table) {
            $table->id();

            // Hero Banner Slides (JSON array of objects with image and link)
            $table->json('hero_slides')->nullable();

            // Cyber Sale Banner Section
            $table->string('cyber_sale_image')->nullable();
            $table->string('cyber_sale_link')->nullable();
            $table->json('cyber_sale_title')->nullable(); // {ar: '', en: ''}
            $table->json('cyber_sale_button_text')->nullable();
            $table->boolean('cyber_sale_active')->default(true);

            // Gifts Section
            $table->json('gifts_section_title')->nullable(); // {ar: '', en: ''}
            $table->json('gifts_items')->nullable(); // Array of {image, title_ar, title_en, link}
            $table->boolean('gifts_section_active')->default(true);

            // Featured Banner (Dolce & Gabbana Position)
            $table->string('featured_banner_image')->nullable();
            $table->json('featured_banner_title')->nullable();
            $table->json('featured_banner_subtitle')->nullable();
            $table->string('featured_banner_link')->nullable();
            $table->json('featured_banner_button_text')->nullable();
            $table->boolean('featured_banner_active')->default(true);

            // Must Have Section Title
            $table->json('must_have_section_title')->nullable();
            $table->boolean('must_have_section_active')->default(true);

            // Spotlight Banner (Gucci Position)
            $table->string('spotlight_banner_image')->nullable();
            $table->json('spotlight_banner_title')->nullable();
            $table->json('spotlight_banner_subtitle')->nullable();
            $table->string('spotlight_banner_link')->nullable();
            $table->json('spotlight_banner_button_text')->nullable();
            $table->boolean('spotlight_banner_active')->default(true);

            // Discover Section
            $table->json('discover_section_title')->nullable();
            $table->json('discover_items')->nullable(); // Array of {image, title_ar, title_en, link, type: 'small'|'wide'}
            $table->boolean('discover_section_active')->default(true);

            // Perfect Present Section Title
            $table->json('perfect_present_section_title')->nullable();
            $table->boolean('perfect_present_section_active')->default(true);

            // Membership Section
            $table->json('membership_logo_text')->nullable();
            $table->json('membership_description')->nullable();
            $table->json('membership_button_text')->nullable();
            $table->string('membership_button_link')->nullable();
            $table->boolean('membership_section_active')->default(true);

            // App Download Section
            $table->json('app_download_title')->nullable();
            $table->json('app_download_subtitle')->nullable();
            $table->string('app_store_link')->nullable();
            $table->string('google_play_link')->nullable();
            $table->boolean('app_section_active')->default(true);

            // Meta Information
            $table->string('locale')->default('ar');
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_pages');
    }
};
