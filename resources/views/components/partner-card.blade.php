@props(['partner', 'compact' => false])

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
    $name        = $tr($partner, 'name');
    $description = $tr($partner, 'description');
    $categoryLabel = \App\Models\Partner::allCategories()[$partner->category] ?? $partner->category;
    $regionLabel   = $partner->region ? (\App\Models\Partner::REGIONS[$partner->region] ?? null) : null;
    $isPopular   = ($partner->views_count_30d ?? 0) > 0
                   && \App\Models\Partner::popular(3)->pluck('id')->contains($partner->id);
    $url = route('partners.show', $partner->slug);
@endphp

<a href="{{ $url }}"
   data-partner-card
   data-category="{{ $partner->category }}"
   data-region="{{ $partner->region }}"
   class="partner-card">

  {{-- Логотип с одинаковой рамкой --}}
  <div class="partner-card__logo">
    @if($partner->logo_image)
      <img src="{{ asset('storage/' . $partner->logo_image) }}"
           alt="{{ $name }}"
           loading="lazy" decoding="async">
    @else
      <span class="partner-card__logo-fallback">{{ $partner->logo_text ?: mb_substr($name, 0, 1) }}</span>
    @endif

    {{-- Бейджи в правом верхнем углу лого --}}
    <div class="partner-card__badges">
      @if($isPopular)
        <span class="partner-card__badge partner-card__badge--hot" title="Один из самых просматриваемых">🔥</span>
      @endif
    </div>
  </div>

  {{-- Meta: категория + регион --}}
  <div class="partner-card__meta">
    <span class="chip chip--sm">{{ $categoryLabel }}</span>
    @if($regionLabel)
      <span class="partner-card__region">
        <span class="material-symbols-outlined" style="font-size:.9rem; vertical-align:-2px;">location_on</span>
        {{ $regionLabel }}
      </span>
    @endif
  </div>

  <h3 class="partner-card__title">{{ $name }}</h3>

  @unless($compact)
    @if($description)
      <p class="partner-card__desc">{{ $description }}</p>
    @endif

    <div class="partner-card__footer">
      <span class="partner-card__cta">
        @switch($cur) @case('uz') Batafsil @break @case('en') Details @break @default Подробнее @endswitch
        <span class="material-symbols-outlined partner-card__arrow">arrow_forward</span>
      </span>
    </div>
  @endunless
</a>
