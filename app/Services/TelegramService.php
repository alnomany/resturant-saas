<?php
namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
 
    public static function sendOrderNotification(Order $order)
    {
        // 1. جلب الفرع المرتبط بالطلب
        $branch = $order->branch;

        // 2. جلب المطعم الرئيسي المرتبط بالفرع
        $restaurant = $branch ? $branch->restaurant : null;

        // 3. جلب telegram_chat_id من المطعم الرئيسي (أو من الفرع كخيار احتياطي)
        $chatId = $restaurant->telegram_chat_id ?? $branch->telegram_chat_id ?? null;

        // إذا لم يتم العثور على chat_id، يتم التسجيل في السجل والتوقف
        if (!$chatId) {
            $branchId = $order->branch_id ?? 'N/A';
            Log::info("Telegram Order Notification Skipped: No chat_id found for Branch ID={$branchId}");
            return;
        }

        $token = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN', '8634478830:AAG3HntgZTkzEQNwwWEvEI82e70c_RwFLHk');

        // تجهيز قائمة أصناف الطلب
        $itemsText = "";
        if ($order->items && count($order->items) > 0) {
            foreach ($order->items as $item) {
                $itemsText .= "• {$item->quantity}x {$item->name} - {$item->price} ر.س\n";
            }
        } else {
            $itemsText = "• لا توجد تفاصيل للمنتجات\n";
        }

        // جلب بيانت العميل واسم المطعم
        $customerName = $order->customer->name ?? $order->customer_name ?? 'زائر';
        $customerPhone = $order->customer->phone ?? $order->customer_phone ?? 'غير محدد';
        $restaurantName = $restaurant->name ?? $branch->name ?? 'المطعم';

        // تنسيق نص الرسالة
        $message = "🛍️ *طلب جديد رقم #{$order->order_number}*\n";
        $message .= "------------------------\n";
        $message .= "🏢 *المطعم:* {$restaurantName}\n";
        $message .= "👤 *العميل:* {$customerName}\n";
        $message .= "📞 *الجوال:* {$customerPhone}\n";
        $message .= "📍 *نوع الطلب:* " . strtoupper($order->order_type) . "\n";
        $message .= "💰 *الإجمالي:* {$order->total} ر.س\n";
        $message .= "------------------------\n";
        $message .= "*المنتجات:*\n{$itemsText}\n";
        $message .= "⏰ *الوقت:* " . now()->format('Y-m-d H:i') . "\n";

        // إرسال الرسالة إلى تليجرام
        try {
            $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);

            if ($response->successful()) {
                Log::info("Telegram Notification Sent for Order #{$order->order_number} to Chat ID: {$chatId}");
            } else {
                Log::error("Telegram API Response Error: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Failed to send Telegram Notification: " . $e->getMessage());
        }
    }
}
?>