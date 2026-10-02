@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);

    // Счётчики по категориям
    $counts = $acts->groupBy('category')->map->count();
    $totalCount = $acts->count();

    $labels = [
        'home'      => \App\Support\Cms::text('legislation.crumb_home',  ['ru' => 'Главная', 'uz' => 'Bosh sahifa', 'en' => 'Home'][$cur]),
        'crumb'     => \App\Support\Cms::text('legislation.crumb_this',  ['ru' => 'Законодательство', 'uz' => 'Qonunchilik', 'en' => 'Legislation'][$cur]),
        'all'       => \App\Support\Cms::text('legislation.chip_all',    ['ru' => 'Все', 'uz' => 'Barchasi', 'en' => 'All'][$cur]),
        'search'    => \App\Support\Cms::text('legislation.search_ph',   ['ru' => 'Поиск по номеру и тексту…', 'uz' => 'Raqam va matn boʻyicha qidirish…', 'en' => 'Search by number or text…'][$cur]),
        'empty'     => \App\Support\Cms::text('legislation.empty',       ['ru' => 'По этим фильтрам ничего не найдено', 'uz' => 'Bu filtrlarga mos hech narsa yoʻq', 'en' => 'Nothing matches these filters'][$cur]),
        'open'      => \App\Support\Cms::text('legislation.open',        ['ru' => 'Открыть', 'uz' => 'Ochish', 'en' => 'Open'][$cur]),
        'reset'     => \App\Support\Cms::text('legislation.reset',       ['ru' => 'Сбросить фильтры', 'uz' => 'Filtrlarni tozalash', 'en' => 'Reset filters'][$cur]),
        'shown'     => \App\Support\Cms::text('legislation.shown',       ['ru' => 'Показано', 'uz' => 'Koʻrsatildi', 'en' => 'Showing'][$cur]),
        'of'        => \App\Support\Cms::text('legislation.of',          ['ru' => 'из', 'uz' => 'jami', 'en' => 'of'][$cur]),
    ];
@endphp

@section('content')

<div class="container" style="padding-top:1.25rem;">
  @include('partials.breadcrumbs', ['items' => [
    ['label' => $labels['crumb'], 'url' => null],
  ]])
</div>

<section class="hero" style="padding:5rem 1.5rem;">
  <div class="hero__inner" style="grid-template-columns:1fr;">
    <div style="max-width:60rem;">
      <span class="tag tag-on-dark"><span class="tag-dot"></span>@cms('legislation.tag', match($cur) { 'uz' => 'Normativ baza', 'en' => 'Regulatory base', default => 'Нормативная база' })</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">
        @cms('legislation.h1', match($cur) { 'uz' => 'Mebel sanoati qonunchiligi', 'en' => 'Furniture industry legislation', default => 'Законодательство мебельной индустрии Узбекистана' })
      </h1>
      <p class="lead">
        @cms('legislation.lead', match($cur) {
            'uz' => 'Postanovleniyalar va qonun loyihalari toʻplami: PQ-193, PQ-5155, PQ-2973 va boshqalar — matn, PDF va manba.',
            'en' => 'Collected decrees and draft bills: PP-193, PP-5155, PP-2973 and more — text, PDF, source.',
            default => 'Собрание постановлений и законопроектов: ПП-193, ПП-5155, ПП-2973 и другие — текст, PDF, источник.',
        })
      </p>
    </div>
  </div>
</section>

