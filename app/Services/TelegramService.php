<?php
namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
 
    public static function sendOrderNotification(Order $order)
{
    // جلب الفرع/المطعم المرتبط بالطلب
    $branch = $order->branch ?? $order->restaurant;

    // التأكد من وجود chat_id معرّف للفرع أو المطعم
    if (!$branch || !$branch->telegram_chat_id) {
        Log::info("Telegram Order Notification Skipped: No chat_id for Branch/Restaurant ID={$order->branch_id}");
        return;
    }

    $token = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN', '8634478830:AAG3HntgZTkzEQNwwWEvEI82e70c_RwFLHk');

    // تجهيز تفاصيل المنتجات
    $itemsText = "";
    if ($order->items && count($order->items) > 0) {
        foreach ($order->items as $item) {
            $itemsText .= "• {$item->quantity}x {$item->name} - {$item->price} ر.س\n";
        }
    } else {
        $itemsText = "• لا توجد تفاصيل للمنتجات\n";
    }

    // اسم العميل وجواله (من علاقة العميل أو قيم افتراضية)
    $customerName = $order->customer->name ?? $order->customer_name ?? 'زائر';
    $customerPhone = $order->customer->phone ?? $order->customer_phone ?? 'غير محدد';

    // تنسيق نص الرسالة
    $message = "🛍️ *طلب جديد رقم #{$order->order_number}*\n";
    $message .= "------------------------\n";
    $message .= "👤 *العميل:* {$customerName}\n";
    $message .= "📞 *الجوال:* {$customerPhone}\n";
    $message .= "📍 *نوع الطلب:* " . strtoupper($order->order_type) . "\n";
    $message .= "💰 *الإجمالي:* {$order->total} ر.س\n";
    $message .= "------------------------\n";
    $message .= "*المنتجات:*\n{$itemsText}\n";
    $message .= "⏰ *الوقت:* " . now()->format('Y-m-d H:i') . "\n";

    // إرسال الرسالة عبر تليجرام
    try {
        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $branch->telegram_chat_id,
            'text' => $message,
            'parse_mode' => 'Markdown',
        ]);
        
        Log::info("Telegram Notification Sent for Order #{$order->order_number}");
    } catch (\Exception $e) {
        Log::error("Failed to send Telegram Notification: " . $e->getMessage());
    }
}
}
?>