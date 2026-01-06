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
        // Footer Sections (main columns)
        Schema::create('footer_sections', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // {"ar": "...", "en": "..."}
            $table->string('type')->default('custom'); // custom, menu, category
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Footer Items (links within sections)
        Schema::create('footer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('footer_section_id')->constrained()->onDelete('cascade');
            $table->json('title'); // {"ar": "...", "en": "..."}
            $table->string('link')->nullable();
            $table->string('link_type')->default('custom'); // custom, route, menu, category
            $table->string('route_name')->nullable();
            $table->foreignId('menu_id')->nullable()->constrained('menus')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Footer Settings (general settings)
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        // Footer Social Links
        Schema::create('footer_social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // facebook, twitter, instagram, whatsapp, linkedin, tiktok
            $table->string('url');
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_social_links');
        Schema::dropIfExists('footer_settings');
        Schema::dropIfExists('footer_items');
        Schema::dropIfExists('footer_sections');
    }
};
