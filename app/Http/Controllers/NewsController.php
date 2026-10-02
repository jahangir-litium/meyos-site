<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Setting;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        // Клиентская фильтрация: показываем все опубликованные новости (их несколько десятков).
        // Параметры URL (category, q) читает JS из window.location.search для deep-link.
        // Если новостей станет >200 — заменить на серверную пагинацию с AJAX-свапом.
        $query = News::published()->orderByDesc('published_at');

        // Featured — только когда нет никаких query-параметров (чистый вход)
        $hasQuery = $request->filled('category') || $request->filled('q');
        $featured = !$hasQuery ? (clone $query)->featured()->first() : null;

        $newsList = $query->when($featured, fn ($x) => $x->whereKeyNot($featured->id))
            ->limit(300)
            ->get();

        return view('pages.news', [
            'featured'   => $featured,
            'news'       => $newsList,
            'category'   => $request->query('category'),
            'q'          => trim((string) $request->query('q', '')),
            'categories' => News::allCategories(),
            'settings'   => $this->settings(),
        ]);
    }

    public function show(string $slug)
    {
        $news = News::published()->where('slug', $slug)->firstOrFail();

        // «Ещё в категории X» — 3 свежих из той же категории; добиваем общими новостями если не хватает
        $related = News::published()
            ->where('id', '!=', $news->id)
            ->where('category', $news->category)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        if ($related->count() < 3) {
            $needed = 3 - $related->count();
            $more = News::published()
                ->where('id', '!=', $news->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->orderByDesc('published_at')
                ->take($needed)
                ->get();
            $related = $related->concat($more);
        }

        return view('pages.news-show', [
            'news'     => $news,
            'related'  => $related,
            'settings' => $this->settings(),
        ]);
    }

    private function settings(): array
    {
        return [
            'phone'   => Setting::get('phone'),
            'email'   => Setting::get('email'),
            'address' => Setting::get('address'),
        ];
    }
}
