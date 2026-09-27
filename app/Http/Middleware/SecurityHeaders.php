<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Заголовки безопасности:
 *  - CSP (Content-Security-Policy) — что откуда можно грузить
 *  - HSTS — принудительный HTTPS в браузере
 *  - X-Content-Type-Options — запрет MIME-sniffing
 *  - X-Frame-Options — защита от clickjacking
 *  - Referrer-Policy — что отправлять в Referer
 *  - Permissions-Policy — ограничение доступа к device API
 *
 * Не применяется к /admin (Filament рендерит собственный CSP-совместимый JS).
 * Не применяется к /livewire (Livewire eval'ит код).
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Только для HTML-ответов на публичном фронте
        if ($request->is('admin*', 'livewire*', 'sitemap*', 'robots.txt', 'llms.txt')) {
            return $response;
        }

        $contentType = $response->headers->get('content-type', '');
        if (!str_contains($contentType, 'text/html')) {
            return $response;
        }

        // Content-Security-Policy: unsafe-inline нужен для Blade-inline-скриптов и стилей.
        // В следующей итерации перейдём на nonce.
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://mc.yandex.ru https://mc.yandex.com https://www.googletagmanager.com https://www.google-analytics.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' data: https://fonts.gstatic.com",
            "img-src 'self' data: blob: https:",
            "connect-src 'self' https://mc.yandex.ru https://mc.yandex.com https://www.google-analytics.com https://yandex.ru",
            "frame-src 'self' https://mc.yandex.ru https://yandex.ru https://*.yandex.ru https://www.google.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');

        // HSTS только для https-прода
        if ($request->isSecure() && app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
