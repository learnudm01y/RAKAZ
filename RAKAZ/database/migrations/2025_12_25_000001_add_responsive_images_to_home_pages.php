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
        Schema::table('home_pages', function (Blueprint $table) {
            // Hero Slides responsive images
            $table->json('hero_slides_tablet')->nullable()->after('hero_slides');
            $table->json('hero_slides_mobile')->nullable()->after('hero_slides_tablet');

            // Cyber Sale responsive images
            $table->string('cyber_sale_image_tablet')->nullable()->after('cyber_sale_image');
            $table->string('cyber_sale_image_mobile')->nullable()->after('cyber_sale_image_tablet');

            // DG Banner responsive images
            $table->json('dg_banner_image_tablet')->nullable()->after('dg_banner_image');
            $table->json('dg_banner_image_mobile')->nullable()->after('dg_banner_image_tablet');

            // Gucci Spotlight responsive images
            $table->json('gucci_spotlight_image_tablet')->nullable()->after('gucci_spotlight_image');
            $table->json('gucci_spotlight_image_mobile')->nullable()->after('gucci_spotlight_image_tablet');

            // Featured Banner responsive images
            $table->string('featured_banner_image_tablet')->nullable()->after('featured_banner_image');
            $table->string('featured_banner_image_mobile')->nullable()->after('featured_banner_image_tablet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn([
                'hero_slides_tablet',
                'hero_slides_mobile',
                'cyber_sale_image_tablet',
                'cyber_sale_image_mobile',
                'dg_banner_image_tablet',
                'dg_banner_image_mobile',
                'gucci_spotlight_image_tablet',
                'gucci_spotlight_image_mobile',
                'featured_banner_image_tablet',
                'featured_banner_image_mobile',
            ]);
        });
    }
};
