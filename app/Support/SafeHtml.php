<?php

namespace App\Support;

/**
 * Санитизация HTML от rich-editor перед выводом на фронт.
 *
 * Защита от stored XSS, если админ-сессия скомпрометирована или
 * контент пришёл из внешнего источника (например, SAVDEX).
 *
 * Разрешённые теги = базовый whitelist для статей:
 *   p, br, a, ul, ol, li, h2, h3, h4, strong, b, em, i,
 *   blockquote, img, table/thead/tbody/tr/th/td, code, pre, hr
 *
 * Отбрасываются: все event-handlers (on*), href/src с javascript:/data:/vbscript:,
 * <script>, <iframe>, <style>, <object>, <embed>, <form> и т.д.
 */
class SafeHtml
{
    /** Теги, которые разрешены на выход (всё остальное будет вырезано). */
    private const ALLOWED_TAGS = '<p><br><a><ul><ol><li><h2><h3><h4><h5>'
        . '<strong><b><em><i><u><s>'
        . '<blockquote><img><figure><figcaption>'
        . '<table><thead><tbody><tr><th><td>'
        . '<code><pre><hr><span><div>';

    /** Атрибуты, которые остаются даже после фильтрации. */
    private const ALLOWED_ATTR_PATTERN = '/(href|src|alt|title|target|rel|class|colspan|rowspan|loading|decoding|width|height)/i';

    public static function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        // 1) Удаляем опасные теги целиком (вместе с содержимым)
        $html = preg_replace('#<(script|style|iframe|object|embed|form|svg)\b[^>]*>.*?</\1>#is', '', (string) $html);
        // 2) Удаляем самозакрывающиеся опасные теги
        $html = preg_replace('#<(script|style|iframe|object|embed|form|svg)\b[^>]*/?>#is', '', $html);

        // 3) strip_tags оставляет только whitelist
        $html = strip_tags($html, self::ALLOWED_TAGS);

        // 4) Удаляем event-handlers (on* атрибуты)
        $html = preg_replace('#\s*on\w+\s*=\s*"[^"]*"#i', '', $html);
        $html = preg_replace("#\s*on\w+\s*=\s*'[^']*'#i", '', $html);
        $html = preg_replace('#\s*on\w+\s*=\s*[^\s>]+#i', '', $html);

        // 5) Защита от javascript:/data:/vbscript: в href/src (сохраняем data: только для картинок)
        $html = preg_replace_callback(
            '#\s*(href|src|xlink:href)\s*=\s*(["\'])(.*?)\2#is',
            function ($m) {
                $attr = $m[1];
                $url  = trim($m[3]);
                $urlLower = strtolower($url);

                // Опасные схемы
                if (preg_match('#^\s*(javascript|vbscript|file):#i', $urlLower)) {
                    return '';
                }
                // data: разрешаем только для картинок (data:image/...)
                if (str_starts_with($urlLower, 'data:') && !str_starts_with($urlLower, 'data:image/')) {
                    return '';
                }

                return ' ' . $attr . '=' . $m[2] . $url . $m[2];
            },
            $html
        );

        // 6) Внешние ссылки — добавляем rel="noopener nofollow" если target="_blank" но rel не указан
        $html = preg_replace_callback(
            '#<a\b([^>]*target\s*=\s*["\']_blank["\'][^>]*)>#i',
            function ($m) {
                $attrs = $m[1];
                if (!preg_match('/\brel\s*=/i', $attrs)) {
                    $attrs .= ' rel="noopener nofollow"';
                }
                return '<a' . $attrs . '>';
            },
            $html
        );

        return trim($html);
    }
}
