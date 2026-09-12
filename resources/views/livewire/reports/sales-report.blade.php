<div>
    <!-- Header Section -->
    <div class="p-4 bg-white dark:bg-gray-800">
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('menu.salesReport')</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                @lang('modules.report.salesReportMessage')
                @php
                    $formattedStartTime = \Carbon\Carbon::parse($startTime)->format('h:i A');
                    $formattedEndTime = \Carbon\Carbon::parse($endTime)->format('h:i A');
                @endphp
                <strong>
                    ({{ $startDate === $endDate
                        ? __('modules.report.salesDataFor') . " $startDate, " . __('modules.report.timePeriod') . " $formattedStartTime - $formattedEndTime"
                        : __('modules.report.salesDataFrom') . " $startDate " . __('app.to') . " $endDate, " . __('modules.report.timePeriodEachDay') . " $formattedStartTime - $formattedEndTime" }})
                </strong>
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Sales Card -->
            <div class="p-4 bg-skin-base/10 rounded-xl shadow-sm dark:bg-skin-base/10 border border-skin-base/30 dark:border-skin-base/40">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-skin-base dark:text-skin-base">@lang('modules.report.totalSales')</h3>
                    <div class="p-2 bg-skin-base/10 rounded-lg dark:bg-skin-base/10">
                        <svg class="w-4 h-4 text-skin-base dark:text-skin-base" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g stroke-width="0"/><g stroke-linecap="round" stroke-linejoin="round"/><g stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 13.75c0 .97.75 1.75 1.67 1.75h1.88c.8 0 1.45-.68 1.45-1.53 0-.91-.4-1.24-.99-1.45l-3.01-1.05c-.59-.21-.99-.53-.99-1.45 0-.84.65-1.53 1.45-1.53h1.88c.92 0 1.67.78 1.67 1.75M12 7.5v9"/><path d="M22 12c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2m10 4V2h-4m-1 5 5-5"/></g></svg>
                    </div>
                </div>
                <p class="text-3xl break-words font-bold text-skin-base dark:text-skin-base mb-4">
                    {{ currency_format($menuItems->sum('total_amount'), restaurant()->currency_id) }}
                </p>

                <div class="space-y-3">

                        <!-- الأكثر مبيعاً -->
                        <div class="rounded-lg bg-skin-base/10 p-3 dark:bg-skin-base/10 space-y-2">
                            <span class="text-sm font-medium text-skin-base block border-b border-skin-base/20 pb-1">
                                الأكثر مبيعاً
                            </span>
                            @forelse($topSellingItems as $item)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-sm font-medium text-emerald-700 dark:text-emerald-100">{{ $item->item_name }}</span>
                                    <span class="text-sm font-medium text-emerald-700 dark:text-emerald-100">{{ $item->total_quantity }}</span>
                                </div>
                            @empty
                                <span class="text-xs text-skin-base/50 block">لا توجد بيانات</span>
                            @endforelse
                        </div>
                        <!-- الأقل مبيعاً -->
                        <div class="rounded-lg bg-skin-base/10 p-3 dark:bg-skin-base/10 space-y-2">
                            <span class="text-sm font-medium text-skin-base block border-b border-skin-base/20 pb-1">
                                الأقل مبيعاً
                            </span>
                            @forelse($leastSellingItems as $item)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-sm font-medium text-emerald-700 dark:text-emerald-100">{{ $item->item_name }}</span>
                                    <span class="text-sm font-medium text-emerald-700 dark:text-emerald-100">{{ $item->total_quantity }}</span>
                                </div>
                            @empty
                                <span class="text-xs text-skin-base/50 block">لا توجد بيانات</span>
                            @endforelse
                        </div>
                </div>
            </div>

                       <!-- المصاريف expensensed -->
            <div class="p-4 bg-skin-base/10 rounded-xl shadow-sm dark:bg-skin-base/10 border border-skin-base/30 dark:border-skin-base/40">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-skin-base dark:text-skin-base">إجمالي المصاريف</h3>
                    <div class="p-2 bg-skin-base/10 rounded-lg dark:bg-skin-base/10">
                        <svg class="w-4 h-4 text-skin-base dark:text-skin-base" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g stroke-width="0"/><g stroke-linecap="round" stroke-linejoin="round"/><g stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 13.75c0 .97.75 1.75 1.67 1.75h1.88c.8 0 1.45-.68 1.45-1.53 0-.91-.4-1.24-.99-1.45l-3.01-1.05c-.59-.21-.99-.53-.99-1.45 0-.84.65-1.53 1.45-1.53h1.88c.92 0 1.67.78 1.67 1.75M12 7.5v9"/><path d="M22 12c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2m10 4V2h-4m-1 5 5-5"/></g></svg>
                    </div>
                </div>
                <p class="text-3xl break-words font-bold text-skin-base dark:text-skin-base mb-4">
                    {{ currency_format($totalExpenses ?? 0, restaurant()->currency_id)  }}
                </p>

           <div class="space-y-3">

    <!-- أعلى تصنيف مصاريف -->
    <div class="rounded-lg bg-skin-base/10 p-3 dark:bg-skin-base/10 space-y-2">
        <span class="text-sm font-medium text-skin-base block border-b border-skin-base/20 pb-1">
            أعلى صنف مصاريف
        </span>
        @if(isset($topExpenseCategory) && $topExpenseCategory)
            <div class="flex items-center justify-between text-xs">
                <span class="text-sm font-medium text-emerald-700 dark:text-emerald-100">{{ $topExpenseCategory->name }}</span>
                <span class="text-sm font-medium text-emerald-700 dark:text-emerald-100">{{ currency_format($topExpenseCategory->total_amount, restaurant()->currency_id) }}</span>
            </div>
        @else
            <span class="text-xs text-skin-base/50 block">لا توجد بيانات</span>
        @endif
    </div>

    <!-- كرت صافي الربح / الخسارة -->
    @php
        $netProfit = ($menuItems->sum('total_amount') ?? 0) - ($totalExpenses ?? 0);
    @endphp

    <div class="rounded-lg bg-skin-base/10 p-3 dark:bg-skin-base/10 space-y-2">
        <span class="text-sm font-medium text-skin-base block border-b border-skin-base/20 pb-1">
            صافي الأرباح
        </span>
        <div class="flex items-center justify-between text-xs">
            <span class="text-sm font-medium {{ $netProfit < 0 ? 'text-rose-600 dark:text-rose-400 font-bold' : 'text-emerald-700 dark:text-emerald-100' }}">
                {{ currency_format($netProfit, restaurant()->currency_id) }}
            </span>
        </div>

        {{-- التنبيه عند وجود خسارة (قيمة بالسالب) --}}
        @if($netProfit < 0)
            <div class="mt-2 rounded-md bg-rose-500/10 p-2 border border-rose-500/20 text-xs text-rose-600 dark:text-rose-400 flex items-center gap-1.5 font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>تنبيه: المصاريف تتجاوز المبيعات، لابد من تحسين وتنشيط مبيعاتك!</span>
            </div>
        @endif
    </div>

