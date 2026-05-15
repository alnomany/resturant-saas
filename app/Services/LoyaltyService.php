<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use App\Models\LoyaltyTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoyaltyService
{
    /**
     * قيمة النقطة – اجعلها متغيرة عبر env
     */
    protected float $pointValue;

    /**
     * نسبة اكتساب النقاط
     */
    protected float $earnRate;

    public function __construct()
    {
        $this->pointValue = config('loyalty.point_value', 1); // قيمة النقطة = 1 ريال
        $this->earnRate   = config('loyalty.earn_rate', 0.10); // earn 10%
    }

    /**
     * استخدم نقاط الولاء وتطبيق الخصم بشكل آمن واحترافي
     */

    //ahmed
    public function applyPoints(Customer $customer, Order $order, int $pointsToRedeem): Order
    {
        return DB::transaction(function () use ($customer, $order, $pointsToRedeem) {

            // التأكد من الرصيد
            $pointsToUse = min($pointsToRedeem, $customer->loyalty_points);

            if ($pointsToUse <= 0) {
                return $order;
            }

            // تحويل النقاط لخصم مالي
            $discount = min($pointsToUse * $this->pointValue, $order->subtotal);

            // تحديث الطلب
            $order->points_discount = $discount;
            $order->final_amount = max($order->subtotal - $discount, 0);
            $order->save();

            // تحديث رصيد العميل
            $customer->decrement('loyalty_points', $pointsToUse);

            // تسجيل الحركة
            $this->logTransaction($customer->id, $order->id, 'redeem', $pointsToUse, "Redeemed {$pointsToUse} points for order #{$order->id}");

            Log::info("Customer {$customer->id} used {$pointsToUse} points on order #{$order->id}");

            return $order;
        });
    }

    /**
     * حساب النقاط المكتسبة وإضافتها لرصيد العميل
     */
    public function calculateEarnedPoints(Order $order): int
    {
        return DB::transaction(function () use ($order) {

            // النقاط = نسبة من final_amount
            $earned = (int) floor($order->final_amount * $this->earnRate);

            if ($earned <= 0) {
                return 0;
            }

            // تحديث الطلب
            $order->points_earned = $earned;
            $order->save();

            // إضافة النقاط لرصيد العميل
            $order->customer->increment('points_balance', $earned);

            // تسجيل الحركة
            $this->logTransaction(
                customerId: $order->customer_id,
                orderId: $order->id,
                type: 'earn',
                points: $earned,
                description: "Earned {$earned} points from order #{$order->id}",
                expiresAt: now()->addMonths(12) // مثال: صلاحية سنة
            );

            Log::info("Points Earned: Customer {$order->customer_id} earned {$earned} points.");

            return $earned;
        });
    }

    /**
     * تسجيل حركة نقاط بشكل منظم
     */
    protected function logTransaction(
        int $customerId,
        int $orderId,
        string $type,
        int $points,
        string $description,
        $expiresAt = null
    ) {
        return LoyaltyTransaction::create([
            'customer_id' => $customerId,
            'order_id'    => $orderId,
            'type'        => $type,
            'points'      => $points,
            'description' => $description,
            'expires_at'  => $expiresAt,
        ]);
    }
}
