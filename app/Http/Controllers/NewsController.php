<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Setting;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $q        = trim((string) $request->query('q', ''));

        $query = News::published()->orderByDesc('published_at');

        if ($category && array_key_exists($category, News::CATEGORIES)) {
            $query->where('category', $category);
        }

        // Поиск: case-insensitive через LOWER() (SQLite не умеет UTF-8 LIKE),
        // по каждому слову отдельно (все слова должны быть найдены хотя бы в одном поле)
        if ($q !== '') {
            // Разбиваем на слова, чистим пунктуацию, минимум 2 символа
            $words = array_filter(
                preg_split('/\s+/u', mb_strtolower($q)),
                fn ($w) => mb_strlen(trim($w, " .,!?;:\"'()[]{}")) >= 2
            );
            $words = array_map(fn ($w) => trim($w, " .,!?;:\"'()[]{}"), $words);

            foreach ($words as $word) {
                $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $word) . '%';
                $query->where(function ($w) use ($like) {
                    $w->whereRaw('LOWER(title) LIKE ?', [$like])
                      ->orWhereRaw('LOWER(preview) LIKE ?', [$like])
                      ->orWhereRaw('LOWER(content) LIKE ?', [$like]);
                });
            }
        }

        $featured = $q === '' && !$category
            ? (clone $query)->featured()->first()
            : null;

        $newsList = $query->when($featured, fn ($x) => $x->whereKeyNot($featured->id))
            ->paginate(9)
            ->withQueryString();

        return view('pages.news', [
            'featured'   => $featured,
            'news'       => $newsList,
            'category'   => $category,
            'q'          => $q,
            'categories' => News::CATEGORIES,
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
