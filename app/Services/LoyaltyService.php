<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use App\Models\LoyaltyTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoyaltyService
{
    protected float $pointValue;
    protected float $earnRate;

    public function __construct()
    {
        $this->pointValue = config('loyalty.point_value', 1); // قيمة النقطة بالريال
        $this->earnRate   = config('loyalty.earn_rate', 0.10); // نسبة الكسب 10%
    }

    /**
     * حساب رصيد العميل لمطعم محدد
     */
    public function getCustomerBalanceForRestaurant(int $customerId, int $restaurantId): int
    {
        $earned = LoyaltyTransaction::where('customer_id', $customerId)
            ->where('restaurant_id', $restaurantId)
            ->where('type', 'earn')
            ->sum('points');

        $redeemed = LoyaltyTransaction::where('customer_id', $customerId)
            ->where('restaurant_id', $restaurantId)
            ->where('type', 'redeem')
            ->sum('points');

        return max(0, $earned - abs($redeemed));
    }

    /**
     * استخدام نقاط الولاء وتطبيق الخصم للطلب
     */
    public function applyPoints(Customer $customer, Order $order, int $pointsToRedeem): Order
    {
        return DB::transaction(function () use ($customer, $order, $pointsToRedeem) {

            // جلب الرصيد الحقيقي المتاح للعميل في هذا المطعم تحديداً
            $availableBalance = $this->getCustomerBalanceForRestaurant($customer->id, $order->restaurant_id);

            // التأكد من عدم تجاوز الرصيد المتاح
            $pointsToUse = min($pointsToRedeem, $availableBalance);

            if ($pointsToUse <= 0) {
                return $order;
            }

            // تحويل النقاط لخصم مالي (بحد أقصى المجموع الفرعي للطلب)
            $discount = min($pointsToUse * $this->pointValue, $order->subtotal);

            // تحديث الطلب
            $order->points_discount = $discount;
            $order->final_amount = max($order->subtotal - $discount, 0);
            $order->save();

            // تسجيل حركة الخصم (تسجل بقيمة سالبة لضبط العمليات الحسابية)
            $this->logTransaction(
                customerId: $customer->id,
                restaurantId: $order->restaurant_id,
                orderId: $order->id,
                type: 'redeem',
                points: $pointsToUse, // السيرفس سيتكفل بتحويلها إلى قيمة سالبة
                description: "Redeemed {$pointsToUse} points for order #{$order->id}"
            );

            Log::info("Customer {$customer->id} used {$pointsToUse} points on order #{$order->id} for restaurant #{$order->restaurant_id}");

            return $order;
        });
    }

    /**
     * حساب النقاط المكتسبة وإضافتها لرصيد العميل للمطعم
     */
    public function calculateEarnedPoints(Order $order): int
    {
        return DB::transaction(function () use ($order) {

            // النقاط = نسبة من الصافي المدفوع
            $earned = (int) floor($order->final_amount * $this->earnRate);

            if ($earned <= 0) {
                return 0;
            }

            // تحديث الطلب
            $order->points_earned = $earned;
            $order->save();

            // تسجيل الحركة وإسنادها للمطعم
            $this->logTransaction(
                customerId: $order->customer_id,
                restaurantId: $order->restaurant_id,
                orderId: $order->id,
                type: 'earn',
                points: $earned,
                description: "Earned {$earned} points from order #{$order->id}",
                expiresAt: now()->addMonths(12)
            );

            Log::info("Points Earned: Customer {$order->customer_id} earned {$earned} points for restaurant #{$order->restaurant_id}.");

            return $earned;
        });
    }

    /**
     * تسجيل حركة نقاط
     */
    protected function logTransaction(
        int $customerId,
        int $restaurantId,
        int $orderId,
        string $type,
        int $points,
        string $description,
        $expiresAt = null
    ) {
        // إذا كانت الحركة خصم، نقوم بحفظها بقيمة سالبة
        $finalPoints = ($type === 'redeem') ? -abs($points) : abs($points);

        return LoyaltyTransaction::create([
            'customer_id'   => $customerId,
            'restaurant_id' => $restaurantId,
            'order_id'      => $orderId,
            'type'          => $type,
            'points'        => $finalPoints,
            'description'   => $description,
            'expires_at'    => $expiresAt,
        ]);
    }
}