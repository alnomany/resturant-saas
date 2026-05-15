<?php

namespace App\Livewire\Order;

use App\Models\Customer;
use App\Models\DeliveryExecutive;
use App\Models\KotCancelReason;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Models\OrderCharge;
use App\Models\OrderItem;
use App\Models\OrderTax;
use App\Models\Printer;
use App\Models\Table;
use App\Models\Tax;
use App\Services\LoyaltyService;
use App\Traits\PrinterSetting;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderDetail extends Component
{

    use LivewireAlert, PrinterSetting;

    public $order;
    public $taxes;
    public $total = 0;
    public $subTotal = 0;
    public $showOrderDetail = false;
    public $showAddCustomerModal = false;
    public $showTableModal = false;
    public $cancelOrderModal = false;
    public $deleteOrderModal = false;
    public $tableNo;
    public $tableId;
    public $orderStatus;
    public $discountAmount = 0;
    public $deliveryExecutives;
    public $deliveryExecutive;
    public $orderProgressStatus;
    public $fromPos = null;
    public $confirmDeleteModal = false;
    public $cancelReasons;
    public $cancelReason;
    public $cancelReasonText;

    //ahmed 
    public $redeemPoints; // عدد النقاط التي يريد العميل استخدامها
    public $discount = 0;     // قيمة الخصم الناتج
    public $finalAmount = 0;  // المبلغ النهائي بعد تطبيق الخصم
    public $pointValue;
    public $earnRate;
    public $points_finalamount = 0;
    public $points_discount = 0;
    public $points_discount_x = 0;
    public $Totalpoint1 = 0;
    public $loyaltyPoints = 0;
    public $pointsDiscount = 0;
    public $pointsFinalAmount = 0;
    public $currentCustomerId;

    public function mount($data = null)
    {
        session()->forget('loyalty.points_discount');
        session()->forget('loyalty.points_finalamount');
        session()->forget('loyalty.points_used');
        session()->forget('loyalty.points_discount_x');
        $this->total = 0;
        $this->subTotal = 0;
        $this->redeemPoints = $this->order->customer->loyalty_points ?? 0;
        $this->taxes = Tax::all();
        $this->deliveryExecutives = DeliveryExecutive::where('status', 'available')->get();
        if ($this->order) {
            $this->deliveryExecutive = $this->order->delivery_executive_id;
        }
        $this->cancelReasons = KotCancelReason::where('cancel_order', true)->get();
        $this->pointValue = config('loyalty.point_value');
        $this->earnRate   = config('loyalty.earn_rate');
    }
    //ahmed
    public function updateLoyaltyValues()
    {
        // احسب الخصم الحالي
        //$this->pointsDiscount = abs($this->points_discount_x ?? 0);

        // احسب المبلغ بعد الخصم
        //$this->pointsFinalAmount = (float) ($this->total - $this->points_discount_x);

        // لو لازم تحفظ في session
        //session([
        //  'loyalty.points_discount'     => $this->pointsDiscount,
        //'loyalty.points_finalamount'  => $this->pointsFinalAmount,
        //]);
    }

    public function printOrder($orderId)
    {


        $orderPlaces = \App\Models\MultipleOrder::with('printerSetting')->get();
        //dd($orderPlaces);
        foreach ($orderPlaces as $orderPlace) {
            $printerSetting = $orderPlace->printerSetting;
        }

        try {

            switch ($printerSetting?->printing_choice) {
                case 'directPrint':
                    $this->handleOrderPrint($orderId);
                    break;
                default:

                    $url = route('orders.print', $orderId);
                    $this->dispatch('print_location', $url);
                    break;
            }
        } catch (\Throwable $e) {
            $this->alert('error', __('messages.printerNotConnected') . ' : ' . $e->getMessage(), [
                'toast' => true,
                'position' => 'top-end',
                'showCancelButton' => false,
                'cancelButtonText' => __('app.close')
            ]);
        }
    }

    #[On('showOrderDetail')]
    public function showOrder($id, $fromPos = null)
    {
        $this->order = Order::with('items', 'items.menuItem', 'items.menuItemVariation', 'payments', 'cancelReason')->find($id);
        $this->orderStatus = $this->order->status;
        $this->fromPos = $fromPos;
        $this->orderProgressStatus = $this->order->order_status->value;
        $this->showOrderDetail = true;
    }
    //ahmed
    #[On('customerSelected')]
    public function updateCustomerData($id = null, $loyaltyPoints = 0, $pointsDiscount = 0, $pointsFinalAmount = 0)
    {
        $this->currentCustomerId = $id;
        $this->loyaltyPoints = $loyaltyPoints;
        $this->pointsDiscount = $pointsDiscount;
        $this->pointsFinalAmount = $pointsFinalAmount;
    }

    #[On('setTable')]
    public function setTable(Table $table)
    {
        $this->tableNo = $table->table_code;
        $this->tableId = $table->id;

        if ($this->order) {
            $currentOrder = Order::where('id', $this->order->id)->first();

            Table::where('id', $currentOrder->table_id)->update([
                'available_status' => 'available'
            ]);

            $currentOrder->update(['table_id' => $table->id]);

            if ($this->order->date_time->format('d-m-Y') == now()->format('d-m-Y')) {
                Table::where('id', $this->tableId)->update([
                    'available_status' => 'running'
                ]);
            }

            $this->order->fresh();
            $this->dispatch('showOrderDetail', id: $this->order->id);
        }

        $this->dispatch('posOrderSuccess');
        $this->dispatch('refreshOrders');
        $this->dispatch('refreshPos');

        $this->showTableModal = false;
    }

    public function saveOrderStatus()
    {
        if ($this->order) {
            Order::where('id', $this->order->id)->update(['status' => $this->orderStatus]);

            $this->dispatch('posOrderSuccess');
            $this->dispatch('refreshOrders');
            $this->dispatch('refreshPos');
        }
    }

    public function showAddCustomer($id)
    {
        $this->order = Order::find($id);
        $this->showAddCustomerModal = true;
    }

    public function deleteOrderItems($id)
    {
        OrderItem::destroy($id);

        if ($this->order) {
            $this->total = 0;
            $this->subTotal = 0;

            foreach ($this->order->items as $value) {
                $this->subTotal = ($this->subTotal + $value->amount);
                $this->total = ($this->total + $value->amount);
            }

            foreach ($this->taxes as $value) {
                $this->total = ($this->total + (($value->tax_percent / 100) * $this->subTotal));
            }

            Order::where('id', $this->order->id)->update([
                'sub_total' => $this->subTotal,
                'total' => $this->total
            ]);
        }

        $this->dispatch('refreshPos');
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

        $this->total = $this->subTotal - $discount;

        // إضافة الضرائب
        $taxes = Tax::all();
        foreach ($taxes as $tax) {
            $this->total += ($tax->tax_percent / 100) * $this->subTotal;
        }
    }
    public function updatedOrderProgressStatus($value)
    {
        if (empty($this->order) || is_null($value)) {
            return;
        }

        $this->order->update(['order_status' => $value]);
        $this->orderProgressStatus = $value;

        if ($value === 'confirmed') {
            $this->order->kot->each(function ($kot) {
                $kot->update(['status' => 'in_kitchen']);
            });
        }

        $this->dispatch('posOrderSuccess');
        $this->dispatch('refreshOrders');
        $this->dispatch('refreshPos');
    }

    public function saveOrder($action)
    {

        switch ($action) {
            case 'bill':
                $successMessage = __('messages.billedSuccess');
                $status = 'billed';
                $tableStatus = 'running';
                break;

            case 'kot':
                return $this->redirect(route('pos.show', $this->order->table_id), navigate: true);
        }

        $taxes = Tax::all();

        Order::where('id', $this->order->id)->update([
            'date_time' => now(),
            'status' => $status
        ]);

        if ($status == 'billed') {

            foreach ($this->order->kot as $kot) {
                foreach ($kot->items as $item) {
                    $price = (($item->menu_item_variation_id) ? $item->menuItemVariation->price : $item->menuItem->price);
                    OrderItem::create([
                        'order_id' => $this->order->id,
                        'menu_item_id' => $item->menu_item_id,
                        'menu_item_variation_id' => $item->menu_item_variation_id,
                        'quantity' => $item->quantity,
                        'price' => $price,
                        'amount' => ($price * $item->quantity),
                    ]);
                }
            }

            foreach ($taxes as $value) {
                OrderTax::create([
                    'order_id' => $this->order->id,
                    'tax_id' => $value->id
                ]);
            }

            $this->total = 0;
            $this->subTotal = 0;

            foreach ($this->order->load('items')->items as $value) {
                $this->subTotal = ($this->subTotal + $value->amount);
                $this->total = ($this->total + $value->amount);
            }

            foreach ($taxes as $value) {
                $this->total = ($this->total + (($value->tax_percent / 100) * $this->subTotal));
            }

            if ($this->order->discount_type === 'percent') {
                $this->discountAmount = round(($this->subTotal * $this->order->discount_value) / 100, 2);
            } elseif ($this->order->discount_type === 'fixed') {
                $this->discountAmount = min($this->order->discount_value, $this->subTotal);
            }

            $this->total -= $this->discountAmount;

            Order::where('id', $this->orderDetail->id)->update([
                'sub_total' => $this->subTotal,
                'total' => $this->total,
                'discount_amount' => $this->discountAmount,
            ]);
        }

        Table::where('id', $this->tableId)->update([
            'available_status' => $tableStatus
        ]);


        $this->alert('success', $successMessage, [
            'toast' => true,
            'position' => 'top-end',
            'showCancelButton' => false,
            'cancelButtonText' => __('app.close')
        ]);

        if ($status == 'billed') {
            $this->dispatch('showOrderDetail', id: $this->order->id);
            $this->dispatch('posOrderSuccess');
            $this->dispatch('refreshOrders');
            $this->dispatch('resetPos');
        }
    }

    public function showPayment($id)
    {
        $this->dispatch('showPaymentModal', [
            'id' => $id,
            'points_discount_x' => $this->points_discount_x, // قيمة الخصم من النقاط
        ]);
    }

    public function cancelOrderStatus($id)
    {
        $this->confirmDeleteModal = true;

        if ($id) {
            $order = Order::find($id);

            if ($order) {
                $order->update([
                    'status' => 'canceled',
                    'order_status' => 'cancelled',
                    'cancel_reason_id' => $this->cancelReason,
                    'cancel_reason_text' => $this->cancelReasonText,
                ]);

                if ($order->table_id) {
                    Table::where('id', $order->table_id)->update([
                        'available_status' => 'available',
                    ]);
                }

                $this->alert('success', __('messages.orderCanceled'), [
                    'toast' => true,
                    'position' => 'top-end',
                    'showCancelButton' => false,
                    'cancelButtonText' => __('app.close'),
                ]);

                return $this->redirect(route('pos.index'), navigate: true);
            }
        }
    }

    public function cancelOrder($id)
    {
        $order = Order::find($id);

        if ($order) {
            $order->update([
                'status' => 'canceled',
            ]);
            $order->kot()->delete();
            $order->payments()->delete();

            if ($order->table_id) {
                Table::where('id', $order->table_id)->update([
                    'available_status' => 'available',
                ]);
            }
            $this->cancelOrderModal = false;
            $this->dispatch('showOrderDetail', id: $this->order->id);
            $this->dispatch('posOrderSuccess');
            $this->dispatch('refreshOrders');

            $this->alert('success', __('messages.orderCanceled'), [
                'toast' => true,
                'position' => 'top-end',
                'showCancelButton' => false,
                'cancelButtonText' => __('app.close')
            ]);

            if ($this->fromPos) {
                return $this->redirect(route('pos.index'), navigate: true);
            } else {
                $this->dispatch('resetPos');
            }
        }
    }

    public function paymentReceived($orderId, $status)
    {
        $order = Order::with('payments')->find($orderId);

        if (!$order) {
            $this->alert('error', __('messages.orderNotFound'), [
                'toast' => true,
                'position' => 'top-end',
                'showCancelButton' => false,
                'cancelButtonText' => __('app.close')
            ]);
            return;
        }

        if ($status === 'received') {
            $amountPaid = $order->payments->sum('amount');
            $order->update([
                'status' => 'paid',
                'amount_paid' => $amountPaid
            ]);
        } elseif ($status === 'not_received') {
            $latestPayment = $order->payments->last();
            if ($latestPayment) {
                $latestPayment->delete();
            }
            $order->update(['status' => 'payment_due']);
        }

        $this->alert('success', __('messages.statusUpdated'), [
            'toast' => true,
            'position' => 'top-end',
            'showCancelButton' => false,
            'cancelButtonText' => __('app.close')
        ]);

        $this->dispatch('showOrderDetail', id: $this->order->id);
        $this->dispatch('refreshOrders');
        $this->dispatch('refreshPos');
    }

    public function deleteOrder($id)
    {
        $order = Order::find($id);

        if (!$order) {
            $this->alert('error', __('messages.orderNotFound'), [
                'toast' => true,
                'position' => 'top-end',
                'showCancelButton' => false,
                'cancelButtonText' => __('app.close')
            ]);
            return;
        }

        if ($order->table_id) {
            Table::where('id', $order->table_id)->update(['available_status' => 'available']);
        }

        $order->delete();

        $this->deleteOrderModal = false;
        $this->showOrderDetail = false;
        $order = null;
        $this->order = null;

        $this->alert('success', __('messages.orderDeleted'), [
            'toast' => true,
            'position' => 'top-end',
            'showCancelButton' => false,
            'cancelButtonText' => __('app.close')
        ]);

        if ($this->fromPos) {
            return $this->redirect(route('pos.index'), navigate: true);
        } else {
            $this->dispatch('refreshOrders');
            $this->dispatch('refreshPos');
            $this->dispatch('refreshKots');
        }
    }

    public function saveDeliveryExecutive()
    {
        $this->order->update(['delivery_executive_id' => $this->deliveryExecutive]);
        $this->order->fresh();
        $this->alert('success', __('messages.deliveryExecutiveAssigned'), [
            'toast' => true,
            'position' => 'top-end',
            'showCancelButton' => false,
            'cancelButtonText' => __('app.close')
        ]);
    }

    public function removeCharge($chargeId)
    {
        $charge = OrderCharge::find($chargeId);

        if ($charge) {
            $chargeAmount = $charge->charge->getAmount($this->order->sub_total - ($this->order->discount_amount ?? 0));
            $charge->delete();
            $this->order->refresh();
            $this->total = $this->order->total - $chargeAmount;
            $this->order->update(['total' => $this->total]);
        }
    }

    public function updatePaymentMethod($id, $paymentMethod)
    {
        if (!$id || !$paymentMethod || !$this->order) {
            return;
        }

        $payment = $this->order->payments()->whereId($id)->first();

        if (!$payment) {
            return;
        }

        $payment->payment_method = $paymentMethod;
        $payment->save();

        $hasPaymentDue = $this->order->payments->contains('payment_method', 'due');

        $newStatus = $hasPaymentDue ? 'payment_due' : 'paid';

        if ($this->order->status !== $newStatus) {
            $this->order->status = $newStatus;
            $this->order->save();
        }

        $this->alert('success', __('messages.statusUpdated'), [
            'toast' => true,
            'position' => 'top-end',
            'showCancelButton' => false,
            'cancelButtonText' => __('app.close')
        ]);

        $this->dispatch('showOrderDetail', id: $this->order->id);
        $this->dispatch('refreshOrders');
    }
    //ahmed applyPoints
    public function applyPointsToOrder()
    {

        $service = new LoyaltyService();
        $customer = $this->order->customer;
        $this->redeemPoints = $this->order->customer->loyalty_points ?? 0;
        // قيمة الخصم = عدد النقاط × قيمة النقطة
        $this->points_discount_x = $this->redeemPoints * $this->pointValue;
        $this->total = 0;
        $this->subTotal = 0;
        $taxes = Tax::all();


        foreach ($this->order->load('items')->items as $value) {
            $this->subTotal = ($this->subTotal + $value->amount);
            $this->total = ($this->total + $value->amount);
        }



        if ($this->order->discount_type === 'percent') {
            $this->discountAmount = round(($this->subTotal * $this->order->discount_value) / 100, 2);
        } elseif ($this->order->discount_type === 'fixed') {
            $this->discountAmount = min($this->order->discount_value, $this->subTotal);
        }
        $this->discountAmount = (float)abs($this->discountAmount) + (float)abs($this->points_discount_x);
        $this->total -= $this->discountAmount;
        foreach ($taxes as $value) {
            $this->total = ($this->total + (($value->tax_percent / 100) * $this->total));
        }

        if ($this->order->customer->id) {
            //ahmed
            $subTotal = floatval($this->order->sub_total ?? 0);

            $pointsEarned = round($subTotal / 10); // مثال: 1 نقطة لكل 10 ريال
            $pointsToUse = round($this->redeemPoints);
            LoyaltyTransaction::create([
                'customer_id' => $this->order->customer->id,
                'order_id' => $this->order->id,
                'points' => $pointsToUse,
                'type' => 'Redeem',
            ]);
            $customer = Customer::find($this->order->customer->id);
            $customer->loyalty_points -= $pointsToUse;
            $customer->save();
        }

        // $this->total =  $this->total - ((float)abs($this->discountAmount) - (float)abs($this->discountAmount));

        /*
        Order::where('id', $this->orderDetail->id)->update([
            'sub_total' => $this->subTotal,
            'total' => $this->total,
            'discount_amount' => $this->discountAmount,
        ]);
        */



        session([
            // 'loyalty.points_used' => $pointsToUse,
            'loyalty.points_discount' => abs($this->points_discount_x),
            'loyalty.points_finalamount' => (float)$this->total,
            'loyalty.Totalpoint1' => (float)$this->total,
            'loyalty.is_applied' => true,
        ]);
        //$orderUpdated = $service->applyPoints($customer, $this->order, $this->redeemPoints);

        // تحديث المتغيرات للواجهة
        // $this->discount = $orderUpdated->points_discount;
        // $this->finalAmount = $orderUpdated->final_amount;
    }

    public function render()
    {
        return view('livewire.order.order-detail');
    }
}
