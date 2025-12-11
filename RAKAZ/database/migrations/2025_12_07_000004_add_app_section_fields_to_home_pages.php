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
            // Check and add app section columns if they don't exist
            if (!Schema::hasColumn('home_pages', 'app_download_title')) {
                $table->json('app_download_title')->nullable()->after('membership_section_active');
            }
            if (!Schema::hasColumn('home_pages', 'app_download_subtitle')) {
                $table->json('app_download_subtitle')->nullable()->after('app_download_title');
            }
            if (!Schema::hasColumn('home_pages', 'app_image')) {
                $table->json('app_image')->nullable()->after('app_download_subtitle');
            }
            if (!Schema::hasColumn('home_pages', 'app_store_link')) {
                $table->string('app_store_link')->nullable()->after('app_image');
            }
            if (!Schema::hasColumn('home_pages', 'google_play_link')) {
                $table->string('google_play_link')->nullable()->after('app_store_link');
            }
            if (!Schema::hasColumn('home_pages', 'app_section_active')) {
                $table->boolean('app_section_active')->default(false)->after('google_play_link');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn([
                'app_download_title',
                'app_download_subtitle',
                'app_image',
                'app_store_link',
                'google_play_link',
                'app_section_active'
            ]);
        });
    }
};
