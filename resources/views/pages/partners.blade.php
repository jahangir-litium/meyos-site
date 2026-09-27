@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);

    $categories = \App\Models\Partner::allCategories();
    // Счётчики по каждой категории
    $counts = $partners->groupBy('category')->map->count();
    $totalCount = $partners->count();

    // Список регионов, которые реально встречаются у партнёров
    $usedRegions = $partners->pluck('region')->filter()->unique()->values();
    $regionsMap  = \App\Models\Partner::REGIONS;

    $labels = [
        'all'     => ['ru' => 'Все', 'uz' => 'Barchasi', 'en' => 'All'][$cur],
        'shown'   => ['ru' => 'Показано', 'uz' => 'Koʻrsatildi', 'en' => 'Showing'][$cur],
        'of'      => ['ru' => 'из', 'uz' => 'jami', 'en' => 'of'][$cur],
        'empty'   => ['ru' => 'По этим фильтрам ничего не найдено', 'uz' => 'Bu filtrlarga mos hech narsa yoʻq', 'en' => 'Nothing matches these filters'][$cur],
        'reset'   => ['ru' => 'Сбросить фильтры', 'uz' => 'Filtrlarni tozalash', 'en' => 'Reset filters'][$cur],
        'region'  => ['ru' => 'Все регионы', 'uz' => 'Barcha hududlar', 'en' => 'All regions'][$cur],
    ];
