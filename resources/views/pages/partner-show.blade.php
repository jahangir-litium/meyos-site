@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);

    $name        = $tr($partner, 'name');
    $description = $tr($partner, 'description');
    $about       = $tr($partner, 'about');
    $categoryLabel = \App\Models\Partner::allCategories()[$partner->category] ?? $partner->category;
    $regionLabel   = $partner->region ? (\App\Models\Partner::REGIONS[$partner->region] ?? null) : null;

    // SEO: сначала per-record поля, потом fallback на name/description
    $siteName    = \App\Models\Setting::get('site_name', 'MEYOS');
    $customTitle = $tr($partner, 'seo_title');
    $seoTitle    = $customTitle ?: ($name . ' — ' . $siteName);
    $customDesc  = $tr($partner, 'seo_description');
    $seoDesc     = mb_substr(trim($customDesc ?: ($description ?: 'Партнёр ассоциации мебельщиков Узбекистана')), 0, 200);
    $logoUrl     = $partner->logo_image ? asset('storage/' . $partner->logo_image) : null;
    $ogImage     = $partner->seo_image ? asset('storage/' . $partner->seo_image) : $logoUrl;

    $showViews = ($partner->views_count_total ?? 0) >= 100;
    $gallery   = collect($partner->gallery_images ?? [])->filter()->values();
    $socials   = $partner->socials ?? [];

    $breadcrumbLabel = ['ru' => 'Партнёры', 'uz' => 'Hamkorlar', 'en' => 'Partners'][$cur] ?? 'Партнёры';
@endphp

@section('title', $seoTitle)
@section('description', $seoDesc)
@section('og_type', 'profile')
@if($ogImage)@section('og_image', $ogImage)@endif

@push('head')
@php
    $__partnerSchema = array_filter([
        '@context'    => 'https://schema.org',
        '@type'       => 'Organization',
        'name'        => $name,
        'url'         => $partner->website_url ?: url()->current(),
        'logo'        => $logoUrl,
        'image'       => $ogImage,
        'description' => $description,
        'foundingDate' => $partner->founded_year ? (string) $partner->founded_year : null,
        'email'       => $partner->contact_email,
        'telephone'   => $partner->contact_phone,
        'address'     => $regionLabel ? ['@type' => 'PostalAddress', 'addressLocality' => $regionLabel, 'addressCountry' => 'UZ'] : null,
        'sameAs'      => array_values(array_filter([
            !empty($socials['telegram']) ? 'https://t.me/'.ltrim($socials['telegram'], '@') : null,
            !empty($socials['instagram']) ? 'https://instagram.com/'.ltrim($socials['instagram'], '@') : null,
            $socials['facebook'] ?? null,
        ])),
        'memberOf'    => ['@type' => 'Organization', 'name' => 'MEYOS', '@id' => url('/').'#org'],
    ], fn ($v) => $v !== null && $v !== '' && $v !== []);
