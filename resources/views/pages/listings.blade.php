@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $counts = $items->groupBy('listing_type')->map->count();
    $totalCount = $items->count();

    // ===== SEO =====
    $seoTitle = \App\Support\Cms::text('listings.seo_title', match($cur) {
        'uz' => 'Mebel sanoati eʼlonlari — MEYOS',
        'en' => 'Furniture industry listings — MEYOS',
        default => 'Актуальные объявления по мебели (SAVDEX) — MEYOS',
    });
    $seoDesc = \App\Support\Cms::text('listings.seo_description', match($cur) {
        'uz' => 'B2B platforma savdex.uz dan mebel kategoriyasining soʻrov, taklif va tenderlari — har kuni yangilanadi.',
        'en' => 'Furniture category requests, offers and tenders from the B2B platform savdex.uz — refreshed daily.',
        default => 'Запросы, предложения и тендеры мебельной категории с B2B-платформы savdex.uz — обновляется ежедневно.',
    });
@endphp

@php
    $labels = [
        'crumb'   => \App\Support\Cms::text('listings.crumb',   'Объявления'),
        'tag'     => \App\Support\Cms::text('listings.hero_tag', 'Актуальный спрос'),
        'h1'      => \App\Support\Cms::text('listings.hero_h1',  'Объявления по мебели — SAVDEX'),
        'lead'    => \App\Support\Cms::text('listings.hero_lead','Собрано с B2B-платформы savdex.uz: запросы, предложения и тендеры мебельной категории. Обновляется ежедневно.'),
        'all'     => \App\Support\Cms::text('listings.filter_all',     'Все'),
        'search'  => \App\Support\Cms::text('listings.search_placeholder','Поиск по заголовку, городу…'),
        'empty'   => \App\Support\Cms::text('listings.empty',    'Пока нет подходящих объявлений'),
        'open'    => \App\Support\Cms::text('listings.btn_open', 'Открыть на SAVDEX'),
        'source'  => \App\Support\Cms::text('listings.source_label', 'Источник: savdex.uz · обновлено'),
        'reset'   => \App\Support\Cms::text('listings.reset',    'Сбросить фильтры'),
        'shown'   => \App\Support\Cms::text('listings.shown',    'Показано'),
        'of'      => \App\Support\Cms::text('listings.of',       'из'),
    ];
@endphp

@section('title', $seoTitle)
@section('description', $seoDesc)

@section('content')

<div class="container" style="padding-top:1.25rem;">
  @include('partials.breadcrumbs', ['items' => [
    ['label' => $labels['crumb'], 'url' => null],
  ]])
</div>

<section class="hero" style="padding:5rem 1.5rem;">
  <div class="hero__inner" style="grid-template-columns:1fr;">
    <div style="max-width:50rem;">
      <span class="tag tag-on-dark"><span class="tag-dot"></span>{{ $labels['tag'] }}</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">{{ $labels['h1'] }}</h1>
      <p class="lead">{{ $labels['lead'] }}</p>
    </div>
  </div>
</section>

<section>
  <div class="container">
    {{-- Toolbar: поиск + чипы (клиентская фильтрация) --}}
    <div class="listings-toolbar" data-listings-toolbar>
      <div class="search-input search-input--rounded">
        <span class="search-input__icon material-symbols-outlined" aria-hidden="true">search</span>
        <input type="search" data-listings-search value="{{ $q ?? '' }}" placeholder="{{ $labels['search'] }}" aria-label="{{ $labels['search'] }}">
      </div>

      <div class="listings-toolbar__chips" data-listings-filter>
        <button type="button" class="filter-chip is-active" data-filter="all">
          {{ $labels['all'] }}<span class="filter-chip__count">{{ $totalCount }}</span>
        </button>
        @foreach ($types as $key => $label)
          @php $count = $counts->get($key, 0); @endphp
          @if ($count > 0)
            <button type="button" class="filter-chip" data-filter="{{ $key }}">
              {{ $label }}<span class="filter-chip__count">{{ $count }}</span>
            </button>
          @endif
        @endforeach
      </div>
    </div>

    <div class="grid grid-3 listings-grid" data-listings-grid>
      @foreach ($items as $item)
        @php
          $searchIdx = mb_strtolower(trim(
              ($item->title ?? '') . ' ' .
              ($item->summary ?? '') . ' ' .
              ($item->city ?? '')
          ));
        @endphp
        <a href="{{ $item->source_url }}" target="_blank" rel="noopener"
           class="listing-card"
           data-listing-card
           data-type="{{ $item->listing_type }}"
           data-search-index="{{ $searchIdx }}">
          <div class="listing-card__media">
            @if($item->image_url)
              <img src="{{ $item->image_url }}" alt="{{ $item->title }}" loading="lazy" decoding="async" referrerpolicy="no-referrer"
                   onerror="this.style.display='none'">
            @else
              <span class="listing-card__placeholder material-symbols-outlined">chair</span>
            @endif
            <span class="listing-card__type listing-card__type--{{ $item->listing_type }}">
              {{ $types[$item->listing_type] ?? $item->listing_type }}
            </span>
          </div>
          <div class="listing-card__body">
            <h3 class="listing-card__title">{{ $item->title }}</h3>
            @if($item->summary)
              <p class="listing-card__summary">{{ Str::limit($item->summary, 140) }}</p>
            @endif
            <div class="listing-card__meta">
              @if($item->price)<span class="listing-card__price">{{ $item->price }}</span>@endif
              @if($item->city)<span class="listing-card__city"><span class="material-symbols-outlined">location_on</span>{{ $item->city }}</span>@endif
            </div>
            <div class="listing-card__footer">
              <span class="listing-card__cta">{{ $labels['open'] }} <span class="material-symbols-outlined">open_in_new</span></span>
              @if($item->published_at)
                <span class="listing-card__date">{{ $item->published_at->format('d.m.Y') }}</span>
              @endif
            </div>
          </div>
        </a>
      @endforeach
    </div>

    {{-- Empty-state --}}
    <div class="listings-empty" data-listings-empty>
      <div class="listings-empty__icon"><span class="material-symbols-outlined">search_off</span></div>
      <h3 class="listings-empty__title">{{ $labels['empty'] }}</h3>
      <button type="button" class="btn btn-outline" data-listings-reset>{{ $labels['reset'] }}</button>
    </div>

    {{-- X из Y --}}
    <div class="listings-status" data-listings-status>
      {{ $labels['shown'] }} <strong data-shown-count>{{ $totalCount }}</strong> {{ $labels['of'] }} <strong>{{ $totalCount }}</strong>
    </div>

    <p style="text-align:center; margin:2rem 0 0; color:rgb(var(--on-surface-mut)); font-size:.82rem;">
      {{ $labels['source'] }} {{ $items->first()?->fetched_at?->diffForHumans() ?? '' }}
    </p>
  </div>
