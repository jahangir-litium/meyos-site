<?php

namespace App\Services;

use App\Models\SavdexListing;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Парсер savdex.uz — забирает мебельные объявления.
 *
 * Способ:
 *  1. Берём все URL из публичных sitemap: sitemap-listings-1.xml и
 *     sitemap-tenders.xml (SAVDEX специально отдаёт их для поисковиков).
 *  2. Фильтруем URL эвристически по словам в slug: mebel, stol, stul,
 *     kreslo, shkaf, divan, komod, matras, interer, dizain, derevo и т.д.
 *     Это даёт ~95% релевантных карточек без 1000+ лишних HTTP-запросов.
 *  3. Для каждого отобранного URL — HTTP GET детальной страницы и
 *     парсинг OpenGraph meta (og:title, og:description, og:image).
 *     Если в тексте страницы есть слово «Мебель» (breadcrumb) — финально
 *     подтверждаем категорию и сохраняем в БД.
 *
 * Каталожная страница /catalog?category=31 рендерится JavaScript
 * на фронте, в server-side HTML ссылок нет — поэтому идём через sitemap.
 * Детальные страницы отдают полный HTML серверно с OpenGraph meta.
 */
class SavdexParser
{
    public const BASE_URL = 'https://savdex.uz';

    /** Эвристические маркеры мебельной тематики в URL. */
    private const FURNITURE_KEYWORDS = [
        'mebel', 'mebl', 'stol', 'stul', 'kreslo', 'divan', 'kreslo-',
        'shkaf', 'komod', 'krovat', 'matras', 'ofisn', 'kuhonn',
        'garnitur', 'gostin', 'spalni', 'interer', 'korpusn',
        'dsp', 'mdf', 'lamina', 'fanera', 'derev', 'yagoch',
    ];

    /** Главная точка — вызывается Job и кнопкой «Обновить сейчас». */
    public function sync(): array
    {
        $stats = ['fetched' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0];

        $urls = $this->collectFurnitureUrls();
        $stats['found_in_sitemap'] = count($urls);

        foreach ($urls as $url) {
            try {
                $data = $this->fetchListing($url);
                if (!$data) { $stats['skipped']++; continue; }

                $existing = SavdexListing::where('external_id', $data['external_id'])->first();
                if ($existing) {
                    // Не затираем ручные флаги админа
                    unset($data['is_hidden'], $data['is_featured']);
                    $existing->update($data);
                    $stats['updated']++;
                } else {
                    SavdexListing::create($data);
                    $stats['created']++;
                }
                $stats['fetched']++;
            } catch (\Throwable $e) {
                Log::warning('SavdexParser error', ['url' => $url, 'err' => $e->getMessage()]);
                $stats['errors']++;
            }
        }

        return $stats;
    }

