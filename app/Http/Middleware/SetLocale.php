<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const SUPPORTED = ['ru', 'uz', 'en'];
    public const DEFAULT   = 'ru';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = self::DEFAULT;

        // 1. Из query: ?lang=uz (постоянное переключение)
        if ($request->query('lang') && in_array($request->query('lang'), self::SUPPORTED, true)) {
            $locale = $request->query('lang');
            cookie()->queue('meyos_locale', $locale, 60 * 24 * 365);
        }
        // 2. Из cookie
        elseif ($request->cookie('meyos_locale') && in_array($request->cookie('meyos_locale'), self::SUPPORTED, true)) {
            $locale = $request->cookie('meyos_locale');
        }
        // 3. Из Accept-Language
        else {
            $accept = (string) $request->header('Accept-Language', '');
            foreach (self::SUPPORTED as $code) {
                if (str_starts_with(strtolower($accept), $code)) {
                    $locale = $code;
                    break;
                }
            }
        }

        app()->setLocale($locale);
        view()->share('locale', $locale);
        view()->share('supportedLocales', self::SUPPORTED);

        return $next($request);
    }
}
