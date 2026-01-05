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
        Schema::table('products', function (Blueprint $table) {
            // Mobile-optimized images (max 15KB each) for better mobile performance
            $table->string('mobile_main_image')->nullable()->after('main_image');
            $table->string('mobile_hover_image')->nullable()->after('hover_image');
            $table->json('mobile_gallery_images')->nullable()->after('gallery_images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['mobile_main_image', 'mobile_hover_image', 'mobile_gallery_images']);
        });
    }
};
