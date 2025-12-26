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
        // جدول مشاهدات الصفحات (كل تحميل للصفحة)
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('session_id')->nullable();
            $table->string('page_url')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->date('view_date');
            $table->timestamps();

            $table->index('view_date');
            $table->index(['ip_address', 'view_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
