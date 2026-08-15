<?php

namespace App\Livewire\Coupon;  // ← غيّرنا

use Livewire\Component;
use App\Models\Coupon;

class CouponPopup extends Component
{
    public $show = false;
    public $copied = false;
    public $coupon;
    public $originalCoupon;
    public $couponError = null;

    public function mount()
    {
        $this->coupon = Coupon::where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();
        $this->originalCoupon = $this->coupon;

               // ← يفتح تلقائياً لو فيه كوبون صالح
        if ($this->coupon) {
            $this->show = true;
        }
    }
    
    /**
     * الانتقال إلى الكوبون التالي
     */
    public function useAnotherCoupon()
    {
        $this->couponError = null;

        if (!$this->coupon) {
            $this->refreshCoupon();
            return;
        }

        $nextCoupon = Coupon::where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>', now())
            ->where('id', '>', $this->coupon->id)
            ->orderBy('id')
            ->first();

        /*
         * إذا لم يوجد كوبون بعد الحالي
         * نرجع لأول كوبون
         */
        if (!$nextCoupon) {
            $nextCoupon = Coupon::where('is_active', true)
                ->where('starts_at', '<=', now())
                ->where('expires_at', '>', now())
                ->orderBy('id')
                ->first();
        }

        if ($nextCoupon) {
            $this->coupon = $nextCoupon;
            $this->copied = false;

            // تحديث الكوبون المستخدم حاليًا في الجلسة إذا أردت
            // session()->put('coupon_id', $nextCoupon->id);

            return;
        }

        /*
         * احتياطياً إذا لم يوجد أي كوبون
         */
        $this->coupon = $this->originalCoupon;
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