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
            $table->json('gucci_spotlight_image')->nullable()->after('dg_banner_active');
            $table->string('gucci_spotlight_link')->nullable()->after('gucci_spotlight_image');
            $table->boolean('gucci_spotlight_active')->default(true)->after('gucci_spotlight_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn(['gucci_spotlight_image', 'gucci_spotlight_link', 'gucci_spotlight_active']);
        });
    }
};
