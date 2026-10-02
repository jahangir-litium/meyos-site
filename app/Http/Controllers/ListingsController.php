<?php

namespace App\Http\Controllers;

use App\Models\SavdexListing;
use App\Models\Setting;
use Illuminate\Http\Request;

class ListingsController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $q    = trim((string) $request->query('q', ''));

        $query = SavdexListing::visible()->fresh()->orderByDesc('published_at')->orderByDesc('fetched_at');

        if ($type && array_key_exists($type, SavdexListing::TYPES)) {
            $query->where('listing_type', $type);
        }
        if ($q !== '') {
            $like = '%' . mb_strtolower($q) . '%';
            $query->where(function ($w) use ($like) {
                $w->whereRaw('LOWER(title) LIKE ?', [$like])
                  ->orWhereRaw('LOWER(summary) LIKE ?', [$like])
                  ->orWhereRaw('LOWER(city) LIKE ?', [$like]);
            });
        }

        $items = $query->paginate(12)->withQueryString();

        return view('pages.listings', [
            'items'    => $items,
            'type'     => $type,
            'q'        => $q,
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
