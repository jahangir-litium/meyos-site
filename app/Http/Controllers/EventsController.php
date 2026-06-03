<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Setting;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index(Request $request)
    {
        $featured = Event::published()->where('is_featured', true)->upcoming()->first();
        $upcoming = Event::published()
            ->upcoming()
            ->when($featured, fn ($q) => $q->whereKeyNot($featured->id))
            ->orderBy('event_date')
            ->get();
        $past = Event::published()
            ->where('event_date', '<', now()->toDateString())
            ->orderByDesc('event_date')
            ->take(5)
            ->get();

        return view('pages.events', [
            'featured' => $featured,
            'upcoming' => $upcoming,
            'past'     => $past,
            'settings' => $this->settings(),
        ]);
    }

    public function show(string $slug)
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();
        return view('pages.events-show', [
            'event'    => $event,
            'settings' => $this->settings(),
        ]);
    }

    private function settings(): array
    {
        return [
            'phone' => Setting::get('phone'),
            'email' => Setting::get('email'),
            'address' => Setting::get('address'),
        ];
    }
}
