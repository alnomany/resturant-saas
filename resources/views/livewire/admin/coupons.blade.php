{{-- resources/views/livewire/admin/coupons.blade.php --}}
<div dir="rtl" class="space-y-6">

    {{-- العنوان --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">الكوبونات</h1>
            <p class="text-sm text-gray-500 mt-1">أنشئ أكواد خصم لعملائك وتابع استخدامها.</p>
        </div>
        <button wire:click="create" class="px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-xl shadow-sm">
            + كوبون جديد
        </button>
    </div>

    @if (session('success'))
        <div class="p-3 bg-green-50 text-green-700 text-sm rounded-xl border border-green-100">{{ session('success') }}</div>
    @endif

    {{-- الإحصائيات --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $cards = [
                ['إجمالي الكوبونات', $stats['total'],   'bg-blue-50 text-blue-600'],
                ['كوبونات فعّالة',   $stats['active'],  'bg-green-50 text-green-600'],
                ['كوبونات منتهية',   $stats['expired'], 'bg-red-50 text-red-600'],
                ['مرات الاستخدام',   $stats['used'],    'bg-orange-50 text-orange-600'],
            ];
        @endphp
        @foreach ($cards as [$label, $num, $color])
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="w-10 h-10 rounded-xl {{ $color }} flex items-center justify-center font-bold mb-3">
                    {{ number_format($num) }}
                </div>
                <p class="text-sm text-gray-500">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    {{-- الجدول --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="بحث بكود الكوبون"
                   class="w-full sm:w-72 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400">

            <div class="flex gap-1 bg-gray-100 p-1 rounded-xl text-sm">
                @foreach (['' => 'الكل', 'active' => 'فعّال', 'inactive' => 'موقوف', 'expired' => 'منتهي'] as $key => $label)
                    <button type="button" wire:click="$set('filter', '{{ $key }}')"
                            class="px-3 py-1.5 rounded-lg transition {{ $filter === (string) $key ? 'bg-white shadow-sm text-gray-800 font-semibold' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">الكود</th>
                        <th class="px-4 py-3 font-medium">الخصم</th>
                        <th class="px-4 py-3 font-medium">الحد الأدنى</th>
                        <th class="px-4 py-3 font-medium">الاستخدام</th>
                        <th class="px-4 py-3 font-medium">الصلاحية</th>
                        <th class="px-4 py-3 font-medium">الحالة</th>
                        <th class="px-4 py-3 font-medium">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($coupons as $c)
                        @php
                            if ($c->isExpired())        $badge = ['bg-red-50 text-red-700', 'منتهي'];
                            elseif ($c->isExhausted())  $badge = ['bg-gray-100 text-gray-600', 'مستنفد'];
                            elseif (! $c->is_active)    $badge = ['bg-yellow-50 text-yellow-700', 'موقوف'];
                            else                        $badge = ['bg-green-50 text-green-700', 'فعّال'];
                            $pct = $c->usage_limit ? min(100, round($c->used / $c->usage_limit * 100)) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50/60" wire:key="coupon-{{ $c->id }}">
                            <td class="px-4 py-3">
                                <button type="button"
                                        onclick="navigator.clipboard.writeText('{{ $c->code }}')"
                                        title="انسخ الكود"
                                        class="px-3 py-1.5 bg-orange-50 text-orange-700 border border-dashed border-orange-300 rounded-lg font-mono font-bold tracking-wider" dir="ltr">
                                    {{ $c->code }}
                                </button>
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-800 whitespace-nowrap">
                                @if ($c->type === 'percentage')
                                    {{ rtrim(rtrim($c->value, '0'), '.') }}%
                                    @if ($c->maximum_discount)
                                        <span class="block text-xs font-normal text-gray-400">حتى {{ number_format($c->maximum_discount, 2) }} ر.س</span>
                                    @endif
                                @else
                                    {{ number_format($c->value, 2) }} <span class="text-xs font-normal text-gray-400">ر.س</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                {{ $c->minimum_amount > 0 ? number_format($c->minimum_amount, 2) . ' ر.س' : '—' }}
                            </td>
                            <td class="px-4 py-3 min-w-[120px]">
                                <p class="text-gray-700">{{ $c->used }} / {{ $c->usage_limit ?: '∞' }}</p>
                                @if ($c->usage_limit)
                                    <div class="mt-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-orange-500 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                                <p>من: {{ $c->starts_at?->format('Y/m/d') ?? '—' }}</p>
                                <p>إلى: {{ $c->expires_at?->format('Y/m/d') ?? 'مفتوح' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $badge[0] }}">{{ $badge[1] }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2 text-xs">
                                    <button wire:click="toggle({{ $c->id }})" class="px-2.5 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">
                                        {{ $c->is_active ? 'إيقاف' : 'تفعيل' }}
                                    </button>
                                    <button wire:click="edit({{ $c->id }})" class="px-2.5 py-1.5 rounded-lg border border-blue-100 text-blue-600 hover:bg-blue-50">تعديل</button>
                                    <button wire:click="delete({{ $c->id }})" wire:confirm="متأكد تبي تحذف هذا الكوبون؟"
                                            class="px-2.5 py-1.5 rounded-lg border border-red-100 text-red-600 hover:bg-red-50">حذف</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center text-gray-400">لا توجد كوبونات بعد، أنشئ أول كوبون</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($coupons->hasPages())
            <div class="p-4 border-t border-gray-100">{{ $coupons->links() }}</div>
        @endif
    </div>

    {{-- نافذة الإضافة والتعديل --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40" wire:click.self="closeModal">
            <div class="bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-xl">

                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">{{ $couponId ? 'تعديل الكوبون' : 'كوبون جديد' }}</h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                </div>

                <form wire:submit="save" class="p-6 space-y-5">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">كود الخصم</label>
                            <input type="text" wire:model="code" dir="ltr" placeholder="WELCOME10"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-xl uppercase font-mono focus:ring-2 focus:ring-orange-200 focus:border-orange-400 focus:outline-none">
                            @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">نوع الخصم</label>
                            <select wire:model.live="type" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                                <option value="percentage">نسبة مئوية %</option>
                                <option value="fixed">مبلغ ثابت (ر.س)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">قيمة الخصم</label>
                            <input type="number" step="0.01" wire:model="value"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                            @error('value') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الحد الأدنى للطلب</label>
                            <input type="number" step="0.01" wire:model="minimum_amount"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                            @error('minimum_amount') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        @if ($type === 'percentage')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">أقصى خصم (اختياري)</label>
                                <input type="number" step="0.01" wire:model="maximum_discount"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">عدد مرات الاستخدام (فارغ = بلا حد)</label>
                            <input type="number" wire:model="usage_limit"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                            @error('usage_limit') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">يبدأ في</label>
                            <input type="datetime-local" wire:model="starts_at"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ينتهي في</label>
                            <input type="datetime-local" wire:model="expires_at"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                            @error('expires_at') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- الإعلان --}}
                    <div class="pt-4 border-t border-gray-100 space-y-4">
                        <p class="text-sm font-semibold text-gray-700">بيانات الإعلان (اختياري)</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <input type="text" wire:model="ad_title" placeholder="عنوان الإعلان"
                                   class="px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                            <input type="text" wire:model="button_text" placeholder="نص الزر مثل: اطلب الآن"
                                   class="px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                        </div>

                        <textarea wire:model="ad_description" rows="2" placeholder="وصف الإعلان"
                                  class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none"></textarea>

                        <div class="flex items-center gap-4">
                            @if ($ad_image)
                                <img src="{{ $ad_image->temporaryUrl() }}" class="w-16 h-16 rounded-xl object-cover">
                            @elseif ($existing_image)
                                <img src="{{ asset('storage/' . $existing_image) }}" class="w-16 h-16 rounded-xl object-cover">
                            @endif
                            <input type="file" wire:model="ad_image" accept="image/*" class="text-sm text-gray-500">
                        </div>
                        @error('ad_image') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-orange-600">
                        الكوبون فعّال
                    </label>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50">إلغاء</button>
                        <button type="submit" class="px-5 py-2 text-sm font-semibold rounded-xl bg-orange-600 hover:bg-orange-700 text-white">
                            <span wire:loading.remove wire:target="save">حفظ</span>
                            <span wire:loading wire:target="save">جاري الحفظ...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>