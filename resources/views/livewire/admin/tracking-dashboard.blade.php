<div class="trk" dir="rtl" wire:poll.120s="loadData">
    <style>
        .trk{--bd:#e5e7eb;--mut:#6b7280;--fg:#111827;--bl:#2563eb;--gr:#059669;--rd:#dc2626;--am:#d97706;color:var(--fg);max-width:1280px;margin:0 auto;padding:16px}
        .trk *{box-sizing:border-box}
        .trk h2{font-size:20px;margin:0 0 4px}
        .trk h3{font-size:15px;margin:0 0 10px}
        .trk .sub{color:var(--mut);font-size:12px}
        .trk .bar{display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin:12px 0 16px}
        .trk .pill{border:1px solid var(--bd);background:#fff;padding:6px 12px;border-radius:999px;cursor:pointer;font-size:13px}
        .trk .pill.on{background:var(--fg);color:#fff;border-color:var(--fg)}
        .trk select,.trk input[type=date]{border:1px solid var(--bd);border-radius:8px;padding:6px 8px;font-size:13px;background:#fff}
        .trk label.chk{font-size:13px;display:flex;gap:6px;align-items:center}
        .trk .grid{display:grid;gap:12px;margin-bottom:12px}
        .trk .kpis{grid-template-columns:repeat(auto-fit,minmax(150px,1fr))}
        .trk .two{grid-template-columns:repeat(auto-fit,minmax(340px,1fr))}
        .trk .card{background:#fff;border:1px solid var(--bd);border-radius:12px;padding:14px;min-width:0}
        .trk .k-l{font-size:12px;color:var(--mut)}
        .trk .k-v{font-size:26px;font-weight:700;margin-top:4px}
        .trk .d{font-size:12px;margin-top:2px}
        .trk .up{color:var(--gr)} .trk .dn{color:var(--rd)} .trk .nt{color:var(--mut)}
        .trk .alert{border-radius:10px;padding:10px 12px;font-size:14px;margin-bottom:8px}
        .trk .alert.warn{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
        .trk .alert.info{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af}
        .trk .fn-row{display:grid;grid-template-columns:190px 1fr 170px;gap:10px;align-items:center;padding:7px 0;border-bottom:1px dashed var(--bd)}
        .trk .fn-row:last-child{border-bottom:0}
        .trk .fn-bar{height:32px;background:#eff6ff;border-radius:6px;overflow:hidden}
        .trk .fn-bar i{display:flex;align-items:center;padding:0 10px;height:100%;background:var(--bl);color:#fff;font-style:normal;font-size:13px;font-weight:600;border-radius:6px;min-width:34px;transition:width .4s}
        .trk .chip{display:inline-block;font-size:11px;padding:2px 8px;border-radius:999px;background:#fef2f2;color:#991b1b}
        @media(max-width:680px){.trk .fn-row{grid-template-columns:1fr}}
        .trk .scroll{overflow-x:auto}
        .trk table{width:100%;border-collapse:collapse;font-size:13px}
        .trk th{color:var(--mut);font-weight:500;text-align:start;padding:6px 8px;border-bottom:1px solid var(--bd);white-space:nowrap}
        .trk td{padding:6px 8px;border-bottom:1px solid #f3f4f6;white-space:nowrap}
        .trk .ch{position:relative;height:300px}
    </style>

    @assets
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @endassets

    {{-- الرأس والفلاتر --}}
    <h2>لوحة تتبع الزيارات والحملات</h2>
    <div class="sub">
        الفترة: {{ $rangeLabel }}
        @if($compare && $prevLabel) · مقارنة مع: {{ $prevLabel }} @endif
        <span wire:loading> · جاري التحديث…</span>
    </div>

    <div class="bar">
        @foreach(['today'=>'اليوم','yesterday'=>'أمس','week'=>'هذا الأسبوع','7d'=>'آخر 7 أيام','month'=>'هذا الشهر','last_month'=>'الشهر الماضي','custom'=>'مخصص'] as $k => $l)
            <button type="button" class="pill {{ $period === $k ? 'on' : '' }}" wire:click="setPeriod('{{ $k }}')">{{ $l }}</button>
        @endforeach

        @if($period === 'custom')
            <input type="date" wire:model.live="from"> <span class="sub">إلى</span> <input type="date" wire:model.live="to">
        @endif

        <label class="chk"><input type="checkbox" wire:model.live="compare"> مقارنة بالفترة السابقة</label>

        <select wire:model.live="source">
            <option value="">كل المصادر</option>
            @foreach($options['sources'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
        </select>
        <select wire:model.live="device">
            <option value="">كل الأجهزة</option>
            @foreach($options['devices'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
        </select>
        <select wire:model.live="country">
            <option value="">كل الدول</option>
            @foreach($options['countries'] as $s) <option value="{{ $s }}">{{ $s }}</option> @endforeach
        </select>
    </div>

    {{-- المؤشرات --}}
    <div class="grid kpis">
        @foreach($kpis as $k)
            @php
                $d   = $k['delta'];
                $cls = ($d === null || $d == 0) ? 'nt' : ((($k['good'] === 'up') === ($d > 0)) ? 'up' : 'dn');
            @endphp
            <div class="card">
                <div class="k-l">{{ $k['label'] }}</div>
                <div class="k-v">{{ $k['type'] === 'rate' ? $k['value'] . '%' : number_format($k['value']) }}</div>
                @if($compare && $d !== null)
                    <div class="d {{ $cls }}">
                        {{ $d > 0 ? '▲' : ($d < 0 ? '▼' : '■') }}
                        {{ abs($d) }}{{ $k['type'] === 'rate' ? ' نقطة' : '%' }}
                        <span class="nt">(قبل: {{ $k['type'] === 'rate' ? $k['prev'] . '%' : number_format($k['prev']) }})</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- التنبيهات الذكية --}}
    @foreach($insights as $i)
        <div class="alert {{ $i['type'] }}">{{ $i['type'] === 'warn' ? '⚠️' : 'ℹ️' }} {{ $i['text'] }}</div>
    @endforeach

    {{-- الفانل --}}
    <div class="card" style="margin-bottom:12px">
        <h3>الفانل — أين يتسرّب الزوار؟</h3>
        @foreach($funnel as $row)
            <div class="fn-row">
                <div>
                    <strong>{{ $row['label'] }}</strong>
                    <div class="sub">{{ number_format($row['visitors']) }} زائر فريد</div>
                </div>
                <div class="fn-bar">
                    <i style="width:{{ max(4, $row['of_total']) }}%">{{ number_format($row['sessions']) }}</i>
                </div>
                <div>
                    <div class="sub">{{ $row['of_total'] }}% من الزيارات</div>
                    @if(!$loop->first)
                        <div class="sub">{{ $row['of_prev'] }}% من المرحلة السابقة</div>
                        @if($row['drop'] > 0) <span class="chip">تسرّب {{ number_format($row['drop']) }}</span> @endif
                    @endif
                    @if($compare && $row['prev_sessions'] !== null)
                        <div class="sub">الفترة السابقة: {{ number_format($row['prev_sessions']) }}</div>
                    @endif
                </div>
            </div>
        @endforeach
        <div class="sub" style="margin-top:8px">ملاحظة: خطوتا النافذة تظهران فقط لمن يحتاج إكمال التسجيل، فقد تكون أقل من غيرها بشكل طبيعي.</div>
    </div>

    {{-- الرسوم --}}
    <div class="card" style="margin-bottom:12px">
        <h3>الاتجاه {{ $period === 'today' || $period === 'yesterday' ? '(بالساعة)' : '(باليوم)' }}</h3>
        <div class="ch" wire:ignore><canvas id="chTrend"></canvas></div>
    </div>

    <div class="grid two">
        <div class="card">
            <h3>المصادر: زيارات / سلة / طلبات</h3>
            <div class="ch" wire:ignore><canvas id="chSources"></canvas></div>
        </div>
        <div class="card">
            <h3>الدول</h3>
            <div class="ch" wire:ignore><canvas id="chCountries"></canvas></div>
        </div>
    </div>

    <div class="grid two">
        <div class="card">
            <h3>الأجهزة</h3>
            <div class="ch" wire:ignore><canvas id="chDevices"></canvas></div>
        </div>

        {{-- السلات المتروكة --}}
        <div class="card">
            <h3>آخر السلات المتروكة</h3>
            <div class="scroll">
                <table>
                    <thead><tr><th>آخر نشاط</th><th>العميل</th><th>المصدر</th><th>وقف عند</th><th>قيمة السلة</th></tr></thead>
                    <tbody>
                    @forelse($abandoned as $r)
                        <tr>
                            <td>{{ $r['time'] }}</td>
                            <td>{{ $r['who'] }} @if($r['phone']) <span class="sub">{{ $r['phone'] }}</span> @endif</td>
                            <td>{{ $r['source'] }} <span class="sub">{{ $r['device'] }}</span></td>
                            <td>{{ $r['stage'] }}</td>
                            <td>{{ $r['value'] !== null ? number_format((float) $r['value'], 2) : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="nt">لا توجد سلات متروكة في هذي الفترة.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="sub" style="margin-top:6px">السلة المتروكة = أضاف للسلة، خمل 30 دقيقة، ولم يشترِ لاحقاً.</div>
        </div>
    </div>

    {{-- الجداول --}}
    <div class="grid two">
        @foreach($tables as $t)
            <div class="card">
                <h3>{{ $t['title'] }}</h3>
                <div class="scroll">
                    <table>
                        <thead>
                        <tr>
                            <th>{{ $t['col'] }}</th><th>زيارات</th><th>فريدون</th><th>سلة</th><th>طلبات</th><th>تحويل</th>
                            @if($t['revenue']) <th>الإيراد</th> @endif
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($t['rows'] as $r)
                            <tr>
                                <td>{{ $r['label'] }}</td>
                                <td>{{ number_format($r['sessions']) }}</td>
                                <td>{{ number_format($r['visitors']) }}</td>
                                <td>{{ number_format($r['carts']) }}</td>
                                <td>{{ number_format($r['orders']) }}</td>
                                <td class="{{ $r['sessions'] >= 30 && $r['orders'] === 0 ? 'dn' : '' }}">{{ $r['conv'] }}%</td>
                                @if($t['revenue']) <td>{{ number_format($r['revenue'], 2) }}</td> @endif
                            </tr>
                        @empty
                            <tr><td colspan="7" class="nt">لا توجد بيانات.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    @script
    <script>
        const cs = {};
        const palette = ['#2563eb', '#d97706', '#059669', '#dc2626', '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16', '#64748b'];

        const make = (id, cfg) => {
            const el = document.getElementById(id);
            if (!el) return;
            if (cs[id]) cs[id].destroy();
            cs[id] = new Chart(el, cfg);
        };

        const base = (extra = {}) => ({
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', rtl: true }, tooltip: { rtl: true, textDirection: 'rtl' } },
            ...extra,
        });

        const draw = (c) => {
            if (!c || !c.trend || typeof Chart === 'undefined') return;
            Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;

            const t = c.trend;
            const ds = [
                { label: 'الزيارات', data: t.visits, borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,.12)', fill: true, tension: .3 },
                { label: 'إضافة للسلة', data: t.carts, borderColor: '#d97706', tension: .3 },
                { label: 'طلبات', data: t.orders, borderColor: '#059669', tension: .3 },
            ];
            if (t.prev_visits && t.prev_visits.length) {
                ds.push({ label: 'زيارات (الفترة السابقة)', data: t.prev_visits, borderColor: '#9ca3af', borderDash: [6, 4], pointRadius: 0, tension: .3 });
            }
            make('chTrend', {
                type: 'line',
                data: { labels: t.labels, datasets: ds },
                options: base({ interaction: { mode: 'index', intersect: false }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }),
            });

            make('chSources', {
                type: 'bar',
                data: {
                    labels: c.sources.labels,
                    datasets: [
                        { label: 'زيارات', data: c.sources.sessions, backgroundColor: '#93c5fd' },
                        { label: 'سلة', data: c.sources.carts, backgroundColor: '#fcd34d' },
                        { label: 'طلبات', data: c.sources.orders, backgroundColor: '#34d399' },
                    ],
                },
                options: base({ scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }),
            });

            make('chCountries', {
                type: 'bar',
                data: {
                    labels: c.countries.labels,
                    datasets: [
                        { label: 'زيارات', data: c.countries.sessions, backgroundColor: '#93c5fd' },
                        { label: 'طلبات', data: c.countries.orders, backgroundColor: '#34d399' },
                    ],
                },
                options: base({ indexAxis: 'y', scales: { x: { beginAtZero: true, ticks: { precision: 0 } } } }),
            });

            make('chDevices', {
                type: 'doughnut',
                data: { labels: c.devices.labels, datasets: [{ data: c.devices.sessions, backgroundColor: palette }] },
                options: base(),
            });
        };

        draw($wire.charts);
        $wire.$watch('charts', (v) => draw(v));
    </script>
    @endscript
</div>
