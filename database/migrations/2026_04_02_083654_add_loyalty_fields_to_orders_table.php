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
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->decimal('points_discount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2)->default(0);
            $table->integer('points_earned')->default(0);
            $table->integer('points_used')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
                $table->dropColumn([
                'points_discount',
                'final_amount',
                'points_earned',
                'points_used'
            ]);
        });
    }
};
