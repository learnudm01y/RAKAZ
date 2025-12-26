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
        // جدول الزيارات
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('page_url')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->date('visit_date');
            $table->timestamps();

            $table->index('visit_date');
            $table->index('ip_address');
        });

        // جدول المستخدمين المتصلين
        Schema::create('online_users', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->string('ip_address', 45);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamp('last_activity');
            $table->timestamps();

            $table->index('last_activity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_users');
        Schema::dropIfExists('site_visits');
    }
};
