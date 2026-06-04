<!DOCTYPE html>
<html lang="{{ $locale ?? app()->getLocale() }}" data-color="wood" data-concept="flow">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@php
    $pageObj = $page ?? null;
    $siteName  = \App\Models\Setting::get('site_name', 'MEYOS');
    $logoUrl   = \App\Models\Setting::logoUrl();
    $faviconUrl = \App\Models\Setting::faviconUrl();
    $pageTitle = trim(View::yieldContent('title')) ?: ($pageObj?->getTranslation('seo_title', app()->getLocale(), false) ?: "$siteName — Ассоциация мебельщиков Узбекистана");
    $pageDesc  = trim(View::yieldContent('description')) ?: ($pageObj?->getTranslation('seo_description', app()->getLocale(), false) ?: 'MEYOS — ассоциация мебельщиков Узбекистана');
    $pageOgImg = trim(View::yieldContent('og_image')) ?: ($logoUrl ?: '');
    $canonical = trim(View::yieldContent('canonical')) ?: url()->current();
    // На проде подгружаем минифицированные версии, если они есть
    $isProd = app()->environment('production');
    $cssPath = $isProd && file_exists(public_path('assets/css/themes.min.css')) ? 'assets/css/themes.min.css' : 'assets/css/themes.css';
    $jsPath  = $isProd && file_exists(public_path('assets/js/main.min.js')) ? 'assets/js/main.min.js' : 'assets/js/main.js';
@endphp
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDesc }}" />
<link rel="canonical" href="{{ $canonical }}" />

{{-- hreflang для трёх локалей --}}
@php
    $currentUrl = url()->current();
    // Базовый URL без query — добавляем lang=
    $baseUrl = strtok($currentUrl, '?');
@endphp
<link rel="alternate" hreflang="ru" href="{{ $baseUrl }}?lang=ru" />
<link rel="alternate" hreflang="uz" href="{{ $baseUrl }}?lang=uz" />
<link rel="alternate" hreflang="en" href="{{ $baseUrl }}?lang=en" />
<link rel="alternate" hreflang="x-default" href="{{ $baseUrl }}" />

{{-- Open Graph для соцсетей --}}
<meta property="og:type"        content="@yield('og_type', 'website')" />
<meta property="og:site_name"   content="{{ $siteName }}" />
<meta property="og:title"       content="{{ $pageTitle }}" />
<meta property="og:description" content="{{ $pageDesc }}" />
<meta property="og:url"         content="{{ $canonical }}" />
<meta property="og:locale"      content="{{ ['ru'=>'ru_RU','uz'=>'uz_UZ','en'=>'en_US'][app()->getLocale()] ?? 'ru_RU' }}" />
@if ($pageOgImg)<meta property="og:image" content="{{ $pageOgImg }}" />@endif

{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary_large_image" />
<meta name="twitter:title"       content="{{ $pageTitle }}" />
<meta name="twitter:description" content="{{ $pageDesc }}" />
@if ($pageOgImg)<meta name="twitter:image" content="{{ $pageOgImg }}" />@endif

{{-- Organization + WebSite на каждой странице — даёт Google панель организации и поиск по сайту --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type' => 'Organization',
            '@id'   => url('/').'#org',
            'name'  => $siteName,
            'url'   => url('/'),
            'logo'  => $logoUrl ?: null,
            'description' => $pageDesc,
            'sameAs' => array_values(array_filter([
                \App\Models\Setting::get('telegram_url'),
                \App\Models\Setting::get('whatsapp_url'),
            ])),
            'contactPoint' => [
                '@type'       => 'ContactPoint',
                'contactType' => 'customer service',
                'email'       => \App\Models\Setting::get('email'),
                'telephone'   => \App\Models\Setting::get('phone'),
                'availableLanguage' => ['ru', 'uz', 'en'],
            ],
        ],
        [
            '@type' => 'WebSite',
            '@id'   => url('/').'#website',
            'url'   => url('/'),
            'name'  => $siteName,
            'publisher' => ['@id' => url('/').'#org'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url('/news').'?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
            'inLanguage' => array_values(\App\Http\Middleware\SetLocale::SUPPORTED),
        ],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

@if ($faviconUrl)<link rel="icon" href="{{ $faviconUrl }}" />@endif

<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400;1,600;1,700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@400,0..1&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset($cssPath) }}" />
<style>
  .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 500; }
  .flash-success { position: fixed; top: 5rem; right: 1.5rem; padding: 1rem 1.5rem; background: rgb(var(--primary)); color: #fff; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); z-index: 200; animation: slide-in .4s ease-out; }
  @keyframes slide-in { from { transform: translateX(120%); } to { transform: translateX(0); } }

  /* ============ Skeleton loader для карточек ============ */
  .skeleton { background: linear-gradient(90deg, rgba(0,0,0,.04) 25%, rgba(0,0,0,.08) 50%, rgba(0,0,0,.04) 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: var(--radius-md); }
  @keyframes shimmer { 0%{background-position:200% 0;} 100%{background-position:-200% 0;} }
  img.lazy-img { background: rgba(0,0,0,.04); transition: opacity .3s; }
  img.lazy-img[loading="lazy"]:not([src]) { opacity: 0; }

  /* ============ Floating Ask button ============ */
  .fab-ask {
    position: fixed; right: 1.5rem; bottom: 1.5rem; z-index: 150;
    width: 3.5rem; height: 3.5rem; border-radius: 50%; border: 0;
    background: rgb(var(--primary)); color: #fff; cursor: pointer;
    box-shadow: 0 10px 30px rgba(0,0,0,.25);
    display: flex; align-items: center; justify-content: center;
    transition: transform .15s, box-shadow .15s;
  }
  .fab-ask:hover { transform: translateY(-2px); box-shadow: 0 14px 36px rgba(0,0,0,.32); }
  .fab-ask .material-symbols-outlined { font-size: 1.7rem; }
  .fab-modal { position: fixed; inset: 0; background: rgba(0,0,0,.55); z-index: 200; display: none; align-items: center; justify-content: center; padding: 1rem; }
  .fab-modal.is-open { display: flex; }
  .fab-modal__panel { background: rgb(var(--surface)); border-radius: var(--radius-lg); padding: 2rem; width: 100%; max-width: 460px; box-shadow: var(--shadow-lg); }
  .fab-modal__close { position: absolute; top: 1rem; right: 1rem; background: transparent; border: 0; cursor: pointer; color: rgb(var(--on-surface-mut)); }
  @media (max-width: 720px) { .fab-ask { right: 1rem; bottom: 1rem; } }
</style>
@stack('head')
</head>
<body>

@include('partials.header')
@include('partials.mobile-menu')

<main>
    @include('partials.flash')

    @yield('content')
</main>

@include('partials.footer')
@include('partials.fab-ask')

<script src="{{ asset($jsPath) }}" defer></script>
<script>
  // ============ Ленивая подгрузка изображений (fallback для старых браузеров) ============
  if ('loading' in HTMLImageElement.prototype === false) {
    document.querySelectorAll('img[loading="lazy"]').forEach(img => {
      const src = img.dataset.src;
      if (src) img.src = src;
    });
  }
</script>
@stack('scripts')
</body>
</html>