@endphp

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
    @include('partials.breadcrumbs', ['items' => [
      ['label' => (['ru' => 'Партнёры', 'uz' => 'Hamkorlar', 'en' => 'Partners'])[$cur] ?? 'Партнёры', 'url' => null],
    ]])

    {{-- ============ Sticky-фильтр ============ --}}
    <div class="partners-toolbar" data-partners-toolbar>
      <div class="partners-toolbar__search">
        <div class="search-input search-input--rounded">
          <span class="search-input__icon material-symbols-outlined" aria-hidden="true">search</span>
          <input type="search" data-partners-search
                 placeholder="@switch($cur) @case('uz') Kompaniya nomi boʻyicha qidiruv… @break @case('en') Search by company name… @break @default Поиск по названию компании… @endswitch">
        </div>
      </div>
      <div class="partners-toolbar__row" data-partners-filter>
        <button class="filter-chip is-active" data-filter="all">
          {{ $labels['all'] }}<span class="filter-chip__count">{{ $totalCount }}</span>
        </button>
        @foreach ($categories as $key => $label)
          @php $count = $counts->get($key, 0); @endphp
          @if ($count > 0)
            <button class="filter-chip" data-filter="{{ $key }}">
              {{ $label }}<span class="filter-chip__count">{{ $count }}</span>
            </button>
          @endif
        @endforeach
      </div>

      @if($usedRegions->count() > 1)
        <div class="partners-toolbar__row" style="margin-top:.6rem;">
          <select data-region-filter class="filter-select" style="padding:.5rem 1rem; border-radius:var(--radius-pill); border:1px solid rgb(var(--outline)); background:rgb(var(--surface)); font-size:.85rem;">
            <option value="all">{{ $labels['region'] }}</option>
            @foreach($usedRegions as $slug)
              <option value="{{ $slug }}">{{ $regionsMap[$slug] ?? $slug }}</option>
            @endforeach
          </select>
        </div>
      @endif
    </div>

    <div class="grid grid-4 partners-grid" data-partners-grid>
      @foreach ($partners as $partner)
        <x-partner-card :partner="$partner" />
      @endforeach
    </div>

    {{-- ============ Empty-state ============ --}}
    <div class="partners-empty" data-partners-empty>
      <div class="partners-empty__icon"><span class="material-symbols-outlined">search_off</span></div>
      <h3 class="partners-empty__title">{{ $labels['empty'] }}</h3>
      <button type="button" class="btn btn-outline" data-partners-reset>{{ $labels['reset'] }}</button>
    </div>

    {{-- ============ X из Y ============ --}}
    <div class="partners-status" data-partners-status>
      {{ $labels['shown'] }} <strong data-shown-count>{{ $totalCount }}</strong> {{ $labels['of'] }} <strong>{{ $totalCount }}</strong>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const toolbar   = document.querySelector('[data-partners-toolbar]');
  const grid      = document.querySelector('[data-partners-grid]');
  const emptyBox  = document.querySelector('[data-partners-empty]');
  const shownEl   = document.querySelector('[data-shown-count]');
  const statusBox = document.querySelector('[data-partners-status]');
  const resetBtn  = document.querySelector('[data-partners-reset]');
  const chips     = document.querySelectorAll('[data-partners-filter] .filter-chip');
  const regionSel = document.querySelector('[data-region-filter]');
  const searchIn  = document.querySelector('[data-partners-search]');
  const cards     = Array.from(document.querySelectorAll('[data-partner-card]'));
  if (!grid) return;

  // Индекс поиска: собираем name+description один раз в lowercase
  cards.forEach(card => {
    const name = card.querySelector('.partner-card__title')?.textContent || '';
    const desc = card.querySelector('.partner-card__desc')?.textContent || '';
    card.dataset.searchIndex = (name + ' ' + desc).toLowerCase();
  });

  // ---------- Sticky-detection ----------
  const sentinel = document.createElement('div');
  sentinel.style.cssText = 'position:absolute; top:0; height:1px; width:1px;';
  toolbar.before(sentinel);
  const stickyObs = new IntersectionObserver(([e]) => {
    toolbar.classList.toggle('is-stuck', !e.isIntersecting);
  }, { threshold: [0] });
  stickyObs.observe(sentinel);

  // ---------- Фильтрация ----------
  let currentCat = 'all';
  let currentReg = 'all';
  let currentQ   = '';

  const apply = (writeUrl = true) => {
    // Skeleton-эффект на 200ms
    grid.classList.add('is-filtering');
    setTimeout(() => grid.classList.remove('is-filtering'), 200);

    // Разбиваем запрос на слова для мультисловного поиска
    const words = currentQ.split(/\s+/).filter(w => w.length >= 2);

    let shown = 0;
    cards.forEach(card => {
      const catOk = currentCat === 'all' || card.dataset.category === currentCat;
      const regOk = currentReg === 'all' || card.dataset.region   === currentReg;
      const searchOk = words.length === 0 || words.every(w => card.dataset.searchIndex.includes(w));
      const visible = catOk && regOk && searchOk;
      card.classList.toggle('is-hidden', !visible);
      if (visible) shown++;
    });

    // X из Y
    if (shownEl) shownEl.textContent = shown;
    statusBox.style.display = (shown === cards.length && currentCat === 'all' && currentReg === 'all' && currentQ === '') ? 'none' : 'block';

    // Empty state
    emptyBox.classList.toggle('is-visible', shown === 0);

    // ---------- Deep-linking: пишем URL ----------
    if (writeUrl && history.replaceState) {
      const params = new URLSearchParams(window.location.search);
      currentCat === 'all' ? params.delete('category') : params.set('category', currentCat);
      currentReg === 'all' ? params.delete('region')   : params.set('region',   currentReg);
      currentQ === ''      ? params.delete('q')        : params.set('q',        currentQ);
      const qs = params.toString();
      history.replaceState(null, '', qs ? `?${qs}` : window.location.pathname);
    }
  };

  // Поиск с debounce 150ms
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
    currentCat = chip.dataset.filter;
    apply();
  }));

  regionSel?.addEventListener('change', () => {
    currentReg = regionSel.value;
    apply();
  });

  resetBtn?.addEventListener('click', () => {
    currentCat = 'all';
    currentReg = 'all';
    currentQ   = '';
    chips.forEach(c => c.classList.toggle('is-active', c.dataset.filter === 'all'));
    if (regionSel) regionSel.value = 'all';
    if (searchIn) searchIn.value = '';
    apply();
  });

  // ---------- Deep-linking: читаем URL при загрузке ----------
  const params = new URLSearchParams(window.location.search);
  const initCat = params.get('category');
  const initReg = params.get('region');
  const initQ   = params.get('q');
  if (initCat) {
    const target = document.querySelector(`[data-partners-filter] .filter-chip[data-filter="${initCat}"]`);
    if (target) {
      chips.forEach(c => c.classList.remove('is-active'));
      target.classList.add('is-active');
      currentCat = initCat;
    }
  }
  if (initReg && regionSel) {
    regionSel.value = initReg;
    currentReg = initReg;
  }
  if (initQ && searchIn) {
    searchIn.value = initQ;
    currentQ = initQ.toLowerCase();
  }
  if (initCat || initReg || initQ) apply(false);
});
</script>
@endpush
