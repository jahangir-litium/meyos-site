<?php

namespace App\Http\Middleware;

use App\Models\Partner;
use App\Models\PartnerView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Считает просмотр страницы конкретного партнёра.
 * - Дедуп: тот же (partner_id, ip_hash) чаще 30 мин не считаем
 * - Ботов не считаем
 * - Инкрементим денормализованные счётчики в partners
 */
class TrackPartnerView
{
    private const BOT_PATTERNS = '/bot|crawler|spider|crawling|googlebot|bingbot|yandex|baidu|duckduck|slurp|facebookexternalhit|whatsapp|telegram|preview/i';
    private const DEDUP_MINUTES = 30;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Только успешные GET
        if (!$request->isMethod('GET') || $response->getStatusCode() >= 400) {
            return $response;
        }

        try {
            $partner = $request->route('partner');
            if (!$partner instanceof Partner) {
                return $response;
            }

            $ua  = (string) $request->userAgent();
            if (preg_match(self::BOT_PATTERNS, $ua)) {
                return $response;
            }

            $ipHash = hash('sha256', (string) $request->ip() . config('app.key'));

            // Дедуп: если этот IP уже смотрел этого партнёра за последние 30 мин — не считаем
            $exists = PartnerView::where('partner_id', $partner->id)
                ->where('ip_hash', $ipHash)
                ->where('viewed_at', '>=', now()->subMinutes(self::DEDUP_MINUTES))
                ->exists();

            if ($exists) {
                return $response;
            }

            PartnerView::create([
                'partner_id'   => $partner->id,
                'ip_hash'      => $ipHash,
                'session_hash' => hash('sha256', (string) $request->session()->getId()),
                'ua_family'    => $this->detectUaFamily($ua),
                'referer_host' => $this->extractHost((string) $request->headers->get('referer', '')),
                'locale'       => app()->getLocale(),
                'viewed_at'    => now(),
            ]);

            // Атомарный инкремент счётчиков (не гонки при одновременных запросах)
            DB::table('partners')->where('id', $partner->id)->update([
                'views_count_total' => DB::raw('views_count_total + 1'),
                'views_count_30d'   => DB::raw('views_count_30d + 1'),
                'last_viewed_at'    => now(),
                'updated_at'        => now(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }

    private function detectUaFamily(string $ua): string
    {
        $os = match (true) {
            (bool) preg_match('/Windows/i', $ua)              => 'Windows',
            (bool) preg_match('/Mac OS/i', $ua)               => 'macOS',
            (bool) preg_match('/Android/i', $ua)              => 'Android',
            (bool) preg_match('/iPhone|iPad|iPod/i', $ua)     => 'iOS',
            (bool) preg_match('/Linux/i', $ua)                => 'Linux',
            default => 'Other',
        };
        $browser = match (true) {
            (bool) preg_match('/Edg\//i', $ua)      => 'Edge',
            (bool) preg_match('/OPR\//i', $ua)      => 'Opera',
            (bool) preg_match('/Firefox/i', $ua)    => 'Firefox',
            (bool) preg_match('/Chrome/i', $ua)     => 'Chrome',
            (bool) preg_match('/Safari/i', $ua)     => 'Safari',
            default => 'Other',
        };
        return substr("$browser / $os", 0, 60);
    }

    private function extractHost(string $url): ?string
    {
        if ($url === '') return null;
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) return null;
        // Не считаем внутренние переходы своим сайтом отдельно — но пишем как есть
        return substr((string) $host, 0, 120);
    }
}
