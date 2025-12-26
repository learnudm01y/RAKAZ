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
        if (!Schema::hasTable('featured_section')) {
            Schema::create('featured_section', function (Blueprint $table) {
                $table->id();
                $table->json('title'); // {ar: 'المنتجات المميزة', en: 'Featured Products'}
                $table->string('link_url')->default('/shop');
                $table->json('link_text'); // {ar: 'تسوق الكل', en: 'Shop All'}
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Pivot table for featured products
        if (!Schema::hasTable('featured_section_products')) {
            Schema::create('featured_section_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('featured_section_id');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('order')->default(0);
                $table->timestamps();

                $table->foreign('featured_section_id')
                    ->references('id')->on('featured_section')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_section_products');
        Schema::dropIfExists('featured_section');
    }
};
