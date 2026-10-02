<?php

namespace App\Support;

/**
 * Генератор embed-кода для видео по публичной ссылке.
 *
 * Поддерживает:
 *  - YouTube: watch?v=…, youtu.be/…, /embed/…, /shorts/…
 *  - Instagram: /p/…, /reel/…, /tv/…
 *
 * Если ссылка не распознана — возвращает пустую строку (на фронте блок просто
 * не рендерится).
 *
 * CSP: домены youtube.com, youtube-nocookie.com, instagram.com должны быть
 * разрешены в frame-src (сделано в SecurityHeaders middleware).
 */
class VideoEmbed
{
    public static function html(?string $url): string
    {
        $url = trim((string) $url);
        if ($url === '') return '';

        if ($yt = self::youTubeId($url)) {
            return self::youTubeEmbed($yt);
        }
        if ($ig = self::instagramShortcode($url)) {
            return self::instagramEmbed($url);
        }
        return '';
    }

    /** Валидный ли URL для встраивания — для подсказки в админке. */
    public static function isValid(?string $url): bool
    {
        $url = trim((string) $url);
        if ($url === '') return false;
        return self::youTubeId($url) !== null || self::instagramShortcode($url) !== null;
    }

    public static function provider(?string $url): ?string
    {
        if (self::youTubeId((string) $url)) return 'youtube';
        if (self::instagramShortcode((string) $url)) return 'instagram';
        return null;
    }

    // ---------- YouTube ----------

    /** Извлечь video_id из любой стандартной формы URL. */
    private static function youTubeId(string $url): ?string
    {
        $patterns = [
            '#(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/|youtube\.com/shorts/|youtube\.com/v/)([A-Za-z0-9_-]{10,15})#i',
        ];
        foreach ($patterns as $re) {
            if (preg_match($re, $url, $m)) {
                return $m[1];
            }
        }
        return null;
    }

    private static function youTubeEmbed(string $videoId): string
    {
        $src = 'https://www.youtube-nocookie.com/embed/' . $videoId . '?rel=0&modestbranding=1';
        return '<div class="video-embed video-embed--youtube">'
             .   '<iframe src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"'
             .     ' title="YouTube video"'
             .     ' loading="lazy"'
             .     ' allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"'
             .     ' referrerpolicy="strict-origin-when-cross-origin"'
             .     ' allowfullscreen></iframe>'
             . '</div>';
    }

    // ---------- Instagram ----------

    /** Извлечь shortcode (/p/<CODE> или /reel/<CODE> или /tv/<CODE>). */
    private static function instagramShortcode(string $url): ?string
    {
        if (preg_match('#instagram\.com/(?:p|reel|tv)/([A-Za-z0-9_-]{5,20})/?#i', $url, $m)) {
            return $m[1];
        }
        return null;
    }

    private static function instagramEmbed(string $url): string
    {
        // Instagram embed = blockquote + их официальный embed.js (lazy-loaded скрипт).
        // Скрипт сам находит blockquote и превращает его в красивый плеер.
        $cleanUrl = rtrim(preg_replace('/\?.*$/', '', $url), '/') . '/';
        return '<div class="video-embed video-embed--instagram">'
             .   '<blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="' . htmlspecialchars($cleanUrl, ENT_QUOTES, 'UTF-8') . '" data-instgrm-version="14" style="background:#FFF; border:0; border-radius:3px; margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%;"></blockquote>'
             .   '<script async src="//www.instagram.com/embed.js"></script>'
             . '</div>';
    }
}
