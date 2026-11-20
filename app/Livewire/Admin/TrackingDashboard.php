<?php

namespace App\Livewire\Admin;

use App\Models\TrackEvent;
use App\Models\TrackSession;
use App\Services\Tracker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TrackingDashboard extends Component
{
    // الفلاتر
    public string $period = 'today';   // today|yesterday|week|7d|month|last_month|custom
    public bool $compare = true;
    public ?string $from = null;
    public ?string $to = null;
    public string $source = '';
    public string $device = '';
    public string $country = '';

    // البيانات (مصفوفات بسيطة حتى تنتقل للمتصفح)
    public array $kpis = [];
    public array $funnel = [];
    public array $insights = [];
    public array $charts = [];
    public array $tables = [];
    public array $abandoned = [];
    public array $options = [];
    public string $rangeLabel = '';
    public string $prevLabel = '';

    public function mount(): void
    {
        $this->options = [
            'sources'   => TrackSession::query()->distinct()->orderBy('source')->limit(60)->pluck('source')->all(),
            'countries' => TrackSession::query()->whereNotNull('country')->distinct()->orderBy('country')->pluck('country')->all(),
            'devices'   => ['mobile', 'tablet', 'desktop'],
        ];
        $this->loadData();
    }

    public function updated($name): void
    {
        $this->loadData();
    }

    public function setPeriod(string $p): void
    {
        $this->period = $p;
        $this->loadData();
    }

    // ------------------------------------------------------------------

    public function loadData(): void
    {
        [$a, $b, $pa, $pb] = $this->range();

        $cur  = $this->funnelCounts($a, $b);
        $prev = $this->compare ? $this->funnelCounts($pa, $pb) : null;

        $fmt = fn (Carbon $x, Carbon $y) => $x->format('Y-m-d') . ($x->isSameDay($y) ? '' : ' → ' . $y->format('Y-m-d'));
        $this->rangeLabel = $fmt($a, $b);
        $this->prevLabel  = $this->compare ? $fmt($pa, $pb) : '';

        $sources   = $this->breakdown('source', $a, $b, 10);
        $campaigns = $this->breakdown('campaign', $a, $b, 15, true);
        $countries = array_map(fn ($r) => ['label' => $this->countryLabel($r['label'] === '—' ? null : $r['label'])] + $r, $this->breakdown('country', $a, $b, 10));
        $devices   = $this->breakdown('device', $a, $b, 5);
        $oses      = $this->breakdown('os', $a, $b, 6);
        $browsers  = $this->breakdown('browser', $a, $b, 8);

        $this->funnel    = $this->buildFunnel($cur, $prev);
        $this->kpis      = $this->buildKpis($cur, $prev, $a, $b, $pa, $pb);
        $this->insights  = $this->buildInsights($cur, $devices, $campaigns);
        $this->abandoned = $this->abandonedList($a, $b);

        $series = fn (array $rows) => [
            'labels'   => array_column($rows, 'label'),
            'sessions' => array_column($rows, 'sessions'),
            'carts'    => array_column($rows, 'carts'),
            'orders'   => array_column($rows, 'orders'),
        ];

        $trend = $this->trend($a, $b);
        $trend['prev_visits'] = $this->compare ? $this->trend($pa, $pb)['visits'] : [];

        $this->charts = [
            'trend'     => $trend,
            'sources'   => $series($sources),
            'countries' => $series($countries),
            'devices'   => $series($devices),
        ];

        $mk = fn (string $title, string $col, array $rows) => [
            'title'   => $title,
            'col'     => $col,
            'rows'    => $rows,
            'revenue' => array_sum(array_column($rows, 'revenue')) > 0,
        ];

        $this->tables = [
            $mk('المصادر', 'المصدر', $sources),
            $mk('الحملات الإعلانية', 'الحملة', $campaigns),
            $mk('الدول', 'الدولة', $countries),
            $mk('الأجهزة', 'الجهاز', $devices),
            $mk('أنظمة التشغيل', 'النظام', $oses),
            $mk('المتصفحات', 'المتصفح', $browsers),
        ];
    }

    public function render()
    {
        return view('livewire.admin.tracking-dashboard');
    }

    // ------------------------------------------------------------------
    // الفترات
    // ------------------------------------------------------------------

    protected function range(): array
    {
        $now = now();

        switch ($this->period) {
            case 'yesterday':
                $a = $now->copy()->subDay()->startOfDay();
                $b = $now->copy()->subDay()->endOfDay();
                break;
            case 'week':
                $a = $now->copy()->startOfWeek(Carbon::SATURDAY);
                $b = $now->copy()->endOfDay();
                break;
            case '7d':
                $a = $now->copy()->subDays(6)->startOfDay();
                $b = $now->copy()->endOfDay();
                break;
            case 'month':
                $a = $now->copy()->startOfMonth();
                $b = $now->copy()->endOfDay();
                break;
            case 'last_month':
                $a = $now->copy()->subMonthNoOverflow()->startOfMonth();
                $b = $now->copy()->subMonthNoOverflow()->endOfMonth();
                break;
            case 'custom':
                $a = Carbon::parse($this->from ?: $now)->startOfDay();
                $b = Carbon::parse($this->to ?: $now)->endOfDay();
                if ($b->lt($a)) {
                    [$a, $b] = [$b->copy()->startOfDay(), $a->copy()->endOfDay()];
                }
                break;
            default: // today
                $a = $now->copy()->startOfDay();
                $b = $now->copy()->endOfDay();
        }

        // الفترة السابقة بنفس الطول مباشرة قبل الفترة الحالية
        $len = $b->timestamp - $a->timestamp + 1;
        $pb  = $a->copy()->subSecond();
        $pa  = $a->copy()->subSeconds($len);

        return [$a, $b, $pa, $pb];
    }

    protected function sessions(Carbon $a, Carbon $b)
    {
        return TrackSession::query()
            ->whereBetween('first_seen_at', [$a, $b])
            ->when($this->source, fn ($q) => $q->where('source', $this->source))
            ->when($this->device, fn ($q) => $q->where('device', $this->device))
            ->when($this->country, fn ($q) => $q->where('country', $this->country));
    }

    // ------------------------------------------------------------------
    // الفانل (حسب الجلسات التي بدأت في الفترة)
    // ------------------------------------------------------------------

    protected function funnelCounts(Carbon $a, Carbon $b): array
    {
        $base = $this->sessions($a, $b);

        $sessions = (clone $base)->count();
        $visitors = (clone $base)->distinct()->count('visitor_id');
        $loginS   = (clone $base)->whereNotNull('user_id')->count();
        $loginV   = (clone $base)->whereNotNull('user_id')->distinct()->count('visitor_id');

        $ev = TrackEvent::query()
            ->whereIn('session_id', $this->sessions($a, $b)->select('id'))
            ->select('name', DB::raw('COUNT(DISTINCT session_id) as s'), DB::raw('COUNT(DISTINCT visitor_id) as v'))
            ->groupBy('name')
            ->get()
            ->keyBy('name');

        $out = [];
        foreach (array_keys(Tracker::FUNNEL) as $key) {
            $out[$key] = match ($key) {
                'visit' => ['s' => $sessions, 'v' => $visitors],
                // "تسجيل الدخول" = أي جلسة صار فيها مستخدم مسجّل (يشمل من دخل قبل الزيارة)
                'login' => ['s' => $loginS, 'v' => $loginV],
                default => ['s' => (int) ($ev[$key]->s ?? 0), 'v' => (int) ($ev[$key]->v ?? 0)],
            };
        }

        return $out;
    }

    protected function buildFunnel(array $cur, ?array $prev): array
    {
        $rows    = [];
        $prevKey = null;
        $first   = max(1, $cur['visit']['s']);

        foreach (Tracker::FUNNEL as $key => $label) {
            $s = $cur[$key]['s'];
            $rows[] = [
                'key'           => $key,
                'label'         => $label,
                'sessions'      => $s,
                'visitors'      => $cur[$key]['v'],
                'of_total'      => round($s / $first * 100, 1),
                'of_prev'       => $prevKey ? ($cur[$prevKey]['s'] > 0 ? min(100, round($s / $cur[$prevKey]['s'] * 100, 1)) : 0) : 100.0,
                'drop'          => $prevKey ? max(0, $cur[$prevKey]['s'] - $s) : 0,
                'prev_sessions' => $prev ? $prev[$key]['s'] : null,
            ];
            $prevKey = $key;
        }

        return $rows;
    }

    protected function buildKpis(array $c, ?array $p, Carbon $a, Carbon $b, Carbon $pa, Carbon $pb): array
    {
        $rate = fn ($x, $y) => $y > 0 ? round($x / $y * 100, 2) : 0.0;

        $defs = [
            ['visits',    'إجمالي الزيارات',     'count', 'up',   $c['visit']['s'],       $p['visit']['s'] ?? null],
            ['visitors',  'زوار فريدون',         'count', 'up',   $c['visit']['v'],       $p['visit']['v'] ?? null],
            ['carts',     'إضافة للسلة',         'count', 'up',   $c['add_to_cart']['s'], $p['add_to_cart']['s'] ?? null],
            ['cart_rate', 'نسبة الإضافة للسلة',  'rate',  'up',   $rate($c['add_to_cart']['s'], $c['visit']['s']), $p ? $rate($p['add_to_cart']['s'], $p['visit']['s']) : null],
            ['orders',    'طلبات مكتملة',        'count', 'up',   $c['purchase']['s'],    $p['purchase']['s'] ?? null],
            ['conv',      'نسبة التحويل الكلية', 'rate',  'up',   $rate($c['purchase']['s'], $c['visit']['s']), $p ? $rate($p['purchase']['s'], $p['visit']['s']) : null],
            ['abandoned', 'سلات متروكة',         'count', 'down', $this->abandonedQuery($a, $b)->count(), $p ? $this->abandonedQuery($pa, $pb)->count() : null],
        ];

        return array_map(function ($d) {
            [$key, $label, $type, $good, $value, $prev] = $d;
            $delta = null;
            if ($prev !== null) {
                $delta = $type === 'rate'
                    ? round($value - $prev, 2)
                    : ($prev > 0 ? round(($value - $prev) / $prev * 100, 1) : null);
            }

            return compact('key', 'label', 'type', 'good', 'value', 'prev', 'delta');
        }, $defs);
    }

    /** اكتشاف أكبر تسرّب تلقائياً (النسب المرجعية تقريبية، عدّلها حسب نشاطك) */
    protected function buildInsights(array $c, array $devices, array $campaigns): array
    {
        $out = [];

        $pairs = [
            ['visit', 'add_to_cart', 'من الزيارة إلى إضافة للسلة', 8],
            ['add_to_cart', 'login', 'من السلة إلى تسجيل الدخول', 60],
            ['login', 'checkout_view', 'من تسجيل الدخول إلى صفحة إكمال الطلب', 70],
            ['checkout_view', 'purchase', 'من صفحة إكمال الطلب إلى الطلب المكتمل', 50],
            ['complete_popup_view', 'complete_popup_done', 'إكمال التسجيل في النافذة', 75],
        ];

        $worst = null;
        foreach ($pairs as [$from, $to, $label, $bench]) {
            $base = $c[$from]['s'];
            if ($base < 10) {
                continue;
            }
            $rate  = min(100, $c[$to]['s'] / $base * 100);
            $ratio = $rate / $bench;
            if (!$worst || $ratio < $worst['ratio']) {
                $worst = [
                    'ratio' => $ratio, 'label' => $label, 'rate' => round($rate, 1),
                    'bench' => $bench, 'lost' => max(0, $base - $c[$to]['s']),
                ];
            }
        }
        if ($worst && $worst['ratio'] < 0.9) {
            $out[] = ['type' => 'warn', 'text' => "أكبر تسرّب: {$worst['label']} — فقط {$worst['rate']}% يكملون (المعتاد حوالي {$worst['bench']}%)، وخسرت {$worst['lost']} جلسة."];
        }

        $byDevice = collect($devices)->keyBy('label');
        $m = $byDevice['mobile'] ?? null;
        $d = $byDevice['desktop'] ?? null;
        if ($m && $d && $m['sessions'] >= 30 && $d['sessions'] >= 30 && $d['conv'] > 0 && $m['conv'] < $d['conv'] * 0.6) {
            $out[] = ['type' => 'warn', 'text' => "الجوال يحوّل {$m['conv']}% مقابل {$d['conv']}% للكمبيوتر — راجع تجربة الجوال (السلة، تسجيل الدخول، الدفع)."];
        }

        foreach ($campaigns as $camp) {
            if ($camp['sessions'] >= 30 && $camp['orders'] === 0) {
                $out[] = ['type' => 'warn', 'text' => "الحملة «{$camp['label']}» جابت {$camp['sessions']} زيارة بدون أي طلب."];
            }
        }

        if (!$out && $c['visit']['s'] >= 10) {
            $out[] = ['type' => 'info', 'text' => 'ما ظهرت تسرّبات واضحة حسب النسب المرجعية في هذي الفترة.'];
        }

        return $out;
    }

    // ------------------------------------------------------------------
    // السلات المتروكة
    // ------------------------------------------------------------------

    /** جلسة فيها إضافة للسلة، خاملة 30 دقيقة+، وما اشترى صاحبها (لا بنفس الجلسة ولا بعدها) */
    protected function abandonedQuery(Carbon $a, Carbon $b)
    {
        return $this->sessions($a, $b)
            ->where('last_seen_at', '<', now()->subMinutes(Tracker::IDLE_MINUTES))
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))->from('track_events as e')
                    ->whereColumn('e.session_id', 'track_sessions.id')
                    ->where('e.name', 'add_to_cart');
            })
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))->from('track_events as p')
                    ->whereColumn('p.visitor_id', 'track_sessions.visitor_id')
                    ->where('p.name', 'purchase')
                    ->whereColumn('p.created_at', '>=', 'track_sessions.first_seen_at');
            });
    }

    protected function abandonedList(Carbon $a, Carbon $b): array
    {
        $rows = $this->abandonedQuery($a, $b)
            ->with([
                'user',
                'events' => fn ($q) => $q->select('id', 'session_id', 'name', 'meta')->orderBy('id'),
            ])
            ->orderByDesc('last_seen_at')
            ->limit(15)
            ->get();

        return $rows->map(function ($s) {
            $names = $s->events->pluck('name')->all();
            if ($s->user_id) {
                $names[] = 'login';
            }
            $last = 'visit';
            foreach (array_keys(Tracker::FUNNEL) as $k) {
                if (in_array($k, $names, true)) {
                    $last = $k;
                }
            }
            $cartEvent = $s->events->where('name', 'add_to_cart')->last();

            return [
                'time'    => $s->last_seen_at?->format('m-d H:i'),
                'who'     => $s->user?->name ?? 'زائر',
                'phone'   => $s->user?->phone ?? null,   // عدّل حسب أعمدة جدول المستخدمين
                'source'  => $s->source . ($s->campaign ? ' / ' . $s->campaign : ''),
                'country' => $this->countryLabel($s->country),
                'device'  => $s->device,
                'stage'   => Tracker::FUNNEL[$last],
                'value'   => $cartEvent ? data_get($cartEvent->meta, 'value') : null,
            ];
        })->all();
    }

    // ------------------------------------------------------------------
    // التقسيمات والاتجاه
    // ------------------------------------------------------------------

    protected function breakdown(string $col, Carbon $a, Carbon $b, int $limit = 10, bool $skipNull = false): array
    {
        abort_unless(in_array($col, ['source', 'medium', 'campaign', 'country', 'device', 'os', 'browser'], true), 500);

        $q = $this->sessions($a, $b)->selectRaw(
            "{$col} as label,
             COUNT(*) as sessions,
             COUNT(DISTINCT visitor_id) as visitors,
             SUM(EXISTS(SELECT 1 FROM track_events e WHERE e.session_id = track_sessions.id AND e.name = 'add_to_cart')) as carts,
             SUM(EXISTS(SELECT 1 FROM track_events e WHERE e.session_id = track_sessions.id AND e.name = 'purchase')) as orders,
             SUM(COALESCE((SELECT SUM(CAST(JSON_UNQUOTE(JSON_EXTRACT(e.meta, '$.value')) AS DECIMAL(14,2)))
                           FROM track_events e WHERE e.session_id = track_sessions.id AND e.name = 'purchase'), 0)) as revenue"
        );

        if ($skipNull) {
            $q->whereNotNull($col);
        }

        return $q->groupBy($col)->orderByDesc('sessions')->limit($limit)->get()->map(fn ($r) => [
            'label'     => $r->label ?: '—',
            'sessions'  => (int) $r->sessions,
            'visitors'  => (int) $r->visitors,
            'carts'     => (int) $r->carts,
            'orders'    => (int) $r->orders,
            'revenue'   => round((float) $r->revenue, 2),
            'conv'      => $r->sessions > 0 ? round($r->orders / $r->sessions * 100, 2) : 0,
            'cart_rate' => $r->sessions > 0 ? round($r->carts / $r->sessions * 100, 2) : 0,
        ])->all();
    }

    protected function trend(Carbon $a, Carbon $b): array
    {
        $hourly = $a->isSameDay($b);
        $fmt    = $hourly ? '%H' : '%Y-%m-%d';

        $labels = [];
        if ($hourly) {
            for ($h = 0; $h < 24; $h++) {
                $labels[] = sprintf('%02d', $h);
            }
        } else {
            for ($d = $a->copy()->startOfDay(); $d->lte($b); $d->addDay()) {
                $labels[] = $d->format('Y-m-d');
            }
        }

        $visits = $this->sessions($a, $b)
            ->selectRaw("DATE_FORMAT(first_seen_at, '{$fmt}') as k, COUNT(*) as c")
            ->groupBy('k')->pluck('c', 'k');

        $ids = $this->sessions($a, $b)->select('id');
        $ev  = fn (string $n) => TrackEvent::query()
            ->whereIn('session_id', $ids)->where('name', $n)
            ->selectRaw("DATE_FORMAT(created_at, '{$fmt}') as k, COUNT(DISTINCT session_id) as c")
            ->groupBy('k')->pluck('c', 'k');

        $carts  = $ev('add_to_cart');
        $orders = $ev('purchase');
        $map    = fn ($col) => array_map(fn ($l) => (int) ($col[$l] ?? 0), $labels);

        return [
            'labels' => $labels,
            'visits' => $map($visits),
            'carts'  => $map($carts),
            'orders' => $map($orders),
        ];
    }

    protected function countryLabel(?string $c): string
    {
        if (!$c || strlen($c) !== 2) {
            return 'غير معروف';
        }
        $c    = strtoupper($c);
        $flag = mb_chr(0x1F1E6 + ord($c[0]) - 65) . mb_chr(0x1F1E6 + ord($c[1]) - 65);
        $name = class_exists(\Locale::class) ? (\Locale::getDisplayRegion('-' . $c, 'ar') ?: $c) : $c;

        return "{$flag} {$name}";
    }
}
