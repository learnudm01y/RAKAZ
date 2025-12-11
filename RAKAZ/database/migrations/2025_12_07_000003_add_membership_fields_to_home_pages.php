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
        Schema::table('home_pages', function (Blueprint $table) {
            // Drop old membership columns if they exist
            if (Schema::hasColumn('home_pages', 'membership_logo_text')) {
                $table->dropColumn([
                    'membership_logo_text',
                    'membership_description',
                    'membership_button_text',
                    'membership_button_link'
                ]);
            }

            // Add new membership columns
            $table->json('membership_title')->nullable()->after('perfect_present_section_active');
            $table->json('membership_desc')->nullable()->after('membership_title');
            $table->string('membership_link')->default('#')->after('membership_desc');
            $table->string('membership_image')->nullable()->after('membership_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn([
                'membership_title',
                'membership_desc',
                'membership_link',
                'membership_image'
            ]);

            // Restore old columns
            $table->json('membership_logo_text')->nullable();
            $table->json('membership_description')->nullable();
            $table->json('membership_button_text')->nullable();
            $table->string('membership_button_link')->nullable();
        });
    }
};