</section>

@endsection

@push('head')
<style>
.listings-toolbar { max-width: 72rem; margin: 0 auto 1.5rem; display: flex; flex-direction: column; gap: .9rem; }
.listings-toolbar__chips { display: flex; flex-wrap: wrap; gap: .5rem; }
.listings-grid { transition: opacity .2s; }
.listings-grid.is-filtering { opacity: .55; }
.listing-card.is-hidden { display: none; }

.listings-empty { display: none; text-align: center; padding: 3rem 1rem;
  color: rgb(var(--on-surface-mut)); max-width: 40rem; margin: 1.5rem auto 0; }
.listings-empty.is-visible { display: block; }
.listings-empty__icon .material-symbols-outlined { font-size: 3rem; opacity: .4; }
.listings-empty__title { margin: .5rem 0 1rem; font-size: 1.05rem; font-weight: 600; }

.listings-status { display: none; text-align: center; margin-top: 1.25rem;
  font-size: .85rem; color: rgb(var(--on-surface-mut)); }
.listings-status.is-visible { display: block; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const grid     = document.querySelector('[data-listings-grid]');
  if (!grid) return;
  const emptyBox = document.querySelector('[data-listings-empty]');
  const statusBx = document.querySelector('[data-listings-status]');
  const shownEl  = document.querySelector('[data-shown-count]');
  const resetBtn = document.querySelector('[data-listings-reset]');
  const chips    = document.querySelectorAll('[data-listings-filter] .filter-chip');
  const searchIn = document.querySelector('[data-listings-search]');
  const cards    = Array.from(document.querySelectorAll('[data-listing-card]'));
  const total    = cards.length;

  let currentType = 'all';
  let currentQ    = '';

  const apply = (writeUrl = true) => {
    grid.classList.add('is-filtering');
    setTimeout(() => grid.classList.remove('is-filtering'), 160);

    const words = currentQ.split(/\s+/).filter(w => w.length >= 2);

    let shown = 0;
    cards.forEach(card => {
      const typeOk   = currentType === 'all' || card.dataset.type === currentType;
      const searchOk = words.length === 0 || words.every(w => (card.dataset.searchIndex || '').includes(w));
      const visible  = typeOk && searchOk;
      card.classList.toggle('is-hidden', !visible);
      if (visible) shown++;
    });

    if (shownEl) shownEl.textContent = shown;
    if (statusBx) {
      const show = !(shown === total && currentType === 'all' && currentQ === '');
      statusBx.classList.toggle('is-visible', show);
    }
    if (emptyBox) emptyBox.classList.toggle('is-visible', shown === 0);

    if (writeUrl && history.replaceState) {
      const params = new URLSearchParams(window.location.search);
      currentType === 'all' ? params.delete('type') : params.set('type', currentType);
      currentQ === ''       ? params.delete('q')    : params.set('q',    currentQ);
      const qs = params.toString();
      history.replaceState(null, '', qs ? `?${qs}` : window.location.pathname);
    }
  };

  let searchTimer;
  searchIn?.addEventListener('input', () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
      currentQ = searchIn.value.trim().toLowerCase();
      apply();
    }, 150);
  });

  chips.forEach(chip => chip.addEventListener('click', () => {
    chips.forEach(c => c.classList.remove('is-active'));
    chip.classList.add('is-active');
    currentType = chip.dataset.filter;
    apply();
  }));

  resetBtn?.addEventListener('click', () => {
    currentType = 'all';
    currentQ    = '';
    chips.forEach(c => c.classList.toggle('is-active', c.dataset.filter === 'all'));
    if (searchIn) searchIn.value = '';
    apply();
  });

  // Инициализация из URL
  const params = new URLSearchParams(window.location.search);
  const initType = params.get('type');
  const initQ    = params.get('q');
  if (initType) {
    const target = document.querySelector(`[data-listings-filter] .filter-chip[data-filter="${initType}"]`);
    if (target) {
      chips.forEach(c => c.classList.remove('is-active'));
      target.classList.add('is-active');
      currentType = initType;
    }
  }
  if (initQ && searchIn) {
    searchIn.value = initQ;
    currentQ = initQ.toLowerCase();
  }
  if (initType || initQ) apply(false);
});
</script>
@endpush
