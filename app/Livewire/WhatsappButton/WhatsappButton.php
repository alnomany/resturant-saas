<?php

namespace App\Livewire\WhatsappButton;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Restaurant;

class WhatsappButton extends Component
{

    public $restaurantId; // ← تمرره من الصفحة
    
    public function mount($restaurantId = null)
    {
        $this->restaurantId = $restaurantId;
    }
    public function openWhatsApp()
    {
          // ← جلب المطعم المحدد
        if ($this->restaurantId) {
            $restaurant = Restaurant::find($this->restaurantId);
        } else {
            $restaurant = Restaurant::first(); // fallback
        }
        $phoneNumber = $restaurant?->phone_number ?? '966509969218';
        $wifeNumber =   "966".$phoneNumber;
        $yourEmail = config('services.whatsapp.notification_email', 'aalnomany50@gmail.com');
        $message = config('services.whatsapp.default_message', 'مرحباً، شفت إعلانكم في موقع تشيز كيك اليرموك وأبغى أطلب');

        // 1. Send Email Notification
        try {
            Mail::raw(
                "🍰 تنبيه: شخص تواصل من الموقع!\n\n" .
                "⏰ الوقت: " . now()->format('Y-m-d H:i:s') . "\n" .
                "🌐 IP: " . request()->ip() . "\n\n" .
                "افتح واتساب زوجتك لتشوف الرسالة.",
                function ($mail) use ($yourEmail) {
                    $mail->to($yourEmail)
                         ->subject('🍰 طلب من الموقع - ' . now()->format('H:i'));
                }
            );
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }

        // 2. Open WhatsApp
        $encodedMessage = urlencode($message);
        $link = "https://wa.me/{$wifeNumber}?text={$encodedMessage}";

        // Livewire v3 dispatch syntax (if using Livewire v2, keep dispatchBrowserEvent)
        $this->dispatch('open-whatsapp', link: $link);
    }

    public function render()
    {
        return view('livewire.whatsapp-button.whatsapp-button');
    }
}