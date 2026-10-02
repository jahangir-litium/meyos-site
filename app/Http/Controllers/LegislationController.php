<?php

namespace App\Http\Controllers;

use App\Models\LegalAct;
use App\Models\Setting;
use Illuminate\Http\Request;

class LegislationController extends Controller
{
    public function index(Request $request)
    {
        // Клиентская фильтрация: отдаём все опубликованные акты (их обычно десятки, не тысячи).
        // Параметры URL (category, status, q) читает JS на фронте для deep-link.
        $acts = LegalAct::published()
            ->orderBy('sort')
            ->orderByDesc('act_date')
            ->get();

        return view('pages.legislation', [
            'acts'       => $acts,
            'category'   => $request->query('category'),
            'status'     => $request->query('status'),
            'q'          => trim((string) $request->query('q', '')),
            'categories' => LegalAct::allCategories(),
            'statuses'   => LegalAct::allStatuses(),
            'settings'   => $this->settings(),
        ]);
    }

    public function show(string $slug)
    {
        $act = LegalAct::published()->where('slug', $slug)->firstOrFail();

        $related = LegalAct::published()
            ->where('id', '!=', $act->id)
            ->where('category', $act->category)
            ->orderBy('sort')
            ->orderByDesc('act_date')
            ->take(3)
            ->get();

        return view('pages.legislation-show', [
            'act'      => $act,
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
