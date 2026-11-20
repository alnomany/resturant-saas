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
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('restaurants')->onDelete('cascade'); // معرف المطعم/المتجر
            $table->string('phone')->index(); // رقم جوال العميل
            $table->string('name')->nullable(); // اسم العميل
            $table->json('cart_data'); // محتويات السلة والأصناف والكميات
            $table->decimal('total_amount', 10, 2)->default(0); // إجمالي قيمة السلة
            $table->enum('status', ['pending', 'completed', 'recovered'])->default('pending'); // حالة السلة
            $table->timestamp('last_active_at')->useCurrent(); // آخر وقت تفاعل
            $table->timestamp('reminder_sent_at')->nullable(); // تاريخ إرسال رسالة التذكير
            // إضافة Index مركب لتسريع البحث
            $table->index(['store_id', 'phone', 'status']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abandoned_carts');
    }
};
