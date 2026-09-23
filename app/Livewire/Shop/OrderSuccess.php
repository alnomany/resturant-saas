<?php

namespace App\Livewire\Shop;

use App\Models\Branch;
use App\Models\Order;
use Livewire\Component;


class OrderSuccess extends Component
{

    public $id;
    public $order;
    public $restaurant;
    public $shopBranch;
        public $earnedPoints = 0;   // النقاط المكتسبة من هذا الطلب


    public function mount()
    {
        $this->order = Order::with('taxes.tax', 'items.menuItem')->where('id', $this->id)->firstOrFail();

        if (is_null(customer()) && $this->restaurant->customer_login_required) {
            return $this->redirect(route('home'));
        }

        if (request()->branch && request()->branch != '') {
            $this->shopBranch = Branch::find(request()->branch);
        } else {
            $this->shopBranch = $this->restaurant->branches->first();
        }
            // جلب النقاط المكتسبة من هذا الطلب تحديداً
        $this->earnedPoints = (int) DB::table('loyalty_transactions')
            ->where('order_id', $this->order->id)
            ->where('type', 'earn')
            ->sum('points');
    }

    public function render()
    {
        return view('livewire.shop.order-success');
    }

    public function refreshOrderSuccess()
    {
        $this->dispatch('$refresh');
    }
}
