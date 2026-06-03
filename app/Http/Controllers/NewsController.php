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

        $query = News::published()->orderByDesc('published_at');
        if ($category && array_key_exists($category, News::CATEGORIES)) {
            $query->where('category', $category);
        }

        $featured = (clone $query)->featured()->first();
        $newsList = $query->when($featured, fn ($q) => $q->whereKeyNot($featured->id))->paginate(9);

        return view('pages.news', [
            'featured'   => $featured,
            'news'       => $newsList,
            'category'   => $category,
            'categories' => News::CATEGORIES,
            'settings'   => $this->settings(),
        ]);
    }

    public function show(string $slug)
    {
        $news = News::published()->where('slug', $slug)->firstOrFail();
        $other = News::published()->where('id', '!=', $news->id)->orderByDesc('published_at')->take(3)->get();

        return view('pages.news-show', [
            'news'     => $news,
            'other'    => $other,
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
