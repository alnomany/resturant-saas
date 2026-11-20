
<div>
    {{-- resources/views/livewire/admin/abandoned-carts.blade.php --}}
    {{-- resources/views/livewire/admin/abandoned-carts.blade.php --}}
<div dir="rtl" class="space-y-6">

    {{-- العنوان --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">السلات المتروكة</h1>
        <p class="text-sm text-gray-500 mt-1">تابع العملاء اللي أضافوا منتجات ولم يكملوا الطلب، واسترجع مبيعاتك.</p>
    </div>

    {{-- بطاقات الإحصائيات --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">إجمالي السلات المتروكة</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-500">نسبة السلات المستردة</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['recovery_rate'] }}%</p>
                <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full" style="width: {{ min($stats['recovery_rate'], 100) }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">متوسط قيمة السلة</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ number_format($stats['avg_value'], 2) }}
                    <span class="text-sm font-medium text-gray-400">ر.س</span>
                </p>
            </div>
        </div>
    </div>

    {{-- الجدول --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- شريط الفلترة --}}
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="relative w-full sm:w-80">
                <svg class="w-4 h-4 text-gray-400 absolute top-1/2 -translate-y-1/2 right-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="text" wire:model.live.debounce.400ms="search"
                       placeholder="بحث بالاسم أو رقم الجوال"
                       class="w-full pr-9 pl-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400">
            </div>

            <div class="flex gap-1 bg-gray-100 p-1 rounded-xl text-sm">
                @foreach (['pending' => 'متروكة', 'recovered' => 'مسترجعة', 'completed' => 'مكتملة', '' => 'الكل'] as $key => $label)
                    <button type="button" wire:click="$set('status', '{{ $key }}')"
                            class="px-3 py-1.5 rounded-lg transition {{ $status === (string) $key ? 'bg-white shadow-sm text-gray-800 font-semibold' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">العميل</th>
                        <th class="px-4 py-3 font-medium">المنتجات</th>
                        <th class="px-4 py-3 font-medium">قيمة السلة</th>
                        <th class="px-4 py-3 font-medium">الحالة</th>
                        <th class="px-4 py-3 font-medium">آخر نشاط</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($carts as $cart)
                        @php
                            $items = collect($cart->cart_data);
                            $badge = match ($cart->status) {
                                'pending'   => ['bg-orange-50 text-orange-700', 'متروكة'],
                                'recovered' => ['bg-green-50 text-green-700', 'مسترجعة'],
                                default     => ['bg-gray-100 text-gray-600', 'مكتملة'],
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60" wire:key="cart-{{ $cart->id }}">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center font-bold shrink-0">
                                        {{ mb_substr($cart->name ?: 'ز', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $cart->name ?: 'زائر' }}</p>
                                        <p class="text-xs text-gray-400" dir="ltr">{{ $cart->phone ?: 'بدون رقم' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                    @foreach ($items->take(2) as $item)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs">
                                            {{ $item['name'] ?? 'منتج' }} ×{{ $item['quantity'] ?? 1 }}
                                        </span>
                                    @endforeach
                                    @if ($items->count() > 2)
                                        <span class="px-2 py-1 bg-orange-50 text-orange-700 rounded-lg text-xs">
                                            +{{ $items->count() - 2 }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3 font-semibold text-gray-800 whitespace-nowrap">
                                {{ number_format($cart->total_amount, 2) }}
                                <span class="text-xs font-normal text-gray-400">ر.س</span>
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $badge[0] }}">{{ $badge[1] }}</span>
                            </td>

                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                {{ $cart->last_active_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-16 text-center">
                                <p class="text-gray-400">لا توجد سلات في هذا القسم</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($carts->hasPages())
            <div class="p-4 border-t border-gray-100">{{ $carts->links() }}</div>
        @endif
    </div>
</div>
    
</div>
