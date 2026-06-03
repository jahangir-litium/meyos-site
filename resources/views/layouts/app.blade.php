<!DOCTYPE html>
<html lang="{{ $locale ?? 'ru' }}" data-color="wood" data-concept="flow">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@php
    $pageObj = $page ?? null;
    $siteName  = \App\Models\Setting::get('site_name', 'MEYOS');
    $logoUrl   = \App\Models\Setting::logoUrl();
    $faviconUrl = \App\Models\Setting::faviconUrl();
@endphp
<title>@yield('title', $pageObj?->getTranslation('seo_title', app()->getLocale(), false) ?: "$siteName — Ассоциация мебельщиков Узбекистана")</title>
<meta name="description" content="@yield('description', $pageObj?->getTranslation('seo_description', app()->getLocale(), false) ?: 'MEYOS — ассоциация мебельщиков Узбекистана')" />
@if ($faviconUrl) <link rel="icon" href="{{ $faviconUrl }}" /> @endif
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400;1,600;1,700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@400,0..1&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/css/themes.css') }}" />
<style>
  .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 500; }
  .flash-success { position: fixed; top: 5rem; right: 1.5rem; padding: 1rem 1.5rem; background: rgb(var(--primary)); color: #fff; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); z-index: 200; animation: slide-in .4s ease-out; }
  @keyframes slide-in { from { transform: translateX(120%); } to { transform: translateX(0); } }
</style>
</head>
<body>

@include('partials.header')
@include('partials.mobile-menu')

<main>
    @if (session('success'))
        <div class="flash-success" onclick="this.remove()">
            <span class="material-symbols-outlined" style="vertical-align: middle;">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @yield('content')
</main>

@include('partials.footer')

<script src="{{ asset('assets/js/main.js') }}"></script>
@stack('scripts')
</body>
</html>
