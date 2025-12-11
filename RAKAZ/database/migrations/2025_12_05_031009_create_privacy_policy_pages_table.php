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
        Schema::create('privacy_policy_pages', function (Blueprint $table) {
            $table->id();

            // Hero Section
            $table->string('hero_title_ar')->nullable();
            $table->string('hero_title_en')->nullable();
            $table->text('hero_subtitle_ar')->nullable();
            $table->text('hero_subtitle_en')->nullable();

            // Content
            $table->longText('content_ar')->nullable();
            $table->longText('content_en')->nullable();

            // Sections (Introduction, Data Collection, Usage, etc.)
            $table->text('section_1_title_ar')->nullable();
            $table->text('section_1_title_en')->nullable();
            $table->longText('section_1_content_ar')->nullable();
            $table->longText('section_1_content_en')->nullable();

            $table->text('section_2_title_ar')->nullable();
            $table->text('section_2_title_en')->nullable();
            $table->longText('section_2_content_ar')->nullable();
            $table->longText('section_2_content_en')->nullable();

            $table->text('section_3_title_ar')->nullable();
            $table->text('section_3_title_en')->nullable();
            $table->longText('section_3_content_ar')->nullable();
            $table->longText('section_3_content_en')->nullable();

            $table->text('section_4_title_ar')->nullable();
            $table->text('section_4_title_en')->nullable();
            $table->longText('section_4_content_ar')->nullable();
            $table->longText('section_4_content_en')->nullable();

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
        Schema::dropIfExists('privacy_policy_pages');
    }
};
