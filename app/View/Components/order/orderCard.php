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

        $addressLat = $this->order->address?->lat ?? $this->order->lat;
        $addressLng = $this->order->address?->lng ?? $this->order->lng;

        if (!$branchLat || !$branchLng || !$addressLat || !$addressLng) {
            return null;
        }

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
        return view('components.order.order-card');
    }

}
