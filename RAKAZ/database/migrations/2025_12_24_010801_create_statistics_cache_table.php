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
        Schema::create('statistics_cache', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique(); // e.g., 'dashboard_stats', 'visitor_stats', 'monthly_sales'
            $table->string('group', 50)->default('general'); // Group: 'dashboard', 'visitors', 'orders', 'products'
            $table->json('data'); // The cached statistics data
            $table->timestamp('computed_at'); // When the stats were computed
            $table->timestamp('expires_at'); // When the cache expires
            $table->integer('ttl')->default(300); // Time to live in seconds (5 min default)
            $table->timestamps();

            $table->index(['group', 'expires_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistics_cache');
    }
};
