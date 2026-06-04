@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
@endphp

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const chips = document.querySelectorAll('[data-partners-filter] .filter-chip');
  const cards = document.querySelectorAll('[data-partner-card]');
  chips.forEach(c => c.addEventListener('click', () => {
    chips.forEach(x => x.classList.remove('is-active'));
    c.classList.add('is-active');
    const cat = c.dataset.filter;
    cards.forEach(card => card.classList.toggle('is-hidden', cat !== 'all' && card.dataset.category !== cat));
  }));
});
</script>
@endpush

@section('content')

<section class="hero" style="padding:5rem 1.5rem;">
  <div class="hero__inner" style="grid-template-columns:1fr;">
    <div style="max-width:50rem;">
      <span class="tag tag-on-dark"><span class="tag-dot"></span>@switch($cur) @case('uz') MEYOS ekotizimi @break @case('en') MEYOS ecosystem @break @default Экосистема MEYOS @endswitch</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">{{ $tr($page, 'hero_h1', 'Партнёры и резиденты ассоциации') }}</h1>
      <p class="lead">{{ $tr($page, 'hero_lead', 'Единая B2B-сеть мебельной отрасли Узбекистана.') }}</p>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div data-partners-filter style="display:flex; flex-wrap:wrap; gap:.5rem; justify-content:center; margin-bottom:2.5rem;">
      <button class="filter-chip is-active" data-filter="all">@switch($cur) @case('uz') Barchasi @break @case('en') All @break @default Все @endswitch</button>
      @foreach (\App\Models\Partner::CATEGORIES as $key => $label)
        <button class="filter-chip" data-filter="{{ $key }}">{{ $label }}</button>
      @endforeach
    </div>

    <div class="grid grid-4">
      @foreach ($partners as $partner)
        @php $hasUrl = !empty($partner->website_url); @endphp
        <{{ $hasUrl ? 'a' : 'div' }}
          @if($hasUrl) href="{{ $partner->website_url }}" target="_blank" rel="noopener" @endif
          data-partner-card data-category="{{ $partner->category }}"
          class="card"
          style="padding:1.5rem; display:block; text-decoration:none; color:inherit; {{ $hasUrl ? 'cursor:pointer;' : '' }}">
          <div style="height:72px; display:flex; align-items:center; justify-content:center; background:rgb(var(--surface-deep)); border-radius:var(--radius-md); font-family:var(--font-head); font-weight:800; font-size:1.1rem; color:rgb(var(--primary)); margin-bottom:1rem; overflow:hidden;">
            @if($partner->logo_image)
              <img src="{{ asset('storage/' . $partner->logo_image) }}" loading="lazy" decoding="async" class="lazy-img" style="max-height:90%; max-width:90%; object-fit:contain;" alt="{{ $tr($partner, 'name') }}">
            @else
              {{ $partner->logo_text ?: $tr($partner, 'name') }}
            @endif
          </div>
          <span class="chip">{{ \App\Models\Partner::CATEGORIES[$partner->category] ?? $partner->category }}</span>
          <h3 style="font-size:1.05rem; margin:.75rem 0 .5rem;">{{ $tr($partner, 'name') }}</h3>
          <p class="text-mut" style="font-size:.85rem; margin:0; line-height:1.5;">{{ $tr($partner, 'description') }}</p>
          @if($hasUrl)
            <div style="margin-top:.75rem; font-size:.75rem; color:rgb(var(--primary)); font-weight:600;">
              @switch($cur) @case('uz') Saytga oʻtish @break @case('en') Visit website @break @default Перейти на сайт @endswitch
              <span class="material-symbols-outlined" style="font-size:.9rem; vertical-align:-2px;">arrow_forward</span>
            </div>
          @endif
        </{{ $hasUrl ? 'a' : 'div' }}>
      @endforeach
    </div>
  </div>
</section>

@endsection
