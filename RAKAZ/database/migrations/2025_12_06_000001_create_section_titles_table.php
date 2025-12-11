<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('section_titles', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique(); // e.g., 'gifts_section', 'spotlight_section', etc.
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Insert default data for gifts section
        DB::table('section_titles')->insert([
            'section_key' => 'gifts_section',
            'title_ar' => 'الهدايا',
            'title_en' => 'Gifts',
            'active' => true,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_titles');
    }
};
