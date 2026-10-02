<?php

namespace App\Http\Controllers;

use App\Models\LegalAct;
use App\Models\Setting;
use Illuminate\Http\Request;

class LegislationController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $status   = $request->query('status');
        $q        = trim((string) $request->query('q', ''));

        $query = LegalAct::published()->orderBy('sort')->orderByDesc('act_date');

        if ($category && array_key_exists($category, LegalAct::CATEGORIES)) {
            $query->where('category', $category);
        }
        if ($status && array_key_exists($status, LegalAct::STATUSES)) {
            $query->where('status', $status);
        }
        if ($q !== '') {
            $words = array_filter(
                preg_split('/\s+/u', mb_strtolower($q)),
                fn ($w) => mb_strlen(trim($w, " .,!?;:\"'()[]{}")) >= 2
            );
            foreach ($words as $word) {
                $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $word) . '%';
                $query->where(function ($w) use ($like) {
                    $w->whereRaw('LOWER(act_number) LIKE ?', [$like])
                      ->orWhereRaw('LOWER(title) LIKE ?', [$like])
                      ->orWhereRaw('LOWER(summary) LIKE ?', [$like])
                      ->orWhereRaw('LOWER(content) LIKE ?', [$like]);
                });
            }
        }

        $acts = $query->paginate(12)->withQueryString();

        return view('pages.legislation', [
            'acts'       => $acts,
            'category'   => $category,
            'status'     => $status,
            'q'          => $q,
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
