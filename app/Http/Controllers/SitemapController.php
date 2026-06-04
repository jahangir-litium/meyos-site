<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use App\Models\Program;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $urls = [];

            // ============ Статичные страницы ============
            foreach (['/', '/about', '/residency', '/programs', '/partners', '/contacts', '/news', '/events'] as $path) {
                $urls[] = [
                    'loc'     => url($path),
                    'lastmod' => now()->toIso8601String(),
                    'change'  => $path === '/' ? 'daily' : 'weekly',
                    'prio'    => $path === '/' ? '1.0' : '0.8',
                ];
            }

            // ============ Новости ============
            News::published()->orderByDesc('published_at')->get(['slug', 'updated_at', 'published_at'])
                ->each(function ($n) use (&$urls) {
                    $urls[] = [
                        'loc'     => route('news.show', $n->slug),
                        'lastmod' => $n->updated_at?->toIso8601String() ?: $n->published_at?->toIso8601String(),
                        'change'  => 'monthly',
                        'prio'    => '0.7',
                    ];
                });

            // ============ Программы ============
            Program::published()->get(['slug', 'updated_at'])
                ->each(function ($p) use (&$urls) {
                    $urls[] = [
                        'loc'     => route('programs') . '#' . $p->slug,
                        'lastmod' => $p->updated_at?->toIso8601String(),
                        'change'  => 'monthly',
                        'prio'    => '0.6',
                    ];
                });

            // ============ События ============
            Event::published()->get(['slug', 'updated_at'])
                ->each(function ($e) use (&$urls) {
                    $urls[] = [
                        'loc'     => route('events.show', $e->slug),
                        'lastmod' => $e->updated_at?->toIso8601String(),
                        'change'  => 'weekly',
                        'prio'    => '0.6',
                    ];
                });

            $xml  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
            foreach ($urls as $u) {
                $xml .= "  <url>\n";
                $xml .= '    <loc>'.htmlspecialchars($u['loc']).'</loc>'."\n";
                if ($u['lastmod']) $xml .= '    <lastmod>'.$u['lastmod'].'</lastmod>'."\n";
                $xml .= '    <changefreq>'.$u['change'].'</changefreq>'."\n";
                $xml .= '    <priority>'.$u['prio'].'</priority>'."\n";
                $xml .= "  </url>\n";
            }
            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
