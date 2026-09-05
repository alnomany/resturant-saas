<?php
namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public static function sendOrderNotification(Order $order)
    {
        // جلب المطعم المرتبط بالطلب
        $restaurant = $order->restaurant;

        // التأكد من وجود chat_id معرّف للمطعم
        if (!$restaurant || !$restaurant->telegram_chat_id) {
            Log::info("Telegram Order Notification Skipped: No chat_id for Restaurant ID={$order->restaurant_id}");
            return;
        }

        $token = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN', '8634478830:AAG3HntgZTkzEQNwwWEvEI82e70c_RwFLHk');

        // تجهيز تفاصيل المنتجات
        $itemsText = "";
        foreach ($order->items as $item) {
            $itemsText .= "• {$item->quantity}x {$item->name} - {$item->price} ر.س\n";
        }

        // تنسيق نص الرسالة
        $message = "🛍️ *طلب جديد رقم #{$order->id}*\n";
        $message .= "------------------------\n";
        $message .= "👤 *العميل:* {$order->customer_name}\n";
        $message .= "📞 *الجوال:* {$order->customer_phone}\n";
        $message .= "💰 *الإجمالي:* {$order->total_amount} ر.س\n";
        $message .= "------------------------\n";
        $message .= "*المنتجات:*\n{$itemsText}\n";
        $message .= "⏰ *الوقت:* " . now()->format('Y-m-d H:i') . "\n";

        // إرسال الرسالة عبر تليجرام
        try {
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $restaurant->telegram_chat_id,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
            
            Log::info("Telegram Notification Sent for Order #{$order->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send Telegram Notification: " . $e->getMessage());
        }
    }
}
?>