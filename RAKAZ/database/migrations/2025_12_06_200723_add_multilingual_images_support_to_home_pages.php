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
            // Add multilingual support for Cyber Sale images
            $table->string('cyber_sale_image_ar')->nullable()->after('cyber_sale_image');
            $table->string('cyber_sale_image_en')->nullable()->after('cyber_sale_image_ar');

            // Add multilingual support for Spotlight Banner images
            $table->string('spotlight_banner_image_ar')->nullable()->after('spotlight_banner_image');
            $table->string('spotlight_banner_image_en')->nullable()->after('spotlight_banner_image_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn([
                'cyber_sale_image_ar',
                'cyber_sale_image_en',
                'spotlight_banner_image_ar',
                'spotlight_banner_image_en'
            ]);
        });
    }
};
