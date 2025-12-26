<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order'], 'menus_is_active_sort_order_idx');
        });

        Schema::table('menu_columns', function (Blueprint $table) {
            $table->index(['menu_id', 'is_active', 'sort_order'], 'menu_columns_menu_active_sort_idx');
        });

        Schema::table('menu_column_items', function (Blueprint $table) {
            $table->index(['menu_column_id', 'is_active', 'sort_order'], 'menu_items_col_active_sort_idx');
            $table->index(['category_id', 'is_active'], 'menu_items_category_active_idx');
        });
    }

    public function down(): void
    {
        Schema::table('menu_column_items', function (Blueprint $table) {
            $table->dropIndex('menu_items_col_active_sort_idx');
            $table->dropIndex('menu_items_category_active_idx');
        });

        Schema::table('menu_columns', function (Blueprint $table) {
            $table->dropIndex('menu_columns_menu_active_sort_idx');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropIndex('menus_is_active_sort_order_idx');
        });
    }
};
