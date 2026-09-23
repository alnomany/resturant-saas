<div class="px-4 space-y-6" @if(pusherSettings()->is_enabled_pusher_broadcast) wire:poll.10s @endif>

    <h2 class="text-xl font-bold dark:text-white inline-flex gap-2 items-center text-green-600">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-patch-check text-green-600" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10.354 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
            <path d="m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911z"/>
        </svg>

        @lang('messages.orderPlacedSuccess')
    </h2>
    {{-- ============ كارت التهنئة بالنقاط المكتسبة ============ --}}
@if($earnedPoints > 0)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => new Audio('{{ asset('sound/points_earned.mp3') }}').play().catch(() => {}), 400)"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 -translate-y-3 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        class="relative overflow-hidden rounded-3xl border border-amber-200/70
               bg-gradient-to-br from-amber-50 via-orange-50 to-white
               dark:from-gray-800 dark:to-gray-800 dark:border-gray-700
               p-5 shadow-sm"
    >
        <!-- زخرفة إشعاعية -->
        <div class="absolute -top-8 -right-8 w-28 h-28 bg-amber-300/25 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-orange-300/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative flex items-center gap-4">
            <!-- أيقونة الفوز / الكأس -->
            <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500
                        flex items-center justify-center shadow-lg shadow-amber-300/50 animate-bounce">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M9 21h6M12 17v4M7 4h10v4a5 5 0 01-10 0V4zM7 4H4a2 2 0 002 2h1V4zm10 0h3a2 2 0 01-2 2h-1V4z"/>
                </svg>
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-1.5">
                    <p class="text-sm font-black text-gray-900 dark:text-white">
                        مبروك! تم إضافة نقاط لحسابك 🎉
                    </p>
                </div>
                <div class="mt-1 flex items-baseline gap-1.5">
                    <span class="text-2xl font-black text-amber-600 dark:text-amber-400">+{{ number_format($earnedPoints) }}</span>
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">نقطة</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    استخدمها لاحقاً للحصول على خصم في طلباتك القادمة
                </p>
            </div>

            <!-- زر إغلاق -->
            <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
