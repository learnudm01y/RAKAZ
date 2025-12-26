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
        Schema::table('brands', function (Blueprint $table) {
            // Drop old name column and add multilingual names
            $table->dropColumn('name');
            $table->string('name_ar')->after('id');
            $table->string('name_en')->after('name_ar');

            // Drop logo column
            $table->dropColumn('logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(['name_ar', 'name_en']);
            $table->string('name')->after('id');
            $table->string('logo')->nullable()->after('description');
        });
    }
};
