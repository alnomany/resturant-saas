<div
    x-data="{
        show: @entangle('show'),
        copied: @entangle('copied'),
        showOtherCoupon: false,
        otherCoupon: '',
        timeLeft: {{ $coupon && $coupon->expires_at
            ? max(0, $coupon->expires_at->diffInSeconds(now()))
            : 0
        }},
        timer: null
    }"

    x-init="
        if (timeLeft > 0) {
            timer = setInterval(() => {
                if (timeLeft > 0) {
                    timeLeft--;
                } else {
                    clearInterval(timer);
                }
            }, 1000);
        }
    "
>

    @if($coupon)

        {{-- ========================================= --}}
        {{-- Floating Offer Button --}}
        {{-- ========================================= --}}

        <button
            type="button"
            @click="$wire.open()"
            class="
                fixed bottom-4 left-4 z-40
                inline-flex items-center gap-2
                bg-red-500 hover:bg-red-600
                text-white
                px-4 py-2.5
                rounded-full
                shadow-lg shadow-red-200
                font-semibold text-sm
                transition-all duration-200
                hover:scale-105
                active:scale-95
            "
        >
            <span class="text-base">🔥</span>
            <span>عرض خاص</span>
        </button>


        {{-- ========================================= --}}
        {{-- Coupon Modal --}}
        {{-- مهم: Teleport إلى body --}}
        {{-- ========================================= --}}

        <template x-teleport="body">

            <div
                x-show="show"
                x-transition.opacity.duration.200ms
                class="
                    fixed inset-0
                    z-[999999]
                    bg-black/60
                    backdrop-blur-[2px]
                "
                style="display: none;"
                @keydown.escape.window="$wire.close()"
            >

                {{-- Modal Center --}}
                <div
                    class="
                        min-h-screen
                        w-full
                        flex
                        items-center
                        justify-center
                        p-4
                    "
                >

                    {{-- Modal --}}
                    <div
                        x-show="show"

                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"

                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"

                        @click.outside="$wire.close()"

                        class="
                            relative
                            w-full
                            max-w-md
                            overflow-hidden
                            rounded-3xl
                            bg-white
                            shadow-2xl
                        "
                    >

                        {{-- ========================================= --}}
                        {{-- Top Accent --}}
                        {{-- ========================================= --}}

                        <div
                            class="
                                h-1.5
                                bg-gradient-to-r
                                from-red-500
                                via-orange-500
                                to-yellow-400
                            "
                        ></div>


                        {{-- ========================================= --}}
                        {{-- Close Button --}}
                        {{-- ========================================= --}}

                        <button
                            type="button"
                            @click="$wire.close()"
                            class="
                                absolute
                                top-4
                                right-4
                                z-20
                                flex
                                items-center
                                justify-center
                                w-9
                                h-9
                                rounded-full
                                bg-gray-100
                                text-gray-500
                                hover:bg-gray-200
                                hover:text-gray-700
                                transition
                            "
                            aria-label="إغلاق"
                        >
                            ✕
                        </button>


                        <div class="p-6 sm:p-7">

                            {{-- ========================================= --}}
                            {{-- Header --}}
                            {{-- ========================================= --}}

                            <div class="text-center">

                                {{-- Icon --}}
                                <div
                                    class="
                                        mx-auto
                                        mb-4
                                        flex
                                        items-center
                                        justify-center
                                        w-16
                                        h-16
                                        rounded-2xl
                                        bg-red-50
                                        text-3xl
                                    "
                                >
                                    🎁
                                </div>


                                {{-- Small Label --}}
                                <p
                                    class="
                                        text-sm
                                        font-semibold
                                        text-red-500
                                        mb-2
                                    "
                                >
                                    عرض خاص لفترة محدودة
                                </p>


                                {{-- Title --}}
                                <h2
                                    class="
                                        text-2xl
                                        sm:text-3xl
                                        font-extrabold
                                        text-gray-900
                                        leading-tight
                                    "
                                >
                                    {{ $coupon->ad_title }}
                                </h2>


                                {{-- ========================================= --}}
                                {{-- Discount --}}
                                {{-- ========================================= --}}

                                <div
                                    class="
                                        mt-4
                                        inline-flex
                                        items-center
                                        justify-center
                                    "
                                >

                                    @if($coupon->type === 'percentage')

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-red-50
                                                px-4
                                                py-2
                                                text-red-600
                                                font-bold
                                            "
                                        >

                                            <span class="text-xl">
                                                {{ rtrim(rtrim(number_format($coupon->value, 2), '0'), '.') }}%
                                            </span>

                                            <span class="text-sm">
                                                خصم
                                            </span>

                                        </span>

                                    @else

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-red-50
                                                px-4
                                                py-2
                                                text-red-600
                                                font-bold
                                            "
                                        >

                                            <span class="text-xl">
                                                {{ rtrim(rtrim(number_format($coupon->value, 2), '0'), '.') }}
                                            </span>

                                            <span class="text-sm">
                                                ريال خصم
                                            </span>

                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- ========================================= --}}
                            {{-- Coupon Code --}}
                            {{-- ========================================= --}}

                            <div class="mt-6">

                                <p
                                    class="
                                        mb-2
                                        text-sm
                                        font-medium
                                        text-gray-500
                                        text-center
                                    "
                                >
                                    استخدم رمز الخصم عند إتمام الطلب
                                </p>


                                <div
                                    class="
                                        flex
                                        items-center
                                        gap-2
                                        rounded-2xl
                                        border-2
                                        border-dashed
                                        border-gray-200
                                        bg-gray-50
                                        p-2
                                    "
                                >

                                    {{-- Code --}}
                                    <div class="flex-1 text-center min-w-0">

                                        <code
                                            class="
                                                block
                                                truncate
                                                text-lg
                                                sm:text-xl
                                                font-extrabold
                                                tracking-[0.15em]
                                                text-gray-900
                                                select-all
                                            "
                                        >
                                            {{ $coupon->code }}
                                        </code>

                                    </div>


                                    {{-- Copy --}}
                                    <button
                                        type="button"
                                        @click="$wire.copy()"
                                        class="
                                            shrink-0
                                            rounded-xl
                                            px-4
                                            py-2.5
                                            text-sm
                                            font-bold
                                            transition
                                            whitespace-nowrap
                                            bg-gray-900
                                            text-white
                                            hover:bg-gray-800
                                            active:scale-95
                                        "
                                    >

                                        <span x-show="!copied">
                                            📋 نسخ الكود
                                        </span>

                                        <span
                                            x-show="copied"
                                            x-transition
                                        >
                                            ✓ تم النسخ
                                        </span>

                                    </button>

                                </div>

                            </div>


                            {{-- ========================================= --}}
                            {{-- Countdown --}}
                            {{-- ========================================= --}}

                            <div
                                x-show="timeLeft > 0"
                                x-transition
                                class="
                                    mt-5
                                    rounded-2xl
                                    border
                                    border-red-100
                                    bg-red-50
                                    px-4
                                    py-3
                                    text-center
                                "
                            >

                                <p
                                    class="
                                        text-xs
                                        font-medium
                                        text-red-500
                                    "
                                >
                                    ينتهي العرض خلال
                                </p>


                                <div
                                    class="
                                        mt-1
                                        text-xl
                                        sm:text-2xl
                                        font-black
                                        tracking-widest
                                        text-red-600
                                        font-mono
                                    "
                                >

                                    <span
                                        x-text="
                                            Math.floor(timeLeft / 3600)
                                                .toString()
                                                .padStart(2, '0')
                                        "
                                    ></span>

                                    :

                                    <span
                                        x-text="
                                            Math.floor((timeLeft % 3600) / 60)
                                                .toString()
                                                .padStart(2, '0')
                                        "
                                    ></span>

                                    :

                                    <span
                                        x-text="
                                            (timeLeft % 60)
                                                .toString()
                                                .padStart(2, '0')
                                        "
                                    ></span>

                                </div>

                            </div>


                            {{-- ========================================= --}}
                            {{-- Primary CTA --}}
                            {{-- ========================================= --}}

                            <button
                                type="button"
                                @click="$wire.close()"
                                class="
                                    mt-6
                                    w-full
                                    rounded-2xl
                                    bg-red-500
                                    py-3.5
                                    text-white
                                    font-bold
                                    shadow-lg
                                    shadow-red-200
                                    hover:bg-red-600
                                    transition
                                    active:scale-[0.98]
                                "
                            >
                                ابدأ التسوق 🛍️
                            </button>


                            {{-- ========================================= --}}
                            {{-- Other Coupon --}}
                            {{-- ========================================= --}}

{{-- تغيير الكوبون --}}
<div class="mt-5 text-center">

    <button
        type="button"
        wire:click="useAnotherCoupon"
        wire:loading.attr="disabled"
        class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition"
    >
        <span wire:loading.remove>
            🔄 لديك عرض آخر؟
        </span>

        <span wire:loading>
            جاري البحث...
        </span>
    </button>

</div>


                                    {{-- Error --}}
                                    @if($couponError)
                                        <div
                                            class="
                                                mt-2
                                                rounded-xl
                                                bg-red-50
                                                px-3
                                                py-2
                                                text-sm
                                                font-medium
                                                text-red-600
                                            "
                                        >
                                            ⚠️ {{ $couponError }}
                                        </div>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </template>

    @endif


    {{-- ========================================= --}}
    {{-- Copy Code --}}
    {{-- ========================================= --}}

    <script>
        document.addEventListener('livewire:initialized', () => {

            Livewire.on('copy-code', ({ code }) => {

                if (navigator.clipboard) {
                    navigator.clipboard.writeText(code);
                }

            });

        });
    </script>

</div>