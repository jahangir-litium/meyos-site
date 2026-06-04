<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use App\Models\Program;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Главный sitemap-index — указывает на под-карты:
     * pages, news (с изображениями), events, programs.
     */
    public function index()
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $now = now()->toIso8601String();
            $maps = [
                ['loc' => url('/sitemap-pages.xml'),    'lastmod' => $now],
                ['loc' => url('/sitemap-news.xml'),     'lastmod' => $now],
                ['loc' => url('/sitemap-events.xml'),   'lastmod' => $now],
                ['loc' => url('/sitemap-programs.xml'), 'lastmod' => $now],
            ];

            $xml  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
            foreach ($maps as $m) {
                $xml .= "  <sitemap>\n";
                $xml .= '    <loc>'.htmlspecialchars($m['loc']).'</loc>'."\n";
                $xml .= '    <lastmod>'.$m['lastmod'].'</lastmod>'."\n";
                $xml .= "  </sitemap>\n";
            }
            $xml .= '</sitemapindex>';
            return $xml;
        });

        return $this->xmlResponse($xml);
    }

    /** Статичные страницы — с hreflang для трёх локалей. */
    public function pages()
    {
        $xml = Cache::remember('sitemap-pages.xml', 3600, function () {
            $urls = [];
            foreach (['/', '/about', '/residency', '/programs', '/partners', '/contacts', '/news', '/events'] as $path) {
                $absolute = url($path);
                $urls[] = [
                    'loc'     => $absolute,
                    'lastmod' => now()->toIso8601String(),
                    'change'  => $path === '/' ? 'daily' : 'weekly',
                    'prio'    => $path === '/' ? '1.0' : '0.8',
                    'alt'     => [
                        'ru' => $absolute.'?lang=ru',
                        'uz' => $absolute.'?lang=uz',
                        'en' => $absolute.'?lang=en',
                    ],
                ];
            }
            return $this->buildUrlset($urls);
        });
        return $this->xmlResponse($xml);
    }

    /** Новости — с image-sitemap для каждой обложки и галереи. */
    public function news()
    {
        $xml = Cache::remember('sitemap-news.xml', 3600, function () {
            $urls = [];
            News::published()->orderByDesc('published_at')->get()->each(function ($n) use (&$urls) {
                $images = [];
                if ($n->cover_image) {
                    $images[] = [
                        'loc'     => asset('storage/'.$n->cover_image),
                        'caption' => $n->getTranslation('image_alt', 'ru', false) ?: $n->getTranslation('title', 'ru', false),
                        'title'   => $n->getTranslation('title', 'ru', false),
                    ];
                }
                if (is_array($n->gallery_images)) {
                    foreach ($n->gallery_images as $img) {
                        $images[] = ['loc' => asset('storage/'.$img), 'title' => $n->getTranslation('title', 'ru', false)];
                    }
                }
                $urls[] = [
                    'loc'     => route('news.show', $n->slug),
                    'lastmod' => $n->updated_at?->toIso8601String() ?: $n->published_at?->toIso8601String(),
                    'change'  => 'monthly',
                    'prio'    => '0.7',
                    'alt'     => [
                        'ru' => route('news.show', $n->slug).'?lang=ru',
                        'uz' => route('news.show', $n->slug).'?lang=uz',
                        'en' => route('news.show', $n->slug).'?lang=en',
                    ],
                    'images'  => $images,
                ];
            });
            return $this->buildUrlset($urls);
        });
        return $this->xmlResponse($xml);
    }

    public function events()
    {
        $xml = Cache::remember('sitemap-events.xml', 3600, function () {
            $urls = [];
            Event::published()->get()->each(function ($e) use (&$urls) {
                $images = $e->cover_image ? [[
                    'loc'     => asset('storage/'.$e->cover_image),
                    'title'   => $e->getTranslation('title', 'ru', false),
                ]] : [];
                $urls[] = [
                    'loc'     => route('events.show', $e->slug),
                    'lastmod' => $e->updated_at?->toIso8601String(),
                    'change'  => 'weekly',
                    'prio'    => '0.6',
                    'images'  => $images,
                ];
            });
            return $this->buildUrlset($urls);
        });
        return $this->xmlResponse($xml);
    }

    public function programs()
    {
        $xml = Cache::remember('sitemap-programs.xml', 3600, function () {
            $urls = [];
            Program::published()->get()->each(function ($p) use (&$urls) {
                $images = $p->cover_image ? [[
                    'loc'   => asset('storage/'.$p->cover_image),
                    'title' => $p->getTranslation('title', 'ru', false),
                ]] : [];
                $urls[] = [
                    'loc'     => route('programs').'#'.$p->slug,
                    'lastmod' => $p->updated_at?->toIso8601String(),
                    'change'  => 'monthly',
                    'prio'    => '0.6',
                    'images'  => $images,
                ];
            });
            return $this->buildUrlset($urls);
        });
        return $this->xmlResponse($xml);
    }

    /* ============ helpers ============ */

    private function buildUrlset(array $urls): string
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'
              . ' xmlns:xhtml="http://www.w3.org/1999/xhtml"'
              . ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars($u['loc']).'</loc>'."\n";
            if (!empty($u['lastmod'])) $xml .= '    <lastmod>'.$u['lastmod'].'</lastmod>'."\n";
            $xml .= '    <changefreq>'.($u['change'] ?? 'monthly').'</changefreq>'."\n";
            $xml .= '    <priority>'.($u['prio'] ?? '0.5').'</priority>'."\n";

            // hreflang
            if (!empty($u['alt'])) {
                foreach ($u['alt'] as $lang => $href) {
                    $xml .= '    <xhtml:link rel="alternate" hreflang="'.$lang.'" href="'.htmlspecialchars($href).'" />'."\n";
                }
            }
            // image-sitemap
            if (!empty($u['images'])) {
                foreach ($u['images'] as $img) {
                    $xml .= "    <image:image>\n";
                    $xml .= '      <image:loc>'.htmlspecialchars($img['loc']).'</image:loc>'."\n";
                    if (!empty($img['caption'])) $xml .= '      <image:caption>'.htmlspecialchars($img['caption']).'</image:caption>'."\n";
                    if (!empty($img['title']))   $xml .= '      <image:title>'.htmlspecialchars($img['title']).'</image:title>'."\n";
                    $xml .= "    </image:image>\n";
                }
            }
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';
        return $xml;
    }

    private function xmlResponse(string $xml)
    {
        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
