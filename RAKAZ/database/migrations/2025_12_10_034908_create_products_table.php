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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic Information (Multilingual JSON fields)
            $table->json('name'); // {ar: "", en: ""}
            $table->json('slug'); // {ar: "", en: ""}
            $table->json('description')->nullable(); // {ar: "", en: ""}

            // Category
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            // Pricing
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('cost', 10, 2)->nullable(); // Cost price for profit calculation

            // Stock Management
            $table->string('sku')->unique()->nullable(); // Stock Keeping Unit
            $table->integer('stock_quantity')->default(0);
            $table->boolean('manage_stock')->default(true);
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'on_backorder'])->default('in_stock');
            $table->integer('low_stock_threshold')->nullable();

            // Images
            $table->string('main_image')->nullable();
            $table->json('gallery_images')->nullable(); // Array of image paths

            // Product Attributes
            $table->json('weight')->nullable(); // {value: "", unit: "kg/g"}
            $table->json('dimensions')->nullable(); // {length: "", width: "", height: "", unit: "cm"}
            $table->json('colors')->nullable(); // Array of available colors
            $table->json('sizes')->nullable(); // Array of available sizes

            // SEO
            $table->json('meta_title')->nullable(); // {ar: "", en: ""}
            $table->json('meta_description')->nullable(); // {ar: "", en: ""}
            $table->json('meta_keywords')->nullable(); // {ar: "", en: ""}

            // Product Status & Visibility
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_on_sale')->default(false);

            // Sorting & Organization
            $table->integer('sort_order')->default(0);
            $table->json('tags')->nullable(); // Array of tags

            // Additional Information
            $table->json('specifications')->nullable(); // Custom specs {key: value}
            $table->string('brand')->nullable();
            $table->string('manufacturer')->nullable();
            $table->date('available_from')->nullable();
            $table->date('available_until')->nullable();

            // Statistics
            $table->integer('views_count')->default(0);
            $table->integer('sales_count')->default(0);
            $table->decimal('rating_average', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);

            $table->timestamps();
            $table->softDeletes(); // For soft delete capability

            // Indexes for better performance
            $table->index('category_id');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('price');
            $table->index('created_at');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
