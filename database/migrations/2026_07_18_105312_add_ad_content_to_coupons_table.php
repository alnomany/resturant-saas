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
        Schema::table('coupons', function (Blueprint $table) {
            //
            $table->string('ad_title')->nullable()->after('is_active');        // عنوان الإعلان
            $table->text('ad_description')->nullable()->after('ad_title');    // نص الإعلان
            $table->string('ad_image')->nullable()->after('ad_description');  // صورة الإعلان
            $table->string('button_text')->default('استخدم الكود')->after('ad_image'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            //
        });
    }
};
