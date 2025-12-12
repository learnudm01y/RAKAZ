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
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "XXS", "XS", "S", "M", "L", "XL", "XXL", "XXXL"
            $table->json('name_translations')->nullable(); // {"ar": "صغير جدا", "en": "XXS"}
            $table->integer('sort_order')->default(0);
            $table->integer('product_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
