<div dir="rtl" class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">السلايدر</h1>
            <p class="text-sm text-gray-500 mt-1">أضف صور وفيديوهات تظهر في واجهة المطعم وتحكم في ترتيبها.</p>
        </div>
        <button wire:click="create" class="px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-xl shadow-sm">
            + شريحة جديدة
        </button>
    </div>

    @if (session('success'))
        <div class="p-3 bg-green-50 text-green-700 text-sm rounded-xl border border-green-100">{{ session('success') }}</div>
    @endif

    {{-- الإحصائيات --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ([
            ['إجمالي الشرائح', $stats['total'],  'bg-blue-50 text-blue-600'],
            ['شرائح فعّالة',   $stats['active'], 'bg-green-50 text-green-600'],
            ['صور',            $stats['images'], 'bg-orange-50 text-orange-600'],
            ['فيديوهات',       $stats['videos'], 'bg-purple-50 text-purple-600'],
        ] as [$label, $num, $color])
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="w-10 h-10 rounded-xl {{ $color }} flex items-center justify-center font-bold mb-3">{{ number_format($num) }}</div>
                <p class="text-sm text-gray-500">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    {{-- المعاينة --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <p class="text-sm font-semibold text-gray-700 mb-3">معاينة السلايدر (الشرائح الفعّالة فقط)</p>

        @if ($preview->isEmpty())
            <div class="h-48 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 text-sm">لا توجد شرائح فعّالة للمعاينة</div>
        @else
            <div wire:key="preview-{{ $preview->pluck('id')->join('-') }}"
                 x-data="{ i: 0, n: {{ $preview->count() }}, t: null,
                           start() { if (this.n > 1) this.t = setInterval(() => this.i = (this.i + 1) % this.n, 4000) },
                           go(k) { this.i = (k + this.n) % this.n } }"
                 x-init="start()"
                 class="relative h-56 sm:h-72 rounded-xl overflow-hidden bg-black">

                @foreach ($preview as $k => $p)
                    <div x-show="i === {{ $k }}" x-transition.opacity class="absolute inset-0">
                        @if ($p->type === 'video')
                            <video src="{{ $p->video_url }}" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
                        @else
                            <img src="{{ $p->image_url }}" class="w-full h-full object-cover" alt="">
                        @endif
                        @if ($p->title)
                            <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/70 to-transparent text-white font-semibold">{{ $p->title }}</div>
                        @endif
                    </div>
                @endforeach

                @if ($preview->count() > 1)
                    <button type="button" @click="go(i + 1)" class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 hover:bg-white text-gray-700">›</button>
                    <button type="button" @click="go(i - 1)" class="absolute left-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 hover:bg-white text-gray-700">‹</button>
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                        @foreach ($preview as $k => $p)
                            <span :class="i === {{ $k }} ? 'bg-white w-5' : 'bg-white/50 w-2'" class="h-2 rounded-full transition-all"></span>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- الجدول --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="بحث بعنوان الشريحة"
                   class="w-full sm:w-72 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-400">

            <div class="flex gap-1 bg-gray-100 p-1 rounded-xl text-sm overflow-x-auto">
                @foreach (['' => 'الكل', 'active' => 'فعّال', 'inactive' => 'موقوف', 'image' => 'صور', 'video' => 'فيديو'] as $key => $label)
                    <button type="button" wire:click="$set('filter', '{{ $key }}')"
                            class="px-3 py-1.5 rounded-lg whitespace-nowrap transition {{ $filter === (string) $key ? 'bg-white shadow-sm text-gray-800 font-semibold' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">المعاينة</th>
                        <th class="px-4 py-3 font-medium">العنوان</th>
                        <th class="px-4 py-3 font-medium">النوع</th>
                        <th class="px-4 py-3 font-medium">الرابط</th>
                        <th class="px-4 py-3 font-medium">الترتيب</th>
                        <th class="px-4 py-3 font-medium">الحالة</th>
                        <th class="px-4 py-3 font-medium">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($sliders as $s)
                        <tr class="hover:bg-gray-50/60" wire:key="slider-{{ $s->id }}">
                            <td class="px-4 py-3">
                                <div class="w-24 h-14 rounded-lg overflow-hidden bg-gray-100">
                                    @if ($s->type === 'video' && $s->video_url)
                                        <video src="{{ $s->video_url }}#t=0.5" muted preload="metadata" class="w-full h-full object-cover"></video>
                                    @elseif ($s->image_url)
                                        <img src="{{ $s->image_url }}" class="w-full h-full object-cover" alt="">
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $s->title ?: '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $s->type === 'video' ? 'bg-purple-50 text-purple-700' : 'bg-orange-50 text-orange-700' }}">
                                    {{ $s->type === 'video' ? 'فيديو' : 'صورة' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 max-w-[160px] truncate" dir="ltr">{{ $s->link ?: '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <button wire:click="move({{ $s->id }}, 'up')" class="w-7 h-7 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">↑</button>
                                    <button wire:click="move({{ $s->id }}, 'down')" class="w-7 h-7 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">↓</button>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="toggle({{ $s->id }})"
                                        class="relative w-11 h-6 rounded-full transition {{ $s->status ? 'bg-green-500' : 'bg-gray-300' }}">
                                    <span class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-all {{ $s->status ? 'right-0.5' : 'right-[22px]' }}"></span>
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2 text-xs">
                                    <button wire:click="edit({{ $s->id }})" class="px-2.5 py-1.5 rounded-lg border border-blue-100 text-blue-600 hover:bg-blue-50">تعديل</button>
                                    <button wire:click="delete({{ $s->id }})" wire:confirm="متأكد تبي تحذف هذه الشريحة؟"
                                            class="px-2.5 py-1.5 rounded-lg border border-red-100 text-red-600 hover:bg-red-50">حذف</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-16 text-center text-gray-400">لا توجد شرائح بعد، أضف أول شريحة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($sliders->hasPages())
            <div class="p-4 border-t border-gray-100">{{ $sliders->links() }}</div>
        @endif
    </div>

    {{-- نافذة الإضافة والتعديل --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40" wire:click.self="closeModal">
            <div class="bg-white w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-xl">

                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">{{ $sliderId ? 'تعديل الشريحة' : 'شريحة جديدة' }}</h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                </div>

                <form wire:submit="save" class="p-6 space-y-5">

                    <div class="grid grid-cols-2 gap-3">
                        @foreach (['image' => 'صور', 'video' => 'فيديو'] as $key => $label)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="type" value="{{ $key }}" class="sr-only peer">
                                <div class="p-3 text-center rounded-xl border border-gray-200 text-gray-600 peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-700 peer-checked:font-semibold">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">العنوان (اختياري)</label>
                        <input type="text" wire:model="title" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                        @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if ($type === 'image')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $sliderId ? 'استبدال الصورة (اختياري)' : 'الصور (تقدر تختار أكثر من صورة)' }}
                            </label>
                            <input type="file" wire:model="images" accept="image/*" @if(! $sliderId) multiple @endif
                                   class="w-full text-sm text-gray-500 file:ml-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700">
                            <div wire:loading wire:target="images" class="text-xs text-orange-600 mt-1">جاري الرفع...</div>
                            @error('images') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            @error('images.*') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror

                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach ($images as $img)
                                    <img src="{{ $img->temporaryUrl() }}" class="w-20 h-14 rounded-lg object-cover">
                                @endforeach
                                @if (empty($images) && $existing_image)
                                    <img src="{{ (new \App\Models\Slider(['image' => $existing_image]))->image_url }}" class="w-20 h-14 rounded-lg object-cover">
                                @endif
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $sliderId ? 'استبدال الفيديو (اختياري)' : 'ملف الفيديو (mp4 / webm، حتى 20 ميجا)' }}
                            </label>
                            <input type="file" wire:model="video" accept="video/mp4,video/webm,video/quicktime"
                                   class="w-full text-sm text-gray-500 file:ml-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-purple-50 file:text-purple-700">
                            <div wire:loading wire:target="video" class="text-xs text-orange-600 mt-1">جاري رفع الفيديو...</div>
                            @error('video') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror

                            @if ($existing_video && ! $video)
                                <video src="{{ (new \App\Models\Slider(['video' => $existing_video]))->video_url }}" controls muted class="mt-3 w-full max-h-48 rounded-xl bg-black"></video>
                            @elseif ($video)
                                <p class="text-xs text-green-600 mt-2">تم اختيار: {{ $video->getClientOriginalName() }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الرابط عند الضغط (اختياري)</label>
                            <input type="text" wire:model="link" dir="ltr" placeholder="https://..."
                                   class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الترتيب</label>
                            <input type="number" wire:model="sort_order" min="0"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-200 focus:outline-none">
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" wire:model="status" class="rounded border-gray-300 text-orange-600">
                        الشريحة فعّالة
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