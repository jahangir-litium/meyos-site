<?php

use App\Http\Controllers\EventsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

/* ============ SEO ============ */
Route::get('/sitemap.xml',          [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-pages.xml',    [SitemapController::class, 'pages']);
Route::get('/sitemap-news.xml',     [SitemapController::class, 'news']);
Route::get('/sitemap-events.xml',   [SitemapController::class, 'events']);
Route::get('/sitemap-programs.xml', [SitemapController::class, 'programs']);

/* llms.txt — стандарт для AI-краулеров (ChatGPT, Claude, Perplexity) */
Route::get('/llms.txt', function () {
    $content = view('seo.llms-txt')->render();
    return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('llms');
Route::get('/robots.txt', function () {
    $path = public_path('robots.txt');
    if (file_exists($path)) {
        return response(file_get_contents($path), 200, ['Content-Type' => 'text/plain']);
    }
    return response("User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: ".url('/sitemap.xml'), 200, ['Content-Type' => 'text/plain']);
})->name('robots');

/* ============ Главная и статичные страницы ============ */
Route::get('/',           [PageController::class, 'home'])->name('home');
Route::get('/about',      [PageController::class, 'about'])->name('about');
Route::get('/residency',  [PageController::class, 'residency'])->name('residency');
Route::get('/programs',   [PageController::class, 'programs'])->name('programs');
Route::get('/partners',   [PageController::class, 'partners'])->name('partners');
Route::get('/contacts',   [PageController::class, 'contacts'])->name('contacts');

/* ============ Новости ============ */
Route::get('/news',          [NewsController::class, 'index'])->name('news');
Route::get('/news/{slug}',   [NewsController::class, 'show'])->name('news.show');

/* ============ Мероприятия ============ */
Route::get('/events',          [EventsController::class, 'index'])->name('events');
Route::get('/events/{slug}',   [EventsController::class, 'show'])->name('events.show');

/* ============ Формы ============ */
Route::post('/submit/membership',     [SubmissionController::class, 'membership'])->name('submit.membership');
Route::post('/submit/event/{slug?}',  [SubmissionController::class, 'eventRegister'])->name('submit.event');
Route::post('/submit/contact',        [SubmissionController::class, 'contact'])->name('submit.contact');
