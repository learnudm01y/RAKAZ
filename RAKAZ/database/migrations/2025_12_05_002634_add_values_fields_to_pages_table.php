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
        Schema::table('pages', function (Blueprint $table) {
            // Value 1
            $table->string('value1_title_ar')->nullable();
            $table->string('value1_title_en')->nullable();
            $table->text('value1_description_ar')->nullable();
            $table->text('value1_description_en')->nullable();
            $table->string('value1_icon')->nullable();

            // Value 2
            $table->string('value2_title_ar')->nullable();
            $table->string('value2_title_en')->nullable();
            $table->text('value2_description_ar')->nullable();
            $table->text('value2_description_en')->nullable();
            $table->string('value2_icon')->nullable();

            // Value 3
            $table->string('value3_title_ar')->nullable();
            $table->string('value3_title_en')->nullable();
            $table->text('value3_description_ar')->nullable();
            $table->text('value3_description_en')->nullable();
            $table->string('value3_icon')->nullable();

            // Value 4
            $table->string('value4_title_ar')->nullable();
            $table->string('value4_title_en')->nullable();
            $table->text('value4_description_ar')->nullable();
            $table->text('value4_description_en')->nullable();
            $table->string('value4_icon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'value1_title_ar', 'value1_title_en', 'value1_description_ar', 'value1_description_en', 'value1_icon',
                'value2_title_ar', 'value2_title_en', 'value2_description_ar', 'value2_description_en', 'value2_icon',
                'value3_title_ar', 'value3_title_en', 'value3_description_ar', 'value3_description_en', 'value3_icon',
                'value4_title_ar', 'value4_title_en', 'value4_description_ar', 'value4_description_en', 'value4_icon',
            ]);
        });
    }
};
