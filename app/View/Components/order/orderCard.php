<?php

namespace App\View\Components\order;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class orderCard extends Component
{

    public $order;
    public $branch;
    public $distance;

public function mount($order)
{
    $this->order = $order;
    
    // 1. جلب الفرع المرتبط بالطلب أو الفرع الحالي
    $this->branch = $order->branch ?? branch();

    // 2. إحداثيات الفرع والعميل
    $branchLat = $this->branch?->lat;
    $branchLong = $this->branch?->long;

    // افترضنا أن عنوان التوصيل يحتوي على الإحداثيات
    $customerLat = $order->address?->lat ?? $order->lat;
    $customerLong = $order->address?->long ?? $order->long;

    // 3. حساب المسافة
    if ($branchLat && $branchLong && $customerLat && $customerLong) {
        $calculatedDistance = $this->calculateDistance($branchLat, $branchLong, $customerLat, $customerLong);
        $this->distance = round($calculatedDistance, 2); // تقريب لرقامين بعد الفاصلة
    } else {
        $this->distance = null;
    }
}
    /**
     * Create a new component instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->branch = $order->branch; // جلب الفرع مباشرة من الطلب
        $this->distance = $this->calculateDistance();
    }
    private function calculateDistance()
    {
        // التحقق من وجود بيانات الفرع والعنوان وإحداثياتهما
        $branchLat = $this->branch?->lat ?? $this->branch?->latitude;
        $branchLng = $this->branch?->lng ?? $this->branch?->longitude;

        $addressLat = $this->order->customer_lat ?? $this->order->lat;
        $addressLng = $this->order->customer_lng ?? $this->order->lng;

        if (!$branchLat || !$branchLng || !$addressLat || !$addressLng) {
            return null;
        }
        // 🔴 طباعة الفحص التشخيصي المباشر
  throw new \Exception(json_encode([
    'order_id'      => $this->order->id ?? 'غير موجود',
    'branch_lat'    => $this->branch?->lat ?? $this->branch?->latitude,
    'branch_lng'    => $this->branch?->lng ?? $this->branch?->longitude,
    'customer_lat'  => $this->order->customer_lat ?? $this->order->lat ?? $this->order->address?->lat,
    'customer_lng'  => $this->order->customer_lng ?? $this->order->lng ?? $this->order->address?->lng,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return $this->haversineDistance($branchLat, $branchLng, $addressLat, $addressLng);
    }
   
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // نصف قطر الأرض بالكيلومتر

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2); // إرجاع المسافة مقربة لأقرب منزلتين
    }

    /**
     * Get the view / contents that represent the component.
     */
public function render(): View|Closure|string
    {
  throw new \Exception(json_encode([
    'order_id'      => $this->order->id ?? 'غير موجود',
    'branch_lat'    => $this->branch?->lat ?? $this->branch?->latitude,
    'branch_lng'    => $this->branch?->lng ?? $this->branch?->longitude,
    'customer_lat'  => $this->order->customer_lat ?? $this->order->lat ?? $this->order->address?->lat,
    'customer_lng'  => $this->order->customer_lng ?? $this->order->lng ?? $this->order->address?->lng,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return view('components.order.order-card', [
            'order' => $this->order,
            'branch' => $this->branch,
            'distance' => $this->distance,
        ]);
    }

}
