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
        Schema::table('menu_items', function (Blueprint $table) {
            //
            // تحديد هل العنصر عليه عرض خاص / مستثنى من الكوبونات
            $table->boolean('is_on_sale')->default(false)->after('price'); 
            // نص البادج اختياري (مثل: عرض خاص، 20% خصم)
            $table->string('badge_text')->nullable()->after('is_on_sale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            //
            $table->dropColumn(['is_on_sale', 'badge_text']);
        });
    }
};
