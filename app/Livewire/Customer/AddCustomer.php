<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Tax;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class AddCustomer extends Component
{
    use LivewireAlert;

    public $order;
    public $customerName;
    public $customerPhone;
    public $customerEmail;
    public $availableResults = [];
    public $customerAddress;
    public $showAddCustomerModal = false;
    public $fromPos;
    public $selectedCustomerId = null;
    public $redeemPoints = 0;
    public $loyaltyPoints;
    public $points_discount_x;
    public $currentCustomerId;
    //ahmed 
    public $taxes;
    public $total = 0;
    public $subTotal = 0;

    #[On('showAddCustomerModal')]
    public function showAddCustomer($id = null, $customerId = null, $fromPos = false)
    {

        if (!is_null($id)) {
            $this->order = Order::find($id);
        }



        if (!is_null($customerId)) {
            $customer = Customer::find($customerId);
            if ($customer) {
                $this->customerName = $customer->name;
                $this->customerPhone = $customer->phone;
                $this->customerEmail = $customer->email;
                $this->customerAddress = $customer->delivery_address;
                //ahmed
                // $this->updateLoyaltyValues();
            }
        }
        $this->fromPos = $fromPos ?? false;
        $this->showAddCustomerModal = true;
    }

    public function updatedCustomerName()
    {
        if (strlen($this->customerName) >= 2) {
            $this->availableResults = $this->fetchSearchResults();
        } else {
            $this->availableResults = [];
        }
    }

    public function fetchSearchResults()
    {

        $results = Customer::where('restaurant_id', restaurant()->id)
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->customerName . '%')
                    ->orWhere('phone', 'like', '%' . $this->customerName . '%')
                    ->orWhere('email', 'like', '%' . $this->customerName . '%');
            })->get();

        return $results;
    }

    public function selectCustomer($customerId)
    {
        $customer = Customer::find($customerId);

        if ($customer) {
            $this->selectedCustomerId = $customer->id;
            $this->customerName = $customer->name;
            $this->customerPhone = $customer->phone;
            $this->customerEmail = $customer->email;
            $this->customerAddress = $customer->delivery_address;
            $this->resetSearch();
        }
    }

    public function submitForm()
    {
        /*
        $customer = Customer::find($customerId);
        dd($customer);
        $this->redeemPoints = $customer->loyalty_points ?? 0;

        dd($this->redeemPoints);
        */
        $this->validate([
            'customerName' => 'required'
        ]);


        // Optimized: Find existing customer by priority (email > phone > id/name)
        $existingCustomer = null;
        $query = Customer::where('restaurant_id', restaurant()->id);

        if (!empty($this->customerEmail)) {
            $query->where('email', $this->customerEmail);
        } elseif (!empty($this->customerPhone)) {
            $query->where('phone', $this->customerPhone);
        } elseif (!empty($this->selectedCustomerId)) {
            $query->where('name', $this->customerName);
        } else {
            $query = null;
        }

        if ($query) {
            $existingCustomer = $query->first();
        }


        $customerData = [
            'name' => $this->customerName,
        ];

        foreach (
            [
                'phone' => $this->customerPhone,
                'email' => $this->customerEmail,
                'delivery_address' => $this->customerAddress
            ] as $field => $value
        ) {
            if (!empty($value)) {
                $customerData[$field] = $value;
            }
        }

        // Update or create the customer
        $customer = $existingCustomer
            ? tap($existingCustomer)->update($customerData)
            : Customer::create($customerData);

        if (!is_null($this->order)) {
            $this->order->customer_id = $customer->id;
            $this->order->delivery_address = $this->customerAddress;
            $this->order->save();
            //ahmed 
            // 🔹 هنا نحسب نقاط الولاء
            $this->loyaltyPoints = $customer->loyalty_points ?? 0;

            // 🔹 إذا عندك خصم من نقاط مستخدمة مسبقًا
            $this->points_discount_x = session('loyalty.points_discount_x', 0);

            // 🔹 تحسب الخصم حسب نقاط العميل
            $this->calculatePointsDiscount($this->loyaltyPoints);

            // 🔹 تحسب المجموع النهائي بعد الخصم
            $this->calculateTotals();


            // 🔹 تحديث الصفحة مباشرة (Livewire event)
            $this->currentCustomerId = $customer->id;
            $this->loyaltyPoints = $customer->loyalty_points ?? 0;
            $this->points_discount_x = session('loyalty.points_discount_x', 0);
            $this->calculatePointsDiscount($this->loyaltyPoints);
            $this->calculateTotals();
            $this->currentCustomerId = $customer->id;

            // ⭐ أرسل البيانات للـ OrderDetails Component
            $this->dispatch(
                'customerSelected',
                id: $this->currentCustomerId,
                loyaltyPoints: $this->loyaltyPoints,
                pointsDiscount: $this->points_discount_x,
                pointsFinalAmount: $this->total
            )->to('order.order-detail');

            if (!$this->fromPos) {
                $this->dispatch('showOrderDetail', id: $this->order->id);
            }
            $this->dispatch('refreshOrders');
            $this->dispatch('refreshPos');
        }

        $this->resetForm();
    }
    //ahmed for 
    public function calculatePointsDiscount($points)
    {
        // قيمة كل نقطة من config
        $pointValue = config('loyalty.point_value', 0.5);

        // خصم النقاط
        $this->points_discount_x = round($points * $pointValue, 2);
        // احفظها بالـ session لو تحتاجها
        session([
            'loyalty.points_discount_x' => $this->points_discount_x,
            'loyalty.points_finalamount' => $this->order->total - $this->points_discount_x,
        ]);
    }

    public function calculateTotals()
    {
        $this->subTotal = $this->order->items->sum('amount');

        // تطبيق الخصم
        $discount = (float) abs($this->order->discount_value ?? 0) + (float) abs($this->points_discount_x);
        $this->total = round($this->subTotal - $discount);

        // إضافة الضرائب
        $taxes = Tax::all();
        foreach ($taxes as $tax) {
            $this->total += ($tax->tax_percent / 100) * $this->total;
        }
    }

    public function resetSearch()
    {
        $this->availableResults = [];
    }

    public function resetForm()
    {
        $this->customerName = '';
        $this->customerPhone = '';
        $this->customerEmail = '';
        $this->customerAddress = '';
        $this->showAddCustomerModal = false;
    }

    public function render()
    {
        return view('livewire.customer.add-customer');
    }
}
