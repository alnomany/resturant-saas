<div x-data="{ 
    show: @entangle('show'),
    copied: @entangle('copied'),
    timeLeft: {{ $coupon ? max(0, $coupon->expires_at->diffInSeconds(now())) : 0 }},
    timer: null
}"
x-init="
    if (timeLeft > 0) {
        timer = setInterval(() => {
            if (timeLeft > 0) timeLeft--;
            else clearInterval(timer);
        }, 1000);
    }
">

    {{-- زر فتح الـ Pop-up --}}
    <button @click="$wire.open()" 
            class="fixed bottom-4 left-4 bg-red-500 text-white px-4 py-2 rounded-full shadow-lg animate-bounce z-40">
        🔥 عرض خاص
    </button>

    {{-- الـ Pop-up --}}
    <div x-show="show" 
         x-transition
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         style="display: none;">
        
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl relative">
            
            {{-- زر إغلاق --}}
            <button @click="$wire.close()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                ✕
            </button>

            {{-- العنوان --}}
            <div class="text-center mb-4">
                <span class="text-3xl">🔥</span>
                <h2 class="text-xl font-bold text-gray-800 mt-2">{{$coupon->ad_title}}</h2>
                    {{-- بسيط: لو percentage ولا fixed --}}
    @if($coupon->type == 'percentage')
        <p class="text-gray-600 text-lg font-bold">خصم {{ $coupon->value }}%</p>
    @else
        <p class="text-gray-600 text-lg font-bold">خصم {{ $coupon->value }} ريال</p>
    @endif
</div>
            

            {{-- الكود + زر النسخ --}}
            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex items-center justify-between gap-2">
                    <code class="text-lg font-mono text-gray-800">{{ $coupon->code }}</code>
                    <button @click="$wire.copy()" 
                            class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 whitespace-nowrap">
                        <span x-show="!copied">📋 نسخ</span>
                        <span x-show="copied">✓ تم</span>
                    </button>
                </div>
            </div>

            {{-- العداد --}}
            <div class="text-center mb-4" x-show="timeLeft > 0">
                <p class="text-sm text-gray-500">ينتهي خلال:</p>
                <div class="text-2xl font-bold text-red-500 font-mono">
                    <span x-text="Math.floor(timeLeft / 3600).toString().padStart(2, '0')"></span>:
                    <span x-text="Math.floor((timeLeft % 3600) / 60).toString().padStart(2, '0')"></span>:
                    <span x-text="(timeLeft % 60).toString().padStart(2, '0')"></span>
                </div>
            </div>

            {{-- زر الاستخدام --}}
            <button @click="$wire.apply()" 
                    class="w-full bg-red-500 text-white py-3 rounded-lg font-bold hover:bg-red-600">
                استخدم الكود
            </button>
        </div>
    </div>

    {{-- JavaScript للنسخ --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('copy-code', ({ code }) => {
                navigator.clipboard.writeText(code);
            });
        });
    </script>
</div>