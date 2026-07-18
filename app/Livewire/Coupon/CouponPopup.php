<?php

namespace App\Livewire\Coupon;  // ← غيّرنا

use Livewire\Component;
use App\Models\Coupon;

class CouponPopup extends Component
{
    public $show = false;
    public $copied = false;
    public $coupon;
    
    public function mount()
    {
        $this->coupon = Coupon::where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();
               // ← يفتح تلقائياً لو فيه كوبون صالح
        if ($this->coupon) {
            $this->show = true;
        }
    }
    
    public function open()
    {
        $this->show = true;
        $this->copied = false;
    }
    
    public function close()
    {
        $this->show = false;
    }
    
    public function copy()
    {
        $this->copied = true;
        $this->dispatch('copy-code', code: $this->coupon->code);
    }
    
    public function apply()
    {
        $this->dispatch('apply-coupon', code: $this->coupon->code);
        $this->close();
    }
    
    public function render()
    {
        return view('livewire.coupon.coupon-popup');
    }
}