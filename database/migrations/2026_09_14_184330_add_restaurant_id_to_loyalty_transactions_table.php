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
        Schema::table('loyalty_transactions', function (Blueprint $table) {
            //
            // نضعه بعد customer_id
        $table->foreignId('restaurant_id')
              ->nullable()
              ->after('customer_id')
              ->constrained('restaurants') // أو اسم جدول المطاعم لديك
              ->cascadeOnDelete();
    });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loyalty_transactions', function (Blueprint $table) {
            //
        });
    }
};
