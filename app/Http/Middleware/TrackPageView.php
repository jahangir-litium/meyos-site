<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    /** Регулярки для определения ботов (минимальный, но рабочий набор). */
    private const BOT_PATTERNS = '/bot|crawler|spider|crawling|googlebot|bingbot|yandex|baidu|duckduck|slurp|facebookexternalhit|whatsapp|telegram|preview/i';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Считаем только успешные GET, не админку и не assets/api
        if (
            $request->isMethod('GET')
            && $response->getStatusCode() < 400
            && !$request->is('admin*', 'livewire*', 'storage/*', '*.css', '*.js', '*.ico', 'build/*', '_ignition/*', 'sanctum/*')
        ) {
            try {
                $ua  = (string) $request->userAgent();
                $bot = (bool) preg_match(self::BOT_PATTERNS, $ua);

                // UTM: первое касание залипает в сессии, чтобы не потерять при кликах внутри сайта
                $utm = [
                    'utm_source'   => $request->query('utm_source'),
                    'utm_medium'   => $request->query('utm_medium'),
                    'utm_campaign' => $request->query('utm_campaign'),
                    'utm_content'  => $request->query('utm_content'),
                    'utm_term'     => $request->query('utm_term'),
                ];
                if (!empty(array_filter($utm))) {
                    session(['utm' => $utm]);
                } else {
                    $utm = session('utm', $utm);
                }

                PageView::create([
                    'path'         => substr($request->path() === '/' ? '/' : '/' . $request->path(), 0, 255),
                    'page_slug'    => $this->detectSlug($request),
                    'locale'       => app()->getLocale(),
                    'referrer'     => substr((string) $request->headers->get('referer', ''), 0, 500) ?: null,
                    'utm_source'   => $utm['utm_source']   ? substr((string) $utm['utm_source'], 0, 100)   : null,
                    'utm_medium'   => $utm['utm_medium']   ? substr((string) $utm['utm_medium'], 0, 100)   : null,
                    'utm_campaign' => $utm['utm_campaign'] ? substr((string) $utm['utm_campaign'], 0, 100) : null,
                    'utm_content'  => $utm['utm_content']  ? substr((string) $utm['utm_content'], 0, 100)  : null,
                    'utm_term'     => $utm['utm_term']     ? substr((string) $utm['utm_term'], 0, 100)     : null,
                    'ip_hash'      => hash('sha256', (string) $request->ip() . config('app.key')),
                    'user_agent'   => substr($ua, 0, 500),
                    'device'       => $this->detectDevice($ua),
                    'is_bot'       => $bot,
                    'created_at'   => now(),
                ]);
            } catch (\Throwable $e) {
                // Логирование посещения не должно ронять страницу
                report($e);
            }
        }

        return $response;
    }

    private function detectSlug(Request $req): ?string
    {
        $path = trim($req->path(), '/');
        if ($path === '') return 'home';

        // Если первый сегмент — язык, берём второй
        $parts = explode('/', $path);
        if (in_array($parts[0] ?? '', ['ru', 'uz', 'en'], true)) {
            array_shift($parts);
        }
        return ($parts[0] ?? null) ?: 'home';
    }

    private function detectDevice(string $ua): string
    {
        if (preg_match('/Mobile|Android|iPhone|iPod/i', $ua)) return 'mobile';
        if (preg_match('/iPad|Tablet/i', $ua)) return 'tablet';
        return 'desktop';
    }
}