@endif


    <div >
        <div class="flex items-end justify-between cursor-pointer mb-4">
            <div class="flex items-center min-w-0">
                <div class="space-y-2">
                    <p class="font-medium text-gray-900 truncate dark:text-white flex gap-2 ">
                        @lang('modules.order.orderNumber') #{{ $order->order_number }}

                        @if ($order->status == 'kot')
                            <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-400 border border-yellow-400 text-xs font-medium px-2 py-1 rounded uppercase tracking-wide whitespace-nowrap">
                                @lang('modules.order.infokot')
                            </span>
                        @endif
                    </p>
                    <div class="flex items-center flex-1 text-xs text-gray-500">
                        {{ $order->items->count() }} @lang('modules.menu.item') | {{
                        $order->date_time->timezone(timezone())->translatedFormat('M d, Y H:i A') }}
                    </div>

                    @php
                        $maxPreparationTime = $order->items->max(function($item) {
                            return $item->menuItem->preparation_time;
                        });
                    @endphp

                    @if ($maxPreparationTime)
                        <div class="text-xs font-normal text-gray-500 dark:text-gray-400 max-w-56 items-center inline-flex my-1">
                            @lang('modules.menu.preparationTime') :
                            {{ $maxPreparationTime }} @lang('modules.menu.minutes') (@lang('app.approx'))
                        </div>
                    @endif
                </div>
            </div>
        
            <div class="inline-flex flex-col text-right text-base font-semibold text-gray-900 dark:text-white">
                <div>{{ currency_format($order->total, $restaurant->currency_id) }}</div>
                <div class="text-xs text-gray-500 font-light">@lang('modules.order.includeTax')</div>
            </div>
        </div>

        <div
            class="w-full divide-y divide-gray-200 overflow-hidden rounded-lg border border-gray-200 dark:divide-gray-700 dark:border-gray-700">
            @foreach ($order->items as $key => $item)
            <div class="space-y-4 p-3">
                <div class="flex justify-between items-center gap-4">
                    <div class="flex gap-4 items-center">
                        <a class="shrink-0">
                            <img class="w-12 h-12 rounded-md object-cover shadow-sm" src="{{ $item->menuItem->item_photo_url }}"
                                alt="{{ $item->menuItem->item_name }}" />
                        </a>

                        <a class="min-w-0 flex flex-col font-medium text-gray-900  dark:text-white">
                            <div class="text-gray-900 dark:text-white inline-flex items-center">
                                {{ $item->menuItem->item_name }}
                            </div>
                            <div class="text-xs text-gray-600 dark:text-white inline-flex items-center">
                                {{ (isset($item->menuItemVariation) ? $item->menuItemVariation->variation : '')
                                }}
                            </div>
                            @if($item->modifierOptions->isNotEmpty())
                            <div class="text-xs text-gray-600 dark:text-white">
                                @foreach ($item->modifierOptions as $modifier)
                                <div class="flex items-center justify-between text-xs mb-1 py-0.5 px-1 border-l-2 border-blue-500 bg-gray-200 dark:bg-gray-800 rounded-md">
                                    <span class="text-gray-900 dark:text-white">{{ $modifier->name }}</span>
                                    <span class="text-gray-600 dark:text-gray-300">{{ currency_format($modifier->price, $restaurant->currency_id) }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            @if ($item->menuItem->preparation_time)
                                <div class="text-xs font-normal text-gray-500 dark:text-gray-400 max-w-56 items-center inline-flex my-1">
                                    @lang('modules.menu.preparationTime') :
                                    {{ $item->menuItem->preparation_time }} @lang('modules.menu.minutes')
                                </div>
                            @endif
                        </a>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <p class="text-sm font-normal text-gray-900 dark:text-white">x{{ $item->quantity }}</p>

                        <p class="text-lg font-medium leading-tight text-gray-900 dark:text-white">
                            {{ currency_format($item->price + $item->modifierOptions->sum('price'), $restaurant->currency_id) }}
                        </p>
                    </div>
                </div>

            </div>
            @endforeach

            <div class="space-y-4 bg-gray-50 p-3 dark:bg-gray-800">
                <div class="space-y-2">
                    <dl class="flex items-center justify-between gap-4">
                        <dt class="font-normal text-gray-500 dark:text-gray-400"> @lang('modules.order.subTotal')</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ currency_format($order->sub_total, $restaurant->currency_id) }}</dd>
                    </dl>
                    @if ($order->discount_amount)
                        <dl class="flex items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <dt class="font-normal">@lang('modules.order.discount')</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">-{{ currency_format($order->discount_amount, $restaurant->currency_id) }}</dd>
                        </dl>
                    @endif
                    @if ($order->points_discount > 0)
                        <dl class="flex items-center justify-between gap-4 text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                            <dt class="font-normal inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                خصم نقاط الولاء ({{ $order->points_used }} نقطة)
                            </dt>
                            <dd class="font-bold">-{{ currency_format($order->points_discount, $restaurant->currency_id) }}</dd>
                        </dl>
                    @endif

                    @if ($order->discount_amount)
                        <dl class="flex items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <dt class="font-normal">@lang('modules.order.discount')</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">-{{ currency_format($order->discount_amount, $restaurant->currency_id) }}</dd>
                        </dl>
                    @endif
                    @foreach ($order->charges as $item)
                    <div class="flex justify-between text-gray-500 text-sm dark:text-gray-400">
                        <div class="inline-flex items-center gap-x-1">
                            {{ $item->charge->charge_name }}
                            @if ($item->charge->charge_type == 'percent')
                                ({{ $item->charge->charge_value }}%)
                            @endif
                        </div>
                        <div>
                            {{ currency_format(($item->charge->getAmount($order->sub_total - ($order->discount_amount ?? 0)))) }}
                        </div>
                    </div>
                    @endforeach

                    @foreach ($order->taxes as $item)
                    <dl class="flex items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <dt class="font-normal">{{ $item->tax->tax_name }} ({{ $item->tax->tax_percent }}%)</dt>
                        <dd class="text-sm font-medium ">{{ currency_format(($item->tax->tax_percent / 100) * $order->sub_total, $restaurant->currency_id) }}</dd>
                    </dl>
                    @endforeach

                    @if($order->tip_amount > 0)
                        <dl class="flex items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <dt class="font-normal">@lang('modules.order.tip')</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">{{ currency_format($order->tip_amount, $restaurant->currency_id) }}</dd>
                        </dl>
                    @endif
                </div>

                <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                    <dt class="text-lg font-bold text-gray-900 dark:text-white">@lang('modules.order.total')</dt>
                    <dd class="text-lg font-bold text-gray-900 dark:text-white">{{ currency_format($order->total, $restaurant->currency_id) }}</dd>
                </dl>
            </div>

        </div>
    </div>

    <div class="flex">
        @if ($order->status == 'paid')

            @if ($order->table_id)
                @php
                    $newOrderLink = route('table_order', [$order->table->hash]);
                @endphp
            @else
                @php
                    $newOrderLink = module_enabled('Subdomain')?url('/'):route('shop_restaurant',['hash' => $restaurant->hash]);
                @endphp
            @endif
        @else
            @php
                $newOrderLink = module_enabled('Subdomain')?url('/'):route('shop_restaurant',['hash' => $restaurant->hash]).'?current_order='.$order->id;
            @endphp
        @endif



        <x-primary-link wire:navigate class="inline-flex items-center mb-2" href="{{ $newOrderLink }}">
            @lang('modules.order.newOrder')
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </x-primary-link>

    </div>


</div>


@push('scripts')

    @if(pusherSettings()->is_enabled_pusher_broadcast)
        <script>

            document.addEventListener('DOMContentLoaded', function () {

                const channel = PUSHER.subscribe('order-success');
                channel.bind('order-success.created', function(data) {
                    @this.call('refreshOrderSuccess');
                    new Audio("{{ asset('sound/new_order.wav')}}").play();
                    console.log('✅ Pusher received data for order success!. Refreshing...');
                });
                PUSHER.connection.bind('connected', () => {
                    console.log('✅ Pusher connected for Order Success!');
                    
                });
                channel.bind('pusher:subscription_succeeded', () => {
                    console.log('✅ Subscribed to order-success channel!');
                });
            });
        </script>
    @endif
@endpush
