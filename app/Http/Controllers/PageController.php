<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\BusinessCase;
use App\Models\Certification;
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

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', [
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
            'settings'  => $this->settings(),
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'page'           => Page::where('slug', 'about')->first(),
            'timeline'       => TimelineItem::published()->ordered()->get(),
            'team'           => TeamMember::published()->ordered()->get(),
            'certifications' => Certification::published()->ordered()->get(),
            'settings'       => $this->settings(),
        ]);
    }

    public function residency()
    {
        return view('pages.residency', [
            'page'     => Page::where('slug', 'residency')->first(),
            'taxRows'  => TaxRow::published()->ordered()->get(),
            'benefits' => Benefit::published()->ordered()->get(),
            'steps'    => JoinStep::published()->ordered()->get(),
            'settings' => $this->settings(),
        ]);
    }

    public function programs()
    {
        return view('pages.programs', [
            'page'     => Page::where('slug', 'programs')->first(),
            'programs' => Program::published()
                ->with(['blocks' => fn ($q) => $q->orderBy('sort'), 'advantages' => fn ($q) => $q->orderBy('sort')])
                ->orderBy('is_flagship', 'desc')
                ->ordered()
                ->get(),
            'settings' => $this->settings(),
        ]);
    }

    public function partners()
    {
        return view('pages.partners', [
            'page'     => Page::where('slug', 'partners')->first(),
            'partners' => Partner::published()->ordered()->get(),
            'settings' => $this->settings(),
        ]);
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