<section>
  <div class="container">
    {{-- Toolbar: поиск + чипы (клиентская фильтрация) --}}
    <div class="legal-toolbar" data-legal-toolbar>
      <div class="search-input search-input--rounded">
        <span class="search-input__icon material-symbols-outlined" aria-hidden="true">search</span>
        <input type="search" data-legal-search value="{{ $q ?? '' }}" placeholder="{{ $labels['search'] }}" aria-label="{{ $labels['search'] }}">
      </div>

      <div class="legal-toolbar__chips" data-legal-filter>
        <button type="button" class="filter-chip is-active" data-filter="all">
          {{ $labels['all'] }}<span class="filter-chip__count">{{ $totalCount }}</span>
        </button>
        @foreach ($categories as $key => $label)
          @php $count = $counts->get($key, 0); @endphp
          @if ($count > 0)
            <button type="button" class="filter-chip" data-filter="{{ $key }}">
              {{ $label }}<span class="filter-chip__count">{{ $count }}</span>
            </button>
          @endif
        @endforeach
      </div>
    </div>

    <div class="legal-list" data-legal-grid>
      @foreach ($acts as $act)
        @php
          $titleTxt   = $tr($act, 'title');
          $summaryTxt = $tr($act, 'summary');
          $searchIdx  = mb_strtolower(trim(($act->act_number ?? '') . ' ' . $titleTxt . ' ' . strip_tags((string) $summaryTxt)));
        @endphp
        <a href="{{ route('legislation.show', $act->slug) }}"
           class="legal-card"
           data-legal-card
           data-category="{{ $act->category }}"
           data-status="{{ $act->status }}"
           data-search-index="{{ $searchIdx }}">
          <div class="legal-card__head">
            @if($act->act_number)
              <span class="legal-card__number">{{ $act->act_number }}</span>
            @endif
            <span class="legal-card__badge legal-card__badge--{{ $act->status }}">
              {{ $statuses[$act->status] ?? $act->status }}
            </span>
            <span class="legal-card__cat">{{ $categories[$act->category] ?? $act->category }}</span>
            @if($act->act_date)
              <span class="legal-card__date">{{ $act->act_date->format('d.m.Y') }}</span>
            @endif
          </div>
          <h3 class="legal-card__title">{{ $titleTxt }}</h3>
          @if($summaryTxt)
            <p class="legal-card__summary">{{ Str::limit(strip_tags($summaryTxt), 220) }}</p>
          @endif
          <span class="legal-card__cta">{{ $labels['open'] }} →</span>
        </a>
      @endforeach
    </div>

    {{-- Empty-state --}}
    <div class="legal-empty" data-legal-empty>
      <div class="legal-empty__icon"><span class="material-symbols-outlined">search_off</span></div>
      <h3 class="legal-empty__title">{{ $labels['empty'] }}</h3>
      <button type="button" class="btn btn-outline" data-legal-reset>{{ $labels['reset'] }}</button>
    </div>

    {{-- X из Y --}}
    <div class="legal-status" data-legal-status>
      {{ $labels['shown'] }} <strong data-shown-count>{{ $totalCount }}</strong> {{ $labels['of'] }} <strong>{{ $totalCount }}</strong>
    </div>
  </div>
</section>

@endsection

@push('head')
<style>
.legal-toolbar { max-width: 60rem; margin: 0 auto 1.5rem; display: flex; flex-direction: column; gap: .9rem; }
.legal-toolbar__chips { display: flex; flex-wrap: wrap; gap: .5rem; }
.legal-list { display: grid; gap: 1.25rem; max-width: 60rem; margin: 0 auto; transition: opacity .2s; }
.legal-list.is-filtering { opacity: .55; }
.legal-card {
  display: block; padding: 1.5rem 1.75rem; border: 1px solid rgb(var(--outline));
  border-radius: var(--radius-lg); background: rgb(var(--surface));
  text-decoration: none; color: inherit; transition: border-color .2s, transform .2s, box-shadow .2s;
}
.legal-card:hover { border-color: rgb(var(--primary)); transform: translateY(-2px);
  box-shadow: 0 8px 20px rgb(0 0 0 / .06); }
.legal-card.is-hidden { display: none; }
.legal-card__head { display: flex; flex-wrap: wrap; gap: .6rem; align-items: center;
  margin-bottom: .75rem; font-size: .8rem; }
.legal-card__number { font-family: var(--font-head); font-weight: 800; color: rgb(var(--primary));
  font-size: .95rem; letter-spacing: .02em; }
.legal-card__badge { padding: .18rem .55rem; border-radius: var(--radius-pill); font-weight: 600;
  text-transform: uppercase; letter-spacing: .05em; font-size: .7rem; }
