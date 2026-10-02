<?php

namespace App\Support;

/**
 * Генератор embed-кода для YouTube по публичной ссылке.
 *
 * Поддерживает:
 *  - YouTube: watch?v=…, youtu.be/…, /embed/…, /shorts/…
 *
 * Если ссылка не распознана — возвращает пустую строку (блок не рендерится).
 *
 * CSP: домены youtube.com и youtube-nocookie.com разрешены в frame-src
 * (см. SecurityHeaders middleware).
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
        return '';
    }

    /** Валидна ли ссылка для встраивания — используется в Filament-валидации. */
    public static function isValid(?string $url): bool
    {
        $url = trim((string) $url);
        if ($url === '') return false;
        return self::youTubeId($url) !== null;
    }

    public static function provider(?string $url): ?string
    {
        return self::youTubeId((string) $url) ? 'youtube' : null;
    }

    // ---------- YouTube ----------

    private static function youTubeId(string $url): ?string
    {
        $re = '#(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/|youtube\.com/shorts/|youtube\.com/v/)([A-Za-z0-9_-]{10,15})#i';
        return preg_match($re, $url, $m) ? $m[1] : null;
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
}
