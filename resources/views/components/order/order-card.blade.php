<div @class([
    'group relative flex flex-col justify-between overflow-hidden rounded-xl border bg-white p-4 transition-all duration-200 hover:border-gray-300 hover:shadow-lg dark:bg-gray-800 dark:border-gray-700 dark:hover:border-gray-600 min-h-[220px]'
])>
    {{-- رابط البطاقة الرئيسي --}}
    <a @if ($order->status == 'kot' && !is_null($order->table_id))
           href="{{ route('pos.order', $order->table_id) }}" wire:navigate
       @elseif ($order->status == 'kot' && is_null($order->table_id))
           href="{{ route('pos.kot', $order->id).'?showOrderDetail=true' }}" wire:navigate
       @else
           wire:click="$dispatch('showOrderDetail', { id: {{ $order->id }} })"
       @endif
       wire:key='order-item-{{ $order->id . microtime() }}' 
       href="javascript:;"
       class="flex flex-col justify-between flex-1 gap-3">

        {{-- 1. الهيدر العلوي --}}
        <div class="flex items-start justify-between border-b border-gray-100 dark:border-gray-700/60 pb-2.5 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">
                    <span wire:loading.class.delay='opacity-50' class="text-xs font-bold">
                        @if ($order->order_type == 'pickup')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4z"/>
                            </svg>
                        @elseif($order->order_type == 'delivery')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 7h-3V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h1a3 3 0 0 0 6 0h4a3 3 0 0 0 6 0h1a1 1 0 0 0 1-1v-5a4 4 0 0 0-4-4zm-12 11a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm10 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm2-5h-2.58l-1.2-3H19v3z"/>
                            </svg>
                        @else
                            {{ $order->table->table_code ?? '--' }}
                        @endif
                    </span>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm font-bold text-gray-900 dark:text-white leading-tight">
                        #{{ $order->order_number }}
                    </span>
                    <span class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ $order->date_time->timezone(timezone())->translatedFormat('d M - h:i A') }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col items-end gap-1 shrink-0">
                <span @class([
                    'px-2 py-0.5 text-[11px] font-semibold rounded-full leading-none',
                    'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' => ($order->status == 'draft'),
                    'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' => ($order->status == 'kot'),
                    'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-300' => ($order->status == 'billed' || $order->status == 'out_for_delivery'),
                    'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' => ($order->status == 'paid' || $order->status == 'delivered'),
                    'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300' => ($order->status == 'canceled' || $order->status == 'payment_due'),
                    'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300' => ($order->status == 'pending_verification'),
                ])>
                    @lang('modules.order.' . $order->status)
                </span>

                <div class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-500 dark:text-gray-400">
                    <span @class([
                        'h-1.5 w-1.5 rounded-full shrink-0',
                        'bg-amber-400' => ($order->order_status->value == 'placed'),
                        'bg-indigo-500' => ($order->order_status->value == 'confirmed'),
                        'bg-purple-500' => ($order->order_status->value == 'preparing'),
                        'bg-sky-400' => ($order->order_status->value == 'ready_for_pickup'),
                        'bg-blue-500' => ($order->order_status->value == 'out_for_delivery'),
                        'bg-emerald-400' => ($order->order_status->value == 'served'),
                        'bg-emerald-500' => ($order->order_status->value == 'delivered'),
                        'bg-rose-500' => ($order->order_status->value == 'cancelled'),
                    ])></span>
                    <span>@lang('modules.order.info_' . $order->order_status->value)</span>
                </div>
            </div>
        </div>

        {{-- 2. منتصف الكارد (بيانات العميل والموقع) --}}
        <div class="space-y-1.5 text-xs text-gray-600 dark:text-gray-300 py-1">
            <div class="flex items-center justify-between gap-2">
                <span class="font-semibold text-gray-800 dark:text-gray-200 truncate">
                    👤 {{ $order->customer->name ?? 'عميل افتراضي' }}
                </span>
                <span class="shrink-0 text-gray-500 dark:text-gray-400 font-mono text-[11px]">
                    📞 {{ $order->customer->phone ?? '--' }}
                </span>
            </div>

            <div class="flex items-center justify-between gap-2 text-gray-500 dark:text-gray-400 text-[11px]">
                <span class="truncate">
                    📍 {{ $order->branch?->name ?? $branch?->name ?? 'الفرع الرئيسي' }}
                </span>
                
                @if(isset($distance) && !is_null($distance))
                    @php
                        $unit = $branch?->deliverySetting?->unit ?? 'km';
                        $formattedDistance = $unit === 'miles' ? $distance / 1.60934 : $distance;
                        $unitLabel = $unit === 'miles' ? 'ميل' : 'كم';
                    @endphp
                    <span class="shrink-0 bg-gray-100 dark:bg-gray-700/50 px-1.5 py-0.5 rounded text-[10px] font-medium text-gray-700 dark:text-gray-300">
                        {{ number_format($formattedDistance, 2) }} {{ $unitLabel }}
                    </span>
                @endif
            </div>

            @if($order->delivery_address)
                <div class="pt-1.5 flex items-center justify-between gap-2 border-t border-dashed border-gray-100 dark:border-gray-700/50">
                    <p class="truncate flex-1 text-[11px] text-gray-500 dark:text-gray-400" title="{{ $order->delivery_address }}">
                        🏠 {{ $order->delivery_address }}
                    </p>

                    @php
                        $lat = $order->customer_lat;
                        $lng = $order->customer_lng;
                        $mapUrl = ($lat && $lng) 
                            ? "https://www.google.com/maps/search/?api=1&query={$lat},{$lng}"
                            : "https://www.google.com/maps/search/?api=1&query=" . urlencode($order->delivery_address);
                    @endphp

                    <a href="{{ $mapUrl }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       onclick="event.stopPropagation();" 
                       class="inline-flex shrink-0 items-center gap-0.5 text-[11px] font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                        <svg class="w-3 h-3 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        الخريطة
                    </a>
                </div>
            @endif
        </div>

        {{-- 3. الفوتر (الإجمالي والأزرار) --}}
        <div class="shrink-0 flex items-end justify-between border-t border-gray-100 dark:border-gray-700/60 pt-2.5 gap-2">
            <div class="flex flex-col">
                <div class="flex items-center gap-1.5">
                    <span class="text-[10px] text-gray-400 dark:text-gray-500">الإجمالي</span>
                    <span class="text-[10px] font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/50 px-1.5 rounded">
                        @if ($order->status == 'kot')
                            {{ $order->kot->count() }} @lang('modules.order.kot')
                        @else
                            {{ $order->items->count() }} @lang('modules.menu.item')
                        @endif
                    </span>
                </div>
                <span class="text-sm font-extrabold text-gray-900 dark:text-white leading-tight mt-0.5">
                    {{ currency_format($order->total, restaurant()->currency_id) }}
                </span>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @if ($order->status == 'kot' && user_can('Create Order'))
                    <x-secondary-link href="{{ route('pos.kot', ['id' => $order->id]) }}" class="!py-1 !px-2.5 text-[11px] whitespace-nowrap shadow-sm">
                        @lang('modules.order.newKot')
                    </x-secondary-link>
                @endif
            </div>
        </div>
    </a>
</div>