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
        //
        Schema::table('customers', function (Blueprint $table) {
        $table->dropUnique(['phone']);
        $table->dropUnique(['email']);

            // 2. إضافة قيد فريد مركب (رقم الجوال والإيميل لا يتكرران *داخل نفس المطعم فقط*)
        $table->unique(['restaurant_id', 'phone'], 'restaurant_phone_unique');
        $table->unique(['restaurant_id', 'email'], 'restaurant_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
        //
        $table->dropUnique('restaurant_phone_unique');
        $table->dropUnique('restaurant_email_unique');
            
        $table->unique('phone');
        $table->unique('email');
        });
    }
};
