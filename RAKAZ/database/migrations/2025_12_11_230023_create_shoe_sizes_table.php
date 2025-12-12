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
        Schema::create('shoe_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('size'); // EU size (e.g., "36", "37.5")
            $table->json('name_translations')->nullable(); // For display names in AR/EN
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('product_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoe_sizes');
    }
};