@endphp
<script type="application/ld+json">
{!! json_encode($__partnerSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

<section style="padding:2rem 1.5rem 1.5rem;">
  <div class="container" style="max-width:1080px;">

    @include('partials.breadcrumbs', ['items' => [
      ['label' => $breadcrumbLabel, 'url' => route('partners')],
      ['label' => $name,             'url' => null],
    ]])

    {{-- ============ HERO компании ============ --}}
    <div class="partner-hero">
      <div class="partner-hero__logo">
        @if($logoUrl)
          <img src="{{ $logoUrl }}" alt="{{ $name }}" loading="eager" decoding="async">
        @else
          <span class="partner-hero__logo-fallback">{{ mb_substr($name, 0, 1) }}</span>
        @endif
      </div>

      <div class="partner-hero__body">
        <div style="display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:.85rem;">
          <span class="chip">{{ $categoryLabel }}</span>
          @if($regionLabel)
            <span class="chip chip--muted">
              <span class="material-symbols-outlined" style="font-size:.9rem; vertical-align:-2px;">location_on</span>
              {{ $regionLabel }}
            </span>
          @endif
          @if($partner->founded_year)
            <span class="chip chip--muted">
              @switch($cur) @case('uz') {{ $partner->founded_year }}-yildan @break @case('en') Since {{ $partner->founded_year }} @break @default с {{ $partner->founded_year }} г. @endswitch
            </span>
          @endif
        </div>

        <h1 style="font-size:clamp(1.75rem, 4vw, 2.75rem); margin:0 0 1rem; line-height:1.15;">{{ $name }}</h1>

        @if($description)
          <p class="lead" style="margin:0 0 1.5rem;">{{ $description }}</p>
        @endif

        <div class="partner-hero__actions">
          @if($partner->website_url)
            <a href="{{ $partner->website_url }}" target="_blank" rel="noopener" class="btn btn-primary">
              @switch($cur) @case('uz') Saytga oʻtish @break @case('en') Visit website @break @default Перейти на сайт @endswitch
              <span class="material-symbols-outlined" style="font-size:1rem; vertical-align:-2px;">open_in_new</span>
            </a>
          @endif
          @if($partner->contact_email)
            <a href="mailto:{{ $partner->contact_email }}" class="btn btn-outline">
              <span class="material-symbols-outlined" style="font-size:1rem; vertical-align:-2px;">mail</span>
              Email
            </a>
          @endif
          @if($partner->contact_phone)
            <a href="tel:{{ preg_replace('/\s+/', '', $partner->contact_phone) }}" class="btn btn-outline">
              <span class="material-symbols-outlined" style="font-size:1rem; vertical-align:-2px;">call</span>
              @switch($cur) @case('uz') Telefon @break @case('en') Call @break @default Позвонить @endswitch
            </a>
          @endif
        </div>

        {{-- Соцсети --}}
        @if(!empty($socials['telegram']) || !empty($socials['instagram']) || !empty($socials['facebook']))
          <div class="partner-hero__socials">
            @if(!empty($socials['telegram']))
              <a href="https://t.me/{{ ltrim($socials['telegram'], '@') }}" target="_blank" rel="noopener" aria-label="Telegram" class="partner-social">
                <span class="material-symbols-outlined">send</span>
              </a>
            @endif
            @if(!empty($socials['instagram']))
              <a href="https://instagram.com/{{ ltrim($socials['instagram'], '@') }}" target="_blank" rel="noopener" aria-label="Instagram" class="partner-social">
                <span class="material-symbols-outlined">photo_camera</span>
              </a>
            @endif
            @if(!empty($socials['facebook']))
              <a href="{{ $socials['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook" class="partner-social">
                <span class="material-symbols-outlined">groups</span>
              </a>
            @endif
          </div>
        @endif
      </div>
    </div>

    {{-- ============ Стат-плитки ============ --}}
    @if($partner->founded_year || $showViews)
      <div class="partner-stats">
        @if($partner->founded_year)
          <div class="partner-stat">
            <div class="partner-stat__value">{{ (int)date('Y') - $partner->founded_year }}</div>
            <div class="partner-stat__label">@switch($cur) @case('uz') yil bozorda @break @case('en') years on market @break @default лет на рынке @endswitch</div>
          </div>
        @endif
        <div class="partner-stat">
          <div class="partner-stat__value">
            @switch($cur) @case('uz') Aʼzo @break @case('en') Member @break @default Резидент @endswitch
          </div>
          <div class="partner-stat__label">MEYOS</div>
        </div>
        @if($showViews)
          <div class="partner-stat">
            <div class="partner-stat__value">
              @if($partner->views_count_total >= 1000)
                {{ number_format($partner->views_count_total / 1000, 1, '.', '') }}K
              @else
                {{ $partner->views_count_total }}
              @endif
            </div>
            <div class="partner-stat__label">@switch($cur) @case('uz') koʻrilgan @break @case('en') views @break @default просмотров @endswitch</div>
          </div>
        @endif
      </div>
    @endif

    {{-- ============ About ============ --}}
    @if($about)
      <section class="partner-section">
        <h2 style="font-size:1.5rem; margin:0 0 1rem;">
          @switch($cur) @case('uz') Kompaniya haqida @break @case('en') About the company @break @default О компании @endswitch
        </h2>
        <div class="prose">{!! \App\Support\SafeHtml::clean($about) !!}</div>
      </section>
    @endif

    {{-- ============ Видео (YouTube / Instagram) ============ --}}
    @if (!empty($partner->video_url) && ($__embed = \App\Support\VideoEmbed::html($partner->video_url)))
      <section class="partner-section">
        <h2 style="font-size:1.5rem; margin:0 0 1rem;">
          @switch($cur) @case('uz') Video @break @case('en') Video @break @default Видео @endswitch
        </h2>
        {!! $__embed !!}
      </section>
    @endif

    {{-- ============ Галерея ============ --}}
    @if($gallery->count())
      <section class="partner-section">
        <h2 style="font-size:1.5rem; margin:0 0 1.25rem;">
          @switch($cur) @case('uz') Galereya @break @case('en') Gallery @break @default Галерея @endswitch
        </h2>
        <div class="partner-gallery">
          @foreach($gallery as $img)
            <a href="{{ asset('storage/' . $img) }}" target="_blank" rel="noopener" class="partner-gallery__item">
              <img src="{{ asset('storage/' . $img) }}" alt="{{ $name }}" loading="lazy" decoding="async">
            </a>
          @endforeach
        </div>
      </section>
    @endif

    {{-- ============ Контакты ============ --}}
    @if($partner->contact_email || $partner->contact_phone)
      <section class="partner-section">
        <h2 style="font-size:1.5rem; margin:0 0 1rem;">
          @switch($cur) @case('uz') Kontaktlar @break @case('en') Contacts @break @default Контакты @endswitch
        </h2>
        <div class="partner-contacts">
          @if($partner->contact_email)
            <a href="mailto:{{ $partner->contact_email }}" class="partner-contact">
              <span class="material-symbols-outlined">mail</span>
              {{ $partner->contact_email }}
            </a>
          @endif
          @if($partner->contact_phone)
            <a href="tel:{{ preg_replace('/\s+/', '', $partner->contact_phone) }}" class="partner-contact">
              <span class="material-symbols-outlined">call</span>
              {{ $partner->contact_phone }}
            </a>
          @endif
        </div>
      </section>
    @endif

    {{-- ============ Похожие партнёры ============ --}}
    @if($related->count())
      <section class="partner-section partner-related">
        <h2 style="font-size:1.5rem; margin:0 0 1.25rem;">
          @switch($cur) @case('uz') Shuningdek qarang @break @case('en') Related partners @break @default Похожие партнёры @endswitch
        </h2>
        <div class="partner-related__grid">
          @foreach($related as $rp)
            <x-partner-card :partner="$rp" />
          @endforeach
        </div>
      </section>
    @endif

    {{-- ============ CTA внизу ============ --}}
    <section class="partner-cta">
      <h2 style="margin:0 0 .5rem; font-size:1.4rem;">@cms('cta.partner_h2', 'Хотите стать резидентом MEYOS?')</h2>
      <p style="margin:0 0 1.25rem; color:rgb(var(--on-surface-mut));">@cms('cta.home_lead', 'Оставьте заявку, менеджер свяжется в течение рабочего дня.')</p>
      <a href="{{ route('residency') }}#join" class="btn btn-primary btn-lg">@cms('cta.home_button', 'Оставить заявку')</a>
    </section>

  </div>
</section>

{{-- ============ Sticky-CTA на мобилке ============ --}}
@if($partner->contact_email || $partner->contact_phone || $partner->website_url)
  <div class="partner-mobile-cta">
    @if($partner->contact_email)
      <a href="mailto:{{ $partner->contact_email }}"><span class="material-symbols-outlined">mail</span></a>
    @endif
    @if($partner->contact_phone)
      <a href="tel:{{ preg_replace('/\s+/', '', $partner->contact_phone) }}"><span class="material-symbols-outlined">call</span></a>
    @endif
    @if($partner->website_url)
      <a href="{{ $partner->website_url }}" target="_blank" rel="noopener" class="is-primary">
        <span class="material-symbols-outlined">open_in_new</span>
        @switch($cur) @case('uz') Sayt @break @case('en') Site @break @default Сайт @endswitch
      </a>
    @endif
  </div>
@endif

@endsection
