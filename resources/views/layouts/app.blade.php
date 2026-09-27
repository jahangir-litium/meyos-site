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
@php
    // Собираем массив в PHP-блоке чтобы обойти конфликт Blade-директивы @context (Laravel 11)
    $__schemaOrg = [
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
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($__schemaOrg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

@if ($faviconUrl)<link rel="icon" href="{{ $faviconUrl }}" />@endif

{{-- Верификация поисковиков --}}
@php
    $yaVerify = \App\Models\Setting::get('yandex_verification');
    $googleVerify = \App\Models\Setting::get('google_site_verification');
    $metrikaId = \App\Models\Setting::get('yandex_metrika_id');
    $gaId = \App\Models\Setting::get('google_analytics_id');
@endphp
@if($yaVerify)<meta name="yandex-verification" content="{{ $yaVerify }}" />@endif
@if($googleVerify)<meta name="google-site-verification" content="{{ $googleVerify }}" />@endif

{{-- DNS-prefetch + preconnect для шрифтов — ускоряет first paint --}}
<link rel="dns-prefetch" href="https://fonts.googleapis.com">
<link rel="dns-prefetch" href="https://fonts.gstatic.com">
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

{{-- Preload главного CSS — браузер начнёт скачивать пока парсит HTML --}}
<link rel="preload" href="{{ asset($cssPath) }}" as="style">
@if($logoUrl)<link rel="preload" as="image" href="{{ $logoUrl }}" fetchpriority="high">@endif

{{-- Шрифты: Manrope (заголовки), Inter (текст). Оба через одну загрузку.
     display:swap — текст сразу системным, потом подменяется. --}}
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;600&display=swap" rel="stylesheet" media="print" onload="this.media='all'" />
<noscript><link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;600&display=swap" rel="stylesheet" /></noscript>
{{-- Material Symbols — только нужные иконки через API-ограничение --}}
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0..1,-25..200&display=swap" rel="stylesheet" media="print" onload="this.media='all'" />
<noscript><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap" rel="stylesheet" /></noscript>
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

{{-- A11y: skip-link — первый focusable, для клавиатурников и screen-readers --}}
<a href="#main-content" class="skip-link">
  @switch(app()->getLocale()) @case('uz') Asosiy tarkibga oʻtish @break @case('en') Skip to content @break @default Перейти к основному содержимому @endswitch
</a>

@include('partials.header')
@include('partials.mobile-menu')

<main id="main-content" tabindex="-1">
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

  // ============ Микро-анимации (отключаются для prefers-reduced-motion) ============
  const noMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;

  // 1) Появление секций при скролле
  if (!noMotion && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('is-visible'); observer.unobserve(e.target); }
      });
    }, { rootMargin: '0px 0px 50px 0px', threshold: 0 });
    const vh = window.innerHeight;
    document.querySelectorAll('section .section-head, .card, .timeline__item, .step, .partner-card, .partner-section').forEach(el => {
      el.classList.add('fade-in-up');
      // Первый экран (+ буфер): сразу visible, чтобы не мигало полу-прозрачным
      const top = el.getBoundingClientRect().top;
      if (top < vh + 100) {
        requestAnimationFrame(() => el.classList.add('is-visible'));
      } else {
        observer.observe(el);
      }
    });
  }

  // 2) Number-counter в hero — крутит 0 → значение при первом появлении
  if (!noMotion && 'IntersectionObserver' in window) {
    const counterObs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el = e.target;
        const raw = el.dataset.value || el.textContent;
        // Извлекаем число + суффикс ('500+', '38%', '12', '$14.6M')
        const match = raw.match(/(-?[\d.,]+)/);
        if (!match) return;
        const numStr = match[1].replace(/\s/g, '').replace(',', '.');
        const num = parseFloat(numStr);
        if (isNaN(num)) return;
        const decimals = (numStr.split('.')[1] || '').length;
        const prefix = raw.substring(0, match.index);
        const suffix = raw.substring(match.index + match[1].length);

        const duration = 1400, start = performance.now();
        const animate = (t) => {
          const p = Math.min((t - start) / duration, 1);
          const eased = 1 - Math.pow(1 - p, 3); // ease-out cubic
          const v = (num * eased).toFixed(decimals);
          el.textContent = prefix + v + suffix;
          if (p < 1) requestAnimationFrame(animate);
        };
        requestAnimationFrame(animate);
        counterObs.unobserve(el);
      });
    }, { threshold: 0.5 });
    document.querySelectorAll('[data-counter]').forEach(el => counterObs.observe(el));
  }

  // 3) AJAX-отправка форм с toast (перехват .form, POST) + fallback на классический сабмит
  (function () {
    // Контейнер toast'ов
    let stack = document.querySelector('.toast-stack');
    if (!stack) {
      stack = document.createElement('div');
      stack.className = 'toast-stack';
      document.body.appendChild(stack);
    }

    const showToast = (type, message) => {
      const t = document.createElement('div');
      t.className = 'toast toast--' + type;
      t.textContent = message;
      stack.appendChild(t);
      setTimeout(() => { t.classList.add('is-leaving'); setTimeout(() => t.remove(), 250); }, 5000);
    };

    document.querySelectorAll('form.form').forEach(form => {
      if (form.dataset.noAjax !== undefined) return;
      if ((form.method || 'get').toLowerCase() !== 'post') return;

      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        btn?.classList.add('is-loading');

        try {
          const res = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
          });

          let data = null;
          try { data = await res.json(); } catch (_) {}

          if (res.ok) {
            showToast('success', data?.message || 'Готово');
            form.reset();
            // Закрыть модалку fab-ask если это форма из неё
            document.getElementById('fab-modal')?.classList.remove('is-open');
          } else if (res.status === 429) {
            showToast('error', 'Слишком много попыток. Подождите и попробуйте позже.');
          } else if (res.status === 422 && data?.errors) {
            const firstErr = Object.values(data.errors)[0]?.[0] || 'Проверьте поля';
            showToast('error', firstErr);
          } else {
            showToast('error', data?.message || 'Что-то пошло не так. Попробуйте ещё раз.');
          }
        } catch (err) {
          showToast('error', 'Ошибка сети. Проверьте соединение.');
        } finally {
          btn?.classList.remove('is-loading');
        }
      });
    });
  })();

  // 4) Магнитный hover для CTA — кнопка тянется к курсору (только desktop, не touch)
  if (!noMotion && matchMedia('(hover: hover) and (pointer: fine)').matches) {
    document.querySelectorAll('.btn-primary, .btn-white, .btn-lg').forEach(btn => {
      btn.addEventListener('mousemove', (e) => {
        const r = btn.getBoundingClientRect();
        const x = e.clientX - r.left - r.width / 2;
        const y = e.clientY - r.top - r.height / 2;
        btn.style.transform = `translate(${x * 0.15}px, ${y * 0.15}px)`;
      });
      btn.addEventListener('mouseleave', () => { btn.style.transform = ''; });
    });
  }
</script>
@stack('scripts')

{{-- Yandex Metrika --}}
@if($metrikaId ?? false)
<script>
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();
   for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
   k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
   ym({{ (int) $metrikaId }}, "init", {clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/{{ (int) $metrikaId }}" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
@endif

{{-- Google Analytics 4 --}}
@if($gaId ?? false)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $gaId }}', {anonymize_ip: true});
</script>
@endif
</body>
</html>
