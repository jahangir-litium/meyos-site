<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\BusinessCase;
use App\Models\Certification;
use App\Models\Document;
use App\Models\Event;
use App\Models\Faq;
use App\Models\JoinStep;
use App\Models\News;
use App\Models\PainSolutionRow;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Setting;
use App\Models\TaxRow;
use App\Models\TeamMember;
use App\Models\TimelineItem;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    /** 5-минутный кэш, инвалидируется по тегу 'pages' при save() моделей (см. trait HasPageCache) */
    private const CACHE_TTL = 300;

    /** Версия кэша. Меняйте при правках моделей чтобы инвалидировать старый cache. */
    private const CACHE_VERSION = 'v2';

    private function key(string $page): string
    {
        return 'page:' . self::CACHE_VERSION . ':' . $page . ':' . app()->getLocale();
    }

    public function home()
    {
        $data = $this->safeRemember($this->key('home'), fn () => [
            'page'      => Page::where('slug', 'home')->first(),
            'benefits'  => Benefit::published()->ordered()->get(),
            'painSols'  => PainSolutionRow::published()->ordered()->get(),
            'cases'     => BusinessCase::published()->ordered()->get(),
            'programs'  => Program::published()->ordered()->get(),
            'taxRows'   => TaxRow::published()->ordered()->get(),
            'steps'     => JoinStep::published()->ordered()->get(),
            'partners'  => Partner::published()->onHome()->ordered()->take(12)->get(),
            'events'    => Event::published()->upcoming()->ordered()->take(3)->get(),
            'news'      => News::published()->orderByDesc('published_at')->take(3)->get(),
            'faqs'      => Faq::published()->forPageSlug('home')->ordered()->get(),
        ]);

        return view('pages.home', $data + ['settings' => $this->settings()]);
    }

    public function about()
    {
        $data = $this->safeRemember($this->key('about'), fn () => [
            'page'           => Page::where('slug', 'about')->first(),
            'timeline'       => TimelineItem::published()->ordered()->get(),
            'team'           => TeamMember::published()->ordered()->get(),
            'certifications' => Certification::published()->ordered()->get(),
        ]);

        return view('pages.about', $data + ['settings' => $this->settings()]);
    }

    public function residency()
    {
        $data = $this->safeRemember($this->key('residency'), fn () => [
            'page'      => Page::where('slug', 'residency')->first(),
            'taxRows'   => TaxRow::published()->ordered()->get(),
            'benefits'  => Benefit::published()->ordered()->get(),
            'steps'     => JoinStep::published()->ordered()->get(),
            'documents' => Document::query()
                ->where('is_published', true)
                ->orderBy('sort')
                ->get(),
        ]);

        return view('pages.residency', $data + ['settings' => $this->settings()]);
    }

    public function programs()
    {
        $data = $this->safeRemember($this->key('programs'), fn () => [
            'page'     => Page::where('slug', 'programs')->first(),
            'programs' => Program::published()
                ->with(['blocks' => fn ($q) => $q->orderBy('sort'), 'advantages' => fn ($q) => $q->orderBy('sort')])
                ->orderBy('is_flagship', 'desc')
                ->ordered()
                ->get(),
        ]);

        return view('pages.programs', $data + ['settings' => $this->settings()]);
    }

    public function partners()
    {
        $data = $this->safeRemember($this->key('partners'), fn () => [
            'page'     => Page::where('slug', 'partners')->first(),
            'partners' => Partner::published()->ordered()->get(),
        ]);

        return view('pages.partners', $data + ['settings' => $this->settings()]);
    }

    public function contacts()
    {
        return view('pages.contacts', [
            'page'     => Page::where('slug', 'contacts')->first(),
            'settings' => $this->settings(),
        ]);
    }

    /**
     * Cache::remember с автовосстановлением: если сериализованный объект сломался
     * (incomplete-object после правок моделей), мы forget'аем ключ и пересчитываем.
     */
    private function safeRemember(string $key, \Closure $callback): array
    {
        try {
            $cached = Cache::get($key);
            if ($cached !== null) {
                // Триггерим обращение к одному полю чтобы убедиться что объект целый
                foreach ($cached as $val) {
                    if (is_object($val) && method_exists($val, 'getKey')) $val->getKey();
                }
                return $cached;
            }
        } catch (\Throwable $e) {
            Cache::forget($key);
            report($e);
        }

        $fresh = $callback();
        try {
            Cache::put($key, $fresh, self::CACHE_TTL);
        } catch (\Throwable $e) {
            report($e); // если сериализация падает — отдаём без кэша
        }
        return $fresh;
    }

    private function settings(): array
    {
        return [
            'phone'      => Setting::get('phone'),
            'email'      => Setting::get('email'),
            'address'    => Setting::get('address'),
            'hours'      => Setting::get('hours'),
            'requisites' => Setting::get('requisites'),
            'entity'     => Setting::get('entity_name'),
            'stats'      => Setting::get('stats', []),
        ];
    }
}