</div>
            </div>

            <!-- Total Cash Card -->
            <div class="p-4 bg-emerald-50 rounded-xl shadow-sm dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">@lang('modules.report.traditionalPayments')</h3>
                        <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg dark:bg-emerald-900/20 dark:text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2m7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ currency_format($menuItems->sum('cash_amount') + $menuItems->sum('card_amount') + $menuItems->sum('upi_amount'), restaurant()->currency_id) }}
                    </p>
                    <div class="space-y-2">
                        @php
                            $traditionalPayments = [
                                'cash' => $menuItems->sum('cash_amount'),
                                'card' => $menuItems->sum('card_amount'),
                                'upi' => $menuItems->sum('upi_amount')
                            ];
                        @endphp

                        @foreach($traditionalPayments as $method => $amount)
                            <div class="flex items-center justify-between rounded-lg bg-emerald-100/50 p-3 dark:bg-emerald-900/20">
                                <span class="text-sm font-medium text-emerald-700 dark:text-emerald-100">
                                    @lang("modules.order.{$method}")
                                </span>
                                <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400">
                                    {{ currency_format($amount, restaurant()->currency_id) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
            </div>
                        <!-- Customers -->
     <!-- Customers -->
<div class="p-4 bg-emerald-50 rounded-xl shadow-sm dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">@lang('modules.report.NumberCustomer')</h3>
        <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg dark:bg-emerald-900/20 dark:text-emerald-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2m7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
    </div>
    <p class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
        {{ $totalGeneralCustomers ?? 0 }}
    </p>
    <div class="space-y-2">
        @php
            $traditionalPayments = [
                'returningCustomers' => $totalReturningCustomers ?? 0,
                'returningOrders' => $totalReturningOrders ?? 0,
                'returningRevenue' => $totalReturningAmount ?? 0
            ];

            // حساب العملاء غير المتكررين (الجدد)
            $totalGeneral = $totalGeneralCustomers ?? 0;
            $totalReturning = $totalReturningCustomers ?? 0;
            $nonReturningCustomers = max(0, $totalGeneral - $totalReturning);
        @endphp

        @foreach($traditionalPayments as $method => $amount)
            <div class="flex items-center justify-between rounded-lg bg-emerald-100/50 p-3 dark:bg-emerald-900/20">
                <span class="text-sm font-medium text-emerald-700 dark:text-emerald-100">
                    @lang("modules.report.{$method}")
                </span>
                <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400">
                    {{ $method === 'returningRevenue' ? currency_format($amount, restaurant()->currency_id) : $amount }}
                </span>
            </div>
        @endforeach

        {{-- تنبيه العملاء غير المتكررين --}}
        @if($nonReturningCustomers > 0)
            <div class="mt-3 rounded-lg bg-amber-500/10 p-3 border border-amber-500/20 text-xs text-amber-700 dark:text-amber-400 flex items-start gap-2 font-medium">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span>تنبيه: لديك <strong>{{ $nonReturningCustomers }}</strong> عميل من أصل <strong>{{ $totalGeneral }}</strong> غير متكررين!</span>
                    <p class="mt-1 text-[11px] opacity-90">حاول إطلاق حملة عروض أو خصومات مخصصة لإعادتهم للطلب مرة أخرى.</p>
                </div>
            </div>
        @endif
    </div>
</div>

            <!-- Intelligent Operational Efficiency Card -->
<div class="p-4 bg-indigo-50 rounded-xl shadow-sm dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-800">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">تحليل كفاءة التشغيل والمنيو</h3>
        <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg dark:bg-indigo-900/20 dark:text-indigo-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
    </div>

    @php
        // 1. حساب متوسط قيمة الفاتورة
        $totalOrdersCount = $totalGeneralOrders ?? 0;
        $totalSalesAmount = $menuItems->sum('total_amount') ?? 0;
        $averageOrderValue = $totalOrdersCount > 0 ? ($totalSalesAmount / $totalOrdersCount) : 0;

        // 2. افتراض الأصناف الخاملة (تكون مجهزة من الـ Component)
        $deadItemsCount = $deadMenuItemsCount ?? 0; 
    @endphp

    <div class="mb-4">
        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium block">متوسط قيمة السلة / الفاتورة</span>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">
            {{ currency_format($averageOrderValue, restaurant()->currency_id) }}
        </p>
    </div>

    <div class="space-y-2">
        <div class="flex items-center justify-between rounded-lg bg-indigo-100/50 p-3 dark:bg-indigo-900/20">
            <span class="text-sm font-medium text-indigo-700 dark:text-indigo-100">
                إجمالي الطلبات الكلية
            </span>
            <span class="text-sm font-bold text-indigo-700 dark:text-indigo-400">
                {{ $totalOrdersCount }}
            </span>
        </div>

        <div class="flex items-center justify-between rounded-lg bg-indigo-100/50 p-3 dark:bg-indigo-900/20">
            <span class="text-sm font-medium text-indigo-700 dark:text-indigo-100">
                أصناف لم تباع (أصناف خاملة)
            </span>
            <span class="text-sm font-bold {{ $deadItemsCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-indigo-700 dark:text-indigo-400' }}">
                {{ $deadItemsCount }} أصناف
            </span>
        </div>

        {{-- تنبيهات ذكية بناءً على المؤشرات --}}
        
        {{-- تنبيه 1: متوسط الفاتورة منخفض --}}
        @if($averageOrderValue > 0 && $averageOrderValue < 35) {{-- يمكن تغيير الـ 35 حسب متوسط مطعمك --}}
            <div class="mt-3 rounded-lg bg-amber-500/10 p-3 border border-amber-500/20 text-xs text-amber-700 dark:text-amber-400 flex items-start gap-2 font-medium">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <span><strong>قرار تسويقي:</strong> متوسط الفاتورة منخفض ({{ currency_format($averageOrderValue, restaurant()->currency_id) }}).</span>
                    <p class="mt-1 text-[11px] opacity-90">أنشئ وجبات مجمعة (Combos) أو اقتراحات "إضافة مشروب/حلى" لزيادة قيمة طلب الزبون.</p>
                </div>
            </div>
        @endif

        {{-- تنبيه 2: وجود أصناف خاملة --}}
        @if($deadItemsCount > 0)
            <div class="mt-2 rounded-lg bg-rose-500/10 p-3 border border-rose-500/20 text-xs text-rose-700 dark:text-rose-400 flex items-start gap-2 font-medium">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <div>
                    <span><strong>قرار تشغيلي:</strong> لديك {{ $deadItemsCount }} أصناف لم يطلبها أحد خلال هذه الفترة.</span>
                    <p class="mt-1 text-[11px] opacity-90">يُوصى بإخفائها من المنيو لتقليل هدر المواد الأولية وتسريع عملية اختيار العميل.</p>
                </div>
            </div>
        @endif
    </div>
</div>
               <!-- Orders -->
            <div class="p-4 bg-emerald-50 rounded-xl shadow-sm dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">@lang('modules.report.orders')</h3>
                        <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg dark:bg-emerald-900/20 dark:text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2m7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $totalGeneralOrders }}
                    </p>
                    <div class="space-y-2">
                        @php
                            $traditionalPayments = [
                                'mostOrderedDate' => $peakDay,
                                'peakDayOrders' => $peakDayOrders,
                            ];
                        @endphp

                        @foreach($traditionalPayments as $method => $amount)
                            <div class="flex items-center justify-between rounded-lg bg-emerald-100/50 p-3 dark:bg-emerald-900/20">
                                <span class="text-sm font-medium text-emerald-700 dark:text-emerald-100">
                                    @lang("modules.report.{$method}")
                                </span>
                                <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400">
                                    {{ $amount}}
                                </span>
                            </div>
                        @endforeach
                        <!-- عرض الأكثر مبيعاً -->

                    </div>
            </div>
            <!-- Online Payments Card -->
            <div class="p-4 bg-emerald-50 rounded-xl shadow-sm dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">@lang('modules.report.paymentGateways')</h3>
                        <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg dark:bg-emerald-900/20 dark:text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ currency_format($menuItems->sum('razorpay_amount') + $menuItems->sum('stripe_amount') + $menuItems->sum('flutterwave_amount'), restaurant()->currency_id) }}
                    </p>
                    <div class="space-y-2">
                        @php
                            $paymentMethods = [
                                'razorpay' => [
                                    'status' => $paymentGateway->razorpay_status,
                                    'amount' => $menuItems->sum('razorpay_amount')
                                ],
                                'stripe' => [
                                    'status' => $paymentGateway->stripe_status,
                                    'amount' => $menuItems->sum('stripe_amount')
                                ],
                                'flutterwave' => [
                                    'status' => $paymentGateway->flutterwave_status,
                                    'amount' => $menuItems->sum('flutterwave_amount')
                                ]
                            ];
                        @endphp

                        @foreach($paymentMethods as $method => $details)
                            @if($details['status'])
                            <div class="flex items-center justify-between rounded-lg bg-emerald-100/50 p-3 dark:bg-emerald-900/20">
                                    <span class="text-sm font-medium text-emerald-700 dark:text-emerald-100">
                                        @lang("modules.order.{$method}")
                                    </span>
                                    <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400">
                                        {{ currency_format($details['amount']) }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
            </div>

            <!-- Additional Amounts Card -->
            <div class="p-4 bg-rose-50 rounded-xl shadow-sm dark:bg-rose-900/10 border border-rose-100 dark:border-rose-800">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">@lang('modules.report.additionalAmounts')</h3>
                        <div class="p-2 bg-rose-100 rounded-lg dark:bg-rose-800/50">
                            <svg class="w-4 h-4 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0"/></svg>
                        </div>
                    </div>
                    <div class="space-y-2">
                        @php
                            $additionalAmounts = [
                                'totalCharges' => [
                                    'label' => 'modules.report.totalCharges',
                                    'amount' => $charges->sum(fn($charge) => $menuItems->sum(fn($item) => $item['charges'][$charge->charge_name] ?? 0))
                                ],
                                'totalTaxes' => [
                                    'label' => 'modules.report.totalTaxes',
                                    'amount' => $menuItems->sum(fn($item) => collect($item['taxes'])->sum())
                                ],
                                'discount' => [
                                    'label' => 'modules.order.discount',
                                    'amount' => $menuItems->sum('discount_amount')
                                ],
                                'tip' => [
                                    'label' => 'modules.order.tip',
                                    'amount' => $menuItems->sum('tip_amount')
                                ]
                            ];
                        @endphp

                        @foreach($additionalAmounts as $key => $data)
                            <div class="flex items-center justify-between rounded-lg bg-rose-100/50 p-3 dark:bg-rose-900/20">
                                <span class="text-sm font-medium text-rose-700 dark:text-rose-200">
                                    @lang($data['label'])
                                </span>
                                <span class="text-sm font-bold text-rose-800 dark:text-rose-200">
                                    {{ currency_format($data['amount']) }}
                                </span>
                            </div>
                        @endforeach
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-6">



</div>
        </div>

        <!-- Filter Section -->
        <div class="flex flex-wrap justify-between items-center gap-4 p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
            <div class="lg:flex items-center mb-4 sm:mb-0">
                <form class="sm:pr-3" action="#" method="GET">

                    <div class="lg:flex gap-2 items-center">
                        <x-select id="dateRangeType" class="block w-full sm:w-fit mb-2 lg:mb-0" wire:model="dateRangeType" wire:change="setDateRange">
                            <option value="today">@lang('app.today')</option>
                            <option value="currentWeek">@lang('app.currentWeek')</option>
                            <option value="lastWeek">@lang('app.lastWeek')</option>
                            <option value="last7Days">@lang('app.last7Days')</option>
                            <option value="currentMonth">@lang('app.currentMonth')</option>
                            <option value="lastMonth">@lang('app.lastMonth')</option>
                            <option value="currentYear">@lang('app.currentYear')</option>
                            <option value="lastYear">@lang('app.lastYear')</option>
                        </x-select>

                        <div id="date-range-picker" date-rangepicker class="flex items-center w-full">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20zM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2"/></svg>
                                </div>
                                <input id="datepicker-range-start" name="start" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model.change='startDate' placeholder="@lang('app.selectStartDate')">
                            </div>
                            <span class="mx-4 text-gray-500 dark:text-gray-100">@lang('app.to')</span>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20zM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2"/></svg>
                                </div>
                                <input id="datepicker-range-end" name="end" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model.live='endDate' placeholder="@lang('app.selectEndDate')">
                            </div>
                        </div>

                        <div class="lg:flex items-center justify-between gap-x-2 ms-2">
                            <div class="w-full max-w-[7rem]">
                                <label for="start-time" class="sr-only">@lang('modules.reservation.timeStart'):</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" width="24" height="24" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 7.5a7.5 7.5 0 1 1 15 0 7.5 7.5 0 0 1-15 0m7 0V3h1v4.293l2.854 2.853-.708.708-3-3A.5.5 0 0 1 7 7.5" fill="currentColor"/></svg>
                                    </div>
                                    <x-input id="start-time" type="time" wire:model.live.debounce.500ms="startTime" />
                                </div>
                            </div>
                            <span class="mx-2 text-gray-500 dark:text-gray-100">@lang('app.to')</span>
                            <div class="w-full max-w-[7rem]">
                                <label for="end-time" class="sr-only">@lang('modules.reservation.timeEnd'):</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" width="24" height="24" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 7.5a7.5 7.5 0 1 1 15 0 7.5 7.5 0 0 1-15 0m7 0V3h1v4.293l2.854 2.853-.708.708-3-3A.5.5 0 0 1 7 7.5" fill="currentColor"/></svg>
                                    </div>
                                    <x-input id="end-time" type="time" wire:model.live.debounce.500ms="endTime" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <a href="javascript:;" wire:click='exportReport'
                class="inline-flex items-center  w-1/2 px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7.414A2 2 0 0 0 15.414 6L12 2.586A2 2 0 0 0 10.586 2zm5 6a1 1 0 1 0-2 0v3.586l-1.293-1.293a1 1 0 1 0-1.414 1.414l3 3a1 1 0 0 0 1.414 0l3-3a1 1 0 0 0-1.414-1.414L11 11.586z" clip-rule="evenodd"/></svg>
                @lang('app.export')
            </a>
            <div class="inline-flex items-center w-1/2 sm:w-auto ms-2">
                <select wire:model.live="selectedWaiter" wire:change="filterWaiter" class="px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-4 focus:ring-primary-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                    <option value="">@lang('app.reportByWaiter')</option>
                    @foreach($waiters ?? [] as $waiter)
                        <option value="{{ $waiter->id }}">{{ $waiter->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 p-4">
        <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
                <th class="p-4 text-xs font-medium tracking-wider text-left text-gray-600 uppercase dark:text-gray-300">
                @lang('app.date')
                </th>
                <th class="p-4 text-xs font-medium tracking-wider text-center text-gray-600 uppercase dark:text-gray-300">
                @lang('modules.report.totalOrders')
                </th>

                <!-- Charges Column Group -->
                @if(count($charges) > 0)
                <th colspan="{{ count($charges) }}" class="p-4 text-xs font-medium tracking-wider text-center text-gray-600 uppercase dark:text-gray-300 bg-blue-50 dark:bg-blue-900/20">
                    @lang('modules.order.extraCharges')
                </th>
                @endif

                <!-- Taxes Column Group -->
                @if(count($taxes) > 0)
                <th colspan="{{ count($taxes) }}" class="p-4 text-xs font-medium tracking-wider text-center text-gray-600 uppercase dark:text-gray-300 bg-red-50 dark:bg-red-900/20">
                    @lang('modules.order.taxes')
                </th>
                @endif

                <!-- Payment Methods Column Group -->
                <th colspan="{{ 3 + collect(['stripe', 'razorpay', 'flutterwave'])->filter(fn($method) => isset($paymentGateway) && $paymentGateway->{"{$method}_status"})->count() }}" class="p-4 text-xs font-medium tracking-wider text-center text-gray-600 uppercase dark:text-gray-300 bg-green-50 dark:bg-green-900/20">
                    @lang('modules.report.paymentMethods')
                </th>

                <th class="p-4 text-xs font-medium tracking-wider text-right text-gray-600 uppercase dark:text-gray-300">
                @lang('modules.order.deliveryFee')
                </th>
                <th class="p-4 text-xs font-medium tracking-wider text-right text-gray-600 uppercase dark:text-gray-300">
                @lang('modules.order.discount')
                </th>
                <th   class="p-4 text-xs font-medium tracking-wider text-right text-gray-600 uppercase dark:text-gray-300">
                @lang('modules.order.tip')
                </th>
                <th class="p-4 text-xs font-bold tracking-wider text-right text-gray-600 uppercase dark:text-gray-300">
                @lang('modules.order.total')
                </th>
            </tr>
            <tr>
                <th></th>
                <th></th>

                <!-- Charges Subheaders -->
                @foreach ($charges as $charge)
                <th class="p-4 text-xs font-medium tracking-wider text-right text-gray-600 uppercase dark:text-gray-300 bg-blue-50 dark:bg-blue-900/20">
                    {{ $charge->charge_name }}
                </th>
                @endforeach

                <!-- Taxes Subheaders -->
                @foreach ($taxes as $tax)
                <th class="p-4 text-xs font-medium tracking-wider text-right text-gray-600 uppercase dark:text-gray-300 bg-red-50 dark:bg-red-900/20">
                    {{ $tax->tax_name }} ({{ $tax->tax_percent }}%)
                </th>
                @endforeach
                <!-- Payment Methods Subheaders -->
                <th class="p-4 text-xs font-medium tracking-wider text-right text-gray-600 uppercase dark:text-gray-300 bg-green-50 dark:bg-green-900/20">
                @lang('modules.order.cash')
                </th>
                <th class="p-4 text-xs font-medium tracking-wider text-right text-gray-600 uppercase dark:text-gray-300 bg-green-50 dark:bg-green-900/20">
                @lang('modules.order.upi')
                </th>
                <th class="p-4 text-xs font-medium tracking-wider text-right text-gray-600 uppercase dark:text-gray-300 bg-green-50 dark:bg-green-900/20">
                @lang('modules.order.card')
                </th>
                @if($paymentGateway->razorpay_status)
                <th class="p-4 text-xs font-medium tracking-wider text-right text-gray-600 uppercase dark:text-gray-300 bg-green-50 dark:bg-green-900/20">
                    @lang('modules.order.razorpay')
                </th>
                @endif
                @if($paymentGateway->stripe_status)
                <th class="p-4 text-xs font-medium tracking-wider text-right text-gray-600 uppercase dark:text-gray-300 bg-green-50 dark:bg-green-900/20">
                    @lang('modules.order.stripe')
                </th>
                @endif
                @if($paymentGateway->flutterwave_status)
                <th class="p-4 text-xs font-medium tracking-wider text-right text-gray-600 uppercase dark:text-gray-300 bg-green-50 dark:bg-green-900/20">
                    @lang('modules.order.flutterwave')
                </th>
                @endif
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
            @forelse ($menuItems as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="p-4 text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">
                {{ \Carbon\Carbon::parse($item['date'])->format('M d, Y') }}
                </td>
                <td class="p-4 text-sm text-center text-gray-900 dark:text-white">
                {{ $item['total_orders'] }}
                </td>

                @foreach ($charges as $charge)
                <td class="p-4 text-sm font-normal text-right text-gray-900 dark:text-gray-100 bg-blue-50/50 dark:bg-blue-900/10">
                {{ currency_format($item['charges'][$charge->charge_name] ?? 0) }}
                </td>
                @endforeach

                @foreach ($taxes as $tax)
                <td class="p-4 text-sm font-normal text-right text-gray-900 dark:text-gray-100 bg-red-50/50 dark:bg-red-900/10">
                {{ currency_format($item['taxes'][$tax->tax_name] ?? 0) }}
                </td>
                @endforeach

                <td class="p-4 text-sm text-right text-gray-900 dark:text-white bg-green-50/50 dark:bg-green-900/10">
                {{ currency_format($item['cash_amount']) }}
                </td>
                <td class="p-4 text-sm text-right text-gray-900 dark:text-white bg-green-50/50 dark:bg-green-900/10">
                {{ currency_format($item['upi_amount']) }}
                </td>
                <td class="p-4 text-sm text-right text-gray-900 dark:text-white bg-green-50/50 dark:bg-green-900/10">
                {{ currency_format($item['card_amount']) }}
                </td>
                @if($paymentGateway->razorpay_status)
                <td class="p-4 text-sm text-right text-gray-900 dark:text-white bg-green-50/50 dark:bg-green-900/10">
                    {{ currency_format($item['razorpay_amount']) }}
                </td>
                @endif
                @if($paymentGateway->stripe_status)
                <td class="p-4 text-sm text-right text-gray-900 dark:text-white bg-green-50/50 dark:bg-green-900/10">
                    {{ currency_format($item['stripe_amount']) }}
                </td>
                @endif
                @if($paymentGateway->flutterwave_status)
                <td class="p-4 text-sm text-right text-gray-900 dark:text-white bg-green-50/50 dark:bg-green-900/10">
                    {{ currency_format($item['flutterwave_amount']) }}
                </td>
                @endif
                <td class="p-4 text-sm text-right text-gray-900 dark:text-white ">
                    {{ currency_format($item['delivery_fee']) }}
                </td>
                <td class="p-4 text-sm text-right text-gray-900 dark:text-white">
                    {{ currency_format($item['discount_amount']) }}
                </td>
                <td class="p-4 text-sm text-right text-gray-900 dark:text-white">
                {{ currency_format($item['tip_amount']) }}
                </td>
                <td class="p-4 text-sm font-bold text-right text-gray-900 dark:text-white">
                {{ currency_format($item['total_amount']) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="14" class="p-4 text-sm text-center text-gray-500 dark:text-gray-400">
                @lang('messages.noItemAdded')
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @script
    <script>
        const datepickerEl1 = document.getElementById('datepicker-range-start');

        datepickerEl1.addEventListener('changeDate', (event) => {
            $wire.dispatch('setStartDate', { start: datepickerEl1.value });
        });

        const datepickerEl2 = document.getElementById('datepicker-range-end');

        datepickerEl2.addEventListener('changeDate', (event) => {
            $wire.dispatch('setEndDate', { end: datepickerEl2.value });
        });
    </script>
    @endscript
</div>
