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

    public function home()
    {
        $data = Cache::remember('page:home:'.app()->getLocale(), self::CACHE_TTL, fn () => [
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
            'faqs'      => Faq::published()->forPage('home')->ordered()->get(),
        ]);

        return view('pages.home', $data + ['settings' => $this->settings()]);
    }

    public function about()
    {
        $data = Cache::remember('page:about:'.app()->getLocale(), self::CACHE_TTL, fn () => [
            'page'           => Page::where('slug', 'about')->first(),
            'timeline'       => TimelineItem::published()->ordered()->get(),
            'team'           => TeamMember::published()->ordered()->get(),
            'certifications' => Certification::published()->ordered()->get(),
        ]);

        return view('pages.about', $data + ['settings' => $this->settings()]);
    }

    public function residency()
    {
        $data = Cache::remember('page:residency:'.app()->getLocale(), self::CACHE_TTL, fn () => [
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
        $data = Cache::remember('page:programs:'.app()->getLocale(), self::CACHE_TTL, fn () => [
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
        $data = Cache::remember('page:partners:'.app()->getLocale(), self::CACHE_TTL, fn () => [
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
