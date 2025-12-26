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
        if (!Schema::hasTable('perfect_gift_section')) {
            Schema::create('perfect_gift_section', function (Blueprint $table) {
                $table->id();
                $table->json('title'); // {ar: 'الهدية المثالية', en: 'Perfect Gift'}
                $table->string('link_url')->default('/shop');
                $table->json('link_text'); // {ar: 'تسوق الكل', en: 'Shop All'}
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Pivot table for perfect gift products
        if (!Schema::hasTable('perfect_gift_section_products')) {
            Schema::create('perfect_gift_section_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('perfect_gift_section_id');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('order')->default(0);
                $table->timestamps();

                $table->foreign('perfect_gift_section_id')
                    ->references('id')->on('perfect_gift_section')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfect_gift_section_products');
        Schema::dropIfExists('perfect_gift_section');
    }
};
