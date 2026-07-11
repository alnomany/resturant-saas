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
  
        Schema::create('coupons', function (Blueprint $table) {

    $table->id();

    $table->foreignId('restaurant_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches')
        ->nullOnDelete();

    $table->string('code');

    $table->string('type'); // percentage | fixed | free_delivery

    $table->decimal('value', 10, 2);

    $table->decimal('minimum_amount', 10, 2)->default(0);

    $table->decimal('maximum_discount', 10, 2)->nullable();

    $table->unsignedInteger('usage_limit')->nullable();

    $table->unsignedInteger('used')->default(0);

    $table->timestamp('starts_at')->nullable();

    $table->timestamp('expires_at')->nullable();

    $table->boolean('is_active')->default(true);

    $table->timestamps();

    $table->unique(['restaurant_id', 'code']);

    });
  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