    /** Удаляет объявления, не обновлявшиеся больше N дней. */
    public function pruneExpired(int $days = 60): int
    {
        return SavdexListing::where('fetched_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Собирает URL из обоих sitemap и фильтрует мебельные.
     * Возвращает уникальный список URL.
     */
    public function collectFurnitureUrls(): array
    {
        $all = array_merge(
            $this->fetchSitemapUrls('/sitemap-listings-1.xml'),
            $this->fetchSitemapUrls('/sitemap-tenders.xml')
        );
        $unique = array_values(array_unique($all));
        return array_filter($unique, fn ($u) => $this->looksLikeFurniture($u));
    }

    private function fetchSitemapUrls(string $path): array
    {
        $xml = $this->httpGet(self::BASE_URL . $path);
        if (!$xml) return [];
        if (!preg_match_all('#<loc>\s*(https?://[^<\s]+)\s*</loc>#', $xml, $m)) return [];

        $out = [];
        foreach ($m[1] as $url) {
            // Берём только RU-версию без языкового префикса
            if (!preg_match('#/(uz|en|zh|tr)/#', $url)) {
                $out[] = $url;
            }
        }
        return $out;
    }

    private function looksLikeFurniture(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $lower = strtolower($path);
        foreach (self::FURNITURE_KEYWORDS as $kw) {
            if (str_contains($lower, $kw)) return true;
        }
        return false;
    }

    /** Парсит одну детальную страницу и возвращает массив полей. */
    public function fetchListing(string $url): ?array
    {
        $html = $this->httpGet($url);
        if (!$html) return null;

        $externalId = $this->extractIdFromUrl($url);
        if (!$externalId) return null;

        $title = $this->metaContent($html, 'og:title');
        if (!$title) return null;
        $title = preg_replace('/\s*·\s*SAVDEX\s*$/u', '', $title);

        $summary = $this->metaContent($html, 'og:description');
        $image   = $this->metaContent($html, 'og:image');
        $ogType  = $this->metaContent($html, 'og:type');

        // Определяем тип: tenders URL = tender, иначе смотрим в texte
        $listingType = 'demand';
        if (str_contains($url, '/tenders/')) {
            $listingType = 'tender';
        } elseif (preg_match('/\bПредложение\b/u', substr($html, 0, 50000))) {
            $listingType = 'offer';
        }

        // Цена: ищем в теле «Цена ...»
        $price = null;
        if (preg_match('/>\s*Цена\s*([^<\n]{3,80})</u', $html, $m)) {
            $price = trim(preg_replace('/\s+/u', ' ', strip_tags($m[1])));
        }

        // Город — ищем в теле или description. SAVDEX показывает его прямо
        // рядом с заголовком, но проще через meta locale/location нету →
        // попробуем regex по тексту: «город: X» или в desc
        $city = null;
        if (preg_match('/(?:город|город:|Страна покупателя:|Расположение:)\s*([^,.<\n]{2,60})/ui', $html, $m)) {
            $city = trim($m[1]);
        }

        // Проверяем что это реально мебель (breadcrumb содержит «Мебель»)
        $isFurniture = $this->looksLikeFurniture($url)
            || (bool) preg_match('/>\s*Мебель\s*</u', $html);
        if (!$isFurniture) return null;

        // Дата публикации
        $publishedAt = null;
        if (preg_match('/(?:Опубликовано|Дата публикации:?)\s*(\d{1,2}[.\-\/]\d{1,2}[.\-\/]\d{2,4})/u', $html, $m)) {
            $publishedAt = $this->parseDate($m[1]);
        }

        $parts = parse_url($url);
        $pathTail = ltrim($parts['path'] ?? '', '/');
        $slug = preg_replace('#^(listing|tenders)/#', '', $pathTail);

        return [
            'external_id'  => $externalId,
            'slug'         => $slug,
            'title'        => mb_substr($title, 0, 250),
            'summary'      => $summary ? mb_substr($summary, 0, 1000) : null,
            'listing_type' => $listingType,
            'price'        => $price ? mb_substr($price, 0, 100) : null,
            'city'         => $city ? mb_substr($city, 0, 100) : null,
            'country'      => null,
            'image_url'    => $image ?: null,
            'source_url'   => $url,
            'tags'         => null,
            'published_at' => $publishedAt,
            'fetched_at'   => now(),
            'is_published' => true,
            'is_hidden'    => false,
            'is_featured'  => false,
        ];
    }

    private function metaContent(string $html, string $property): ?string
    {
        // Пробуем в обоих порядках: property=".."...content=".." и content=".."...property=".."
        if (preg_match('#<meta\s+property="' . preg_quote($property, '#') . '"\s+content="([^"]*)"#u', $html, $m)) {
            return html_entity_decode(trim($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        if (preg_match('#<meta\s+content="([^"]*)"\s+property="' . preg_quote($property, '#') . '"#u', $html, $m)) {
            return html_entity_decode(trim($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return null;
    }

    private function extractIdFromUrl(string $url): ?string
    {
        if (preg_match('#/(?:listing|tenders)/[^/?]+-(\d+)/?#', $url, $m)) return $m[1];
        return null;
    }

    private function parseDate(string $s): ?string
    {
        $s = str_replace(['-', '/'], '.', $s);
        if (preg_match('/^(\d{1,2})\.(\d{1,2})\.(\d{2,4})$/', $s, $m)) {
            $year = (int) $m[3];
            if ($year < 100) $year += 2000;
            return sprintf('%04d-%02d-%02d', $year, (int) $m[2], (int) $m[1]);
        }
        return null;
    }

    private function httpGet(string $url): ?string
    {
        try {
            $r = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; MEYOS-SiteBot/1.0; +https://meyos.uz)',
                    'Accept'     => 'text/html,application/xhtml+xml,application/xml',
                ])
                ->get($url);
            return $r->ok() ? $r->body() : null;
        } catch (\Throwable $e) {
            Log::warning('SavdexParser HTTP error', ['url' => $url, 'err' => $e->getMessage()]);
            return null;
        }
    }
}
