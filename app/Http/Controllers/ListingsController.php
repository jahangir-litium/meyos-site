<?php

namespace App\Http\Controllers;

use App\Models\SavdexListing;
use App\Models\Setting;
use Illuminate\Http\Request;

class ListingsController extends Controller
{
    public function index(Request $request)
    {
        // Клиентская фильтрация: отдаём все актуальные объявления (их пара сотен),
        // параметры URL (type, q) читает JS из window.location.search для deep-link.
        $items = SavdexListing::visible()
            ->fresh()
            ->orderByDesc('published_at')
            ->orderByDesc('fetched_at')
            ->limit(300)
            ->get();

        return view('pages.listings', [
            'items'    => $items,
            'type'     => $request->query('type'),
            'q'        => trim((string) $request->query('q', '')),
            'types'    => SavdexListing::allTypes(),
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
