<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'orders_status_created_idx');
            $table->index(['payment_status', 'created_at'], 'orders_payment_created_idx');
            $table->index(['user_id', 'created_at'], 'orders_user_created_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'products_active_sort_idx');
            $table->index(['category_id', 'is_active', 'sort_order'], 'products_cat_active_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_sort_idx');
            $table->dropIndex('products_cat_active_sort_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_created_idx');
            $table->dropIndex('orders_payment_created_idx');
            $table->dropIndex('orders_user_created_idx');
        });
    }
};
