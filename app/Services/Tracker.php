<?php

namespace App\Services;

use App\Models\TrackEvent;
use App\Models\TrackSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class Tracker
{
    public const SESSION_KEY  = 'trk_sid';
    public const COOKIE       = 'trk_vid';
    public const IDLE_MINUTES = 30;

    /** مراحل الفانل بالترتيب (المفتاح = اسم الحدث) */
    public const FUNNEL = [
        'visit'               => 'الزيارات',
        'add_to_cart'         => 'إضافة للسلة',
        'login'               => 'تسجيل الدخول',
        'complete_popup_view' => 'ظهرت نافذة إكمال التسجيل',
        'complete_popup_done' => 'أكمل التسجيل',
        'checkout_view'       => 'صفحة إكمال الطلب',
        'purchase'            => 'طلب مكتمل',
    ];

    /** تسجيل حدث (كل مرة يُستدعى فيها) */
    public static function event(string $name, array $meta = [], ?Request $request = null): void
    {
        self::safely(function () use ($name, $meta, $request) {
            $s = self::current($request ?? request());
            if ($s) {
                self::push($s, $name, $meta);
            }
        });
    }

    /** تسجيل حدث مرة واحدة فقط لكل جلسة (للمراحل) */
    public static function once(string $name, array $meta = [], ?Request $request = null): void
    {
        self::safely(function () use ($name, $meta, $request) {
            $s = self::current($request ?? request());
            if (!$s) {
                return;
            }
            $exists = TrackEvent::where('session_id', $s->id)->where('name', $name)->exists();
            if (!$exists) {
                self::push($s, $name, $meta);
            }
        });
    }

    /** يستدعيه الميدلوير: يضمن وجود جلسة تتبع للزيارة */
    public static function touch(Request $request): void
    {
        self::safely(fn () => self::current($request));
    }

    public static function isBot(?string $ua): bool
    {
        if (!$ua) {
            return true;
        }

        return (bool) preg_match(
            '/bot|crawl|spider|slurp|facebookexternalhit|facebot|preview|monitor|uptime|pingdom|lighthouse|headless|curl|wget|python|java\/|go-http|scrapy/i',
            $ua
        );
    }

    // ------------------------------------------------------------------

    protected static function current(Request $r): ?TrackSession
    {
        if (self::isBot($r->userAgent())) {
            return null;
        }
        if ($cached = $r->attributes->get('trk_session')) {
            return $cached;
        }

        $vid = self::visitorId($r);
        $sid = $r->session()->get(self::SESSION_KEY);
        $s   = $sid ? TrackSession::find($sid) : null;

        if ($s && $s->last_seen_at && $s->last_seen_at->lt(now()->subMinutes(self::IDLE_MINUTES))) {
            $s = null; // خمول أكثر من 30 دقيقة = زيارة جديدة
        }

        $isNew = !$s;

        if ($isNew) {
            $s = self::create($r, $vid);
            $r->session()->put(self::SESSION_KEY, $s->id);
        } else {
            $dirty = [];
            if (!$s->last_seen_at || $s->last_seen_at->lt(now()->subMinute())) {
                $dirty['last_seen_at'] = now();
            }
            if (!$s->user_id && ($uid = $r->user()?->id)) {
                $dirty['user_id'] = $uid;
            }
            if ($dirty) {
                $s->update($dirty);
            }
        }

        $r->attributes->set('trk_session', $s);

        if ($isNew) {
            self::push($s, 'visit');
        }

        return $s;
    }

    protected static function visitorId(Request $r): string
    {
        if ($v = $r->attributes->get('trk_vid')) {
            return $v;
        }

        $v = $r->cookie(self::COOKIE);

        if (!is_string($v) || !Str::isUuid($v)) {
            $v = (string) Str::uuid();
            Cookie::queue(Cookie::make(self::COOKIE, $v, 60 * 24 * 365 * 2, '/', null, $r->isSecure(), true, false, 'lax'));
        }

        $r->attributes->set('trk_vid', $v);

        return $v;
    }

    protected static function create(Request $r, string $vid): TrackSession
    {
        [$source, $medium, $campaign] = self::attribution($r);
        $ua = (string) $r->userAgent();

        return TrackSession::create([
            'visitor_id'    => $vid,
            'user_id'       => $r->user()?->id,
            'ip'            => $r->ip(),
            'country'       => self::country($r),
            'device'        => self::device($ua),
            'os'            => self::os($ua),
            'browser'       => self::browser($ua),
            'source'        => $source,
            'medium'        => $medium,
            'campaign'      => $campaign,
            'content'       => $r->query('utm_content'),
            'term'          => $r->query('utm_term'),
            'referrer'      => Str::limit((string) $r->headers->get('referer'), 250, ''),
            'landing_page'  => Str::limit($r->fullUrl(), 295, ''),
            'first_seen_at' => now(),
            'last_seen_at'  => now(),
        ]);
    }

    protected static function push(TrackSession $s, string $name, array $meta = []): void
    {
        TrackEvent::create([
            'session_id' => $s->id,
            'visitor_id' => $s->visitor_id,
            'user_id'    => $s->user_id,
            'name'       => $name,
            'meta'       => $meta ?: null,
            'created_at' => now(),
        ]);
    }

    /** المصدر / الوسيط / الحملة */
    protected static function attribution(Request $r): array
    {
        $q        = $r->query();
        $source   = $q['utm_source'] ?? null;
        $medium   = $q['utm_medium'] ?? null;
        $campaign = $q['utm_campaign'] ?? null;

        // معرّفات النقرات التلقائية للمنصات الإعلانية
        if (!$source) {
            if (isset($q['gclid']) || isset($q['gbraid']) || isset($q['wbraid'])) {
                [$source, $medium] = ['google', 'cpc'];
            } elseif (isset($q['fbclid'])) {
                [$source, $medium] = ['facebook', 'paid'];
            } elseif (isset($q['ttclid'])) {
                [$source, $medium] = ['tiktok', 'paid'];
            } elseif (isset($q['ScCid']) || isset($q['sc_cid'])) {
                [$source, $medium] = ['snapchat', 'paid'];
            }
        }

        // من الريفرر
        if (!$source) {
            $host = parse_url((string) $r->headers->get('referer'), PHP_URL_HOST);
            if ($host && $host !== $r->getHost()) {
                $map = [
                    'google' => 'google', 'bing' => 'bing', 'facebook' => 'facebook',
                    'instagram' => 'instagram', 't.co' => 'twitter', 'twitter' => 'twitter',
                    'x.com' => 'twitter', 'snapchat' => 'snapchat', 'tiktok' => 'tiktok',
                    'youtube' => 'youtube', 'whatsapp' => 'whatsapp', 't.me' => 'telegram',
                    'telegram' => 'telegram',
                ];
                $source = $host;
                $medium = 'referral';
                foreach ($map as $needle => $name) {
                    if (str_contains($host, $needle)) {
                        $source = $name;
                        $medium = in_array($name, ['google', 'bing'], true) ? 'organic' : 'social';
                        break;
                    }
                }
            }
        }

        return [
            strtolower($source ?: 'direct'),
            strtolower($medium ?: 'none'),
            $campaign ? Str::limit($campaign, 150, '') : null,
        ];
    }

    /** الدولة من هيدر Cloudflare (أو عدّلها لـ GeoIP) */
    protected static function country(Request $r): ?string
    {
        $c = strtoupper((string) ($r->header('CF-IPCountry') ?: $r->header('X-Country-Code') ?: ''));

        return preg_match('/^[A-Z]{2}$/', $c) && !in_array($c, ['XX', 'T1'], true) ? $c : null;
    }

    protected static function device(string $ua): string
    {
        if (preg_match('/ipad|tablet|(android(?!.*mobile))/i', $ua)) {
            return 'tablet';
        }
        if (preg_match('/mobile|iphone|ipod|android/i', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    protected static function os(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/iphone|ipad|ipod/i', $ua) => 'iOS',
            (bool) preg_match('/android/i', $ua)          => 'Android',
            (bool) preg_match('/windows/i', $ua)          => 'Windows',
            (bool) preg_match('/mac os/i', $ua)           => 'macOS',
            (bool) preg_match('/linux/i', $ua)            => 'Linux',
            default                                       => 'Other',
        };
    }

    protected static function browser(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/snapchat/i', $ua)          => 'Snapchat (داخل التطبيق)',
            (bool) preg_match('/instagram/i', $ua)         => 'Instagram (داخل التطبيق)',
            (bool) preg_match('/FBAN|FBAV/i', $ua)         => 'Facebook (داخل التطبيق)',
            (bool) preg_match('/tiktok|musical_ly/i', $ua) => 'TikTok (داخل التطبيق)',
            (bool) preg_match('/edg\//i', $ua)             => 'Edge',
            (bool) preg_match('/opr\//i', $ua)             => 'Opera',
            (bool) preg_match('/firefox|fxios/i', $ua)     => 'Firefox',
            (bool) preg_match('/chrome|crios/i', $ua)      => 'Chrome',
            (bool) preg_match('/safari/i', $ua)            => 'Safari',
            default                                        => 'Other',
        };
    }

    protected static function safely(callable $fn): void
    {
        try {
            $fn();
        } catch (\Throwable $e) {
            report($e); // التتبع ما يكسر الموقع أبداً
        }
    }
}
