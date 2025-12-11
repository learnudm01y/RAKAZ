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
        Schema::create('contact_pages', function (Blueprint $table) {
            $table->id();

            // Hero Section
            $table->string('hero_title_ar')->nullable();
            $table->string('hero_title_en')->nullable();
            $table->text('hero_subtitle_ar')->nullable();
            $table->text('hero_subtitle_en')->nullable();

            // Contact Information
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address_ar')->nullable();
            $table->text('address_en')->nullable();
            $table->string('map_url')->nullable();

            // Working Hours
            $table->string('working_hours_title_ar')->nullable();
            $table->string('working_hours_title_en')->nullable();
            $table->text('working_hours_ar')->nullable();
            $table->text('working_hours_en')->nullable();

            // Social Media
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('whatsapp')->nullable();

            // Additional Info
            $table->longText('additional_info_ar')->nullable();
            $table->longText('additional_info_en')->nullable();

            // Meta Tags
            $table->string('meta_description_ar')->nullable();
            $table->string('meta_description_en')->nullable();
            $table->string('meta_keywords_ar')->nullable();
            $table->string('meta_keywords_en')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_pages');
    }
};
