<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Delete items without category_id first
        DB::table('menu_column_items')->whereNull('category_id')->delete();

        Schema::table('menu_column_items', function (Blueprint $table) {
            if (Schema::hasColumn('menu_column_items', 'custom_name')) {
                $table->dropColumn('custom_name');
            }
            if (Schema::hasColumn('menu_column_items', 'custom_link')) {
                $table->dropColumn('custom_link');
            }
        });

        // Make category_id required after deleting null records
        Schema::table('menu_column_items', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('menu_column_items', function (Blueprint $table) {
            $table->json('custom_name')->nullable();
            $table->string('custom_link')->nullable();
            $table->foreignId('category_id')->nullable()->change();
        });
    }
};
