<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. تسجيل كل ما يصلنا من تليجرام في السجل لتتبعه
        Log::info('Telegram Webhook Payload:', $request->all());

        $data = $request->all();

        // التأكد من وجود رسالة
        if (!isset($data['message'])) {
            return response()->json(['status' => 'ok']);
        }

        $message = $data['message'];
        $chatId = $message['chat']['id'] ?? null;
        $text = $message['text'] ?? '';

        // 2. استخدام التعبير النمطي (Regex) للتقاط ID المطعم بدقة
        if (preg_match('/\/start\s+restaurant_(\d+)/', $text, $matches)) {
            $restaurantId = $matches[1];

            Log::info("Telegram Link Attempt: Restaurant ID={$restaurantId}, Chat ID={$chatId}");

            // البحث عن المطعم
            $restaurant = Restaurant::find($restaurantId);

            if ($restaurant) {
                // حفظ رقم المحادثة
                $restaurant->update(['telegram_chat_id' => $chatId]);

                Log::info("Successfully saved telegram_chat_id for Restaurant ID={$restaurantId}");

                // إرسال رسالة تأكيد للمستخدم على تليجرام
                $token = config('services.telegram.bot_token') ?: env('TELEGRAM_BOT_TOKEN', '8634478830:AAG3HntgZTkzEQNwwWEvEI82e70c_RwFLHk');
                Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => "🎉 تم ربط مطعم **{$restaurant->name}** بنجاح!\nستصلك جميع الطلبات الجديدة هنا فوراً.",
                    'parse_mode' => 'Markdown',
                ]);
            } else {
                Log::error("Telegram Link Failed: Restaurant ID={$restaurantId} not found in DB.");
            }
        }

        return response()->json(['status' => 'ok']);
    }
}