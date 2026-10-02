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
Route::get('/sitemap-partners.xml', [SitemapController::class, 'partners']);

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
Route::get('/partners',            [PageController::class, 'partners'])->name('partners');
Route::get('/partners/{partner:slug}', [PageController::class, 'partnerShow'])
    ->middleware('track.partner')
    ->name('partners.show');
Route::get('/contacts',   [PageController::class, 'contacts'])->name('contacts');

/* ============ Новости ============ */
Route::get('/news',          [NewsController::class, 'index'])->name('news');
Route::get('/news/{slug}',   [NewsController::class, 'show'])->name('news.show');

Route::get('/legislation',          [\App\Http\Controllers\LegislationController::class, 'index'])->name('legislation');
Route::get('/legislation/{slug}',   [\App\Http\Controllers\LegislationController::class, 'show'])->name('legislation.show');

Route::get('/listings',             [\App\Http\Controllers\ListingsController::class, 'index'])->name('listings');

/* ============ Мероприятия ============ */
Route::get('/events',          [EventsController::class, 'index'])->name('events');
Route::get('/events/{slug}',   [EventsController::class, 'show'])->name('events.show');

/* ============ Формы ============
 * throttle:N,60 — N отправок в час на IP, отдельный счётчик для каждой формы
 * (пользователь, отправивший membership, может задать вопрос через fab-ask).
 */
Route::middleware('throttle:3,60')
    ->post('/submit/membership', [SubmissionController::class, 'membership'])->name('submit.membership');

Route::middleware('throttle:3,60')
    ->post('/submit/event/{slug?}', [SubmissionController::class, 'eventRegister'])->name('submit.event');

Route::middleware('throttle:5,60')
    ->post('/submit/contact', [SubmissionController::class, 'contact'])->name('submit.contact');

/* ============ Голосования (Poll) ============ */
Route::middleware('throttle:10,60')->post('/polls/{poll:slug}/vote', [\App\Http\Controllers\PollController::class, 'vote'])->name('polls.vote');