.legal-card__badge--active { background: #e3f3e7; color: #2a7c3b; }
.legal-card__badge--draft { background: #fff2d6; color: #a05a00; }
.legal-card__badge--repealed { background: rgb(var(--surface-deep)); color: rgb(var(--on-surface-mut)); }
.legal-card__cat { color: rgb(var(--on-surface-mut)); }
.legal-card__date { margin-left: auto; color: rgb(var(--on-surface-mut)); font-variant-numeric: tabular-nums; }
.legal-card__title { font-size: 1.15rem; line-height: 1.35; margin: 0 0 .5rem; }
.legal-card__summary { color: rgb(var(--on-surface-mut)); font-size: .9rem; line-height: 1.55; margin: 0 0 .75rem; }
.legal-card__cta { color: rgb(var(--primary)); font-weight: 600; font-size: .9rem; }

.legal-empty { display: none; text-align: center; padding: 3rem 1rem;
  color: rgb(var(--on-surface-mut)); max-width: 40rem; margin: 1.5rem auto 0; }
.legal-empty.is-visible { display: block; }
.legal-empty__icon .material-symbols-outlined { font-size: 3rem; opacity: .4; }
.legal-empty__title { margin: .5rem 0 1rem; font-size: 1.05rem; font-weight: 600; }

.legal-status { display: none; text-align: center; margin-top: 1.25rem;
  font-size: .85rem; color: rgb(var(--on-surface-mut)); }
.legal-status.is-visible { display: block; }

@media (max-width: 639px) {
  .legal-card { padding: 1.15rem 1.25rem; }
  .legal-card__date { margin-left: 0; width: 100%; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const grid     = document.querySelector('[data-legal-grid]');
  if (!grid) return;
  const emptyBox = document.querySelector('[data-legal-empty]');
  const statusBx = document.querySelector('[data-legal-status]');
  const shownEl  = document.querySelector('[data-shown-count]');
  const resetBtn = document.querySelector('[data-legal-reset]');
  const chips    = document.querySelectorAll('[data-legal-filter] .filter-chip');
  const searchIn = document.querySelector('[data-legal-search]');
  const cards    = Array.from(document.querySelectorAll('[data-legal-card]'));
  const total    = cards.length;

  let currentCat = 'all';
  let currentQ   = '';

  const apply = (writeUrl = true) => {
    grid.classList.add('is-filtering');
    setTimeout(() => grid.classList.remove('is-filtering'), 160);

    const words = currentQ.split(/\s+/).filter(w => w.length >= 2);

    let shown = 0;
    cards.forEach(card => {
      const catOk    = currentCat === 'all' || card.dataset.category === currentCat;
      const searchOk = words.length === 0 || words.every(w => (card.dataset.searchIndex || '').includes(w));
      const visible  = catOk && searchOk;
      card.classList.toggle('is-hidden', !visible);
      if (visible) shown++;
    });

    if (shownEl) shownEl.textContent = shown;
    if (statusBx) {
      const show = !(shown === total && currentCat === 'all' && currentQ === '');
      statusBx.classList.toggle('is-visible', show);
    }
    if (emptyBox) emptyBox.classList.toggle('is-visible', shown === 0);

    // Deep-linking через history.replaceState
    if (writeUrl && history.replaceState) {
      const params = new URLSearchParams(window.location.search);
      currentCat === 'all' ? params.delete('category') : params.set('category', currentCat);
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

  resetBtn?.addEventListener('click', () => {
    currentCat = 'all';
    currentQ   = '';
    chips.forEach(c => c.classList.toggle('is-active', c.dataset.filter === 'all'));
    if (searchIn) searchIn.value = '';
    apply();
  });

  // Инициализация из URL
  const params = new URLSearchParams(window.location.search);
  const initCat = params.get('category');
  const initQ   = params.get('q');
  if (initCat) {
    const target = document.querySelector(`[data-legal-filter] .filter-chip[data-filter="${initCat}"]`);
    if (target) {
      chips.forEach(c => c.classList.remove('is-active'));
      target.classList.add('is-active');
      currentCat = initCat;
    }
  }
  if (initQ && searchIn) {
    searchIn.value = initQ;
    currentQ = initQ.toLowerCase();
  }
  if (initCat || initQ) apply(false);
});
</script>
@endpush
