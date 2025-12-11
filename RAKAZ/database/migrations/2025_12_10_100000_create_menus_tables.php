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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // {"ar": "الكنادير", "en": "KANDORAS"}
            $table->string('image')->nullable(); // صورة القائمة في mega menu
            $table->json('image_title')->nullable(); // {"ar": "عنوان الصورة", "en": "Image Title"}
            $table->json('image_description')->nullable(); // {"ar": "وصف الصورة", "en": "Image Description"}
            $table->string('link')->nullable(); // رابط "تسوق الآن"
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->json('title'); // {"ar": "أنواع الكنادير", "en": "TYPES"}
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_column_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_column_id')->constrained('menu_columns')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
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
        Schema::dropIfExists('menu_column_items');
        Schema::dropIfExists('menu_columns');
        Schema::dropIfExists('menus');
    }
};
