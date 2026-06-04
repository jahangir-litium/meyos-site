<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Кэш-заголовки для статики и HTML.
 *
 * Веб-сервер (nginx/Apache) обычно сам обслуживает /assets/* и /storage/* и
 * этот middleware на них не сработает. Но локально (php artisan serve)
 * мы прогоняем их через Laravel — и нужно поставить правильные заголовки.
 *
 * На прод-nginx добавьте:
 *   location ~* \.(css|js|jpg|jpeg|png|webp|svg|woff2)$ {
 *       expires 365d; add_header Cache-Control "public, immutable";
 *   }
 */
class StaticCacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse
            && !str_starts_with((string) $response->headers->get('Content-Type'), 'image/')
            && !$this->isStaticAsset($request)) {
            return $response;
        }

        // Кэш статики на 1 год + immutable (на проде хорошо иметь хэш в имени файла)
        if ($this->isStaticAsset($request)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('Vary', 'Accept-Encoding');
        }

        // Для HTML страниц — короткий кэш + revalidation
        if (str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            $response->headers->set('Cache-Control', 'public, max-age=300, must-revalidate');
        }

        return $response;
    }

    private function isStaticAsset(Request $request): bool
    {
        return $request->is('assets/*', 'storage/*', 'build/*')
            || preg_match('/\.(css|js|jpg|jpeg|png|webp|gif|svg|woff2?|ttf|ico|mp4|webm)$/i', $request->path());
    }
}
