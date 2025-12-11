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
            $table->json('dg_banner_image')->nullable()->after('gifts_section_active');
            $table->string('dg_banner_link')->nullable()->after('dg_banner_image');
            $table->boolean('dg_banner_active')->default(true)->after('dg_banner_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn(['dg_banner_image', 'dg_banner_link', 'dg_banner_active']);
        });
    }
};
