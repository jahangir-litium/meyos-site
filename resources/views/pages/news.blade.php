@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
    $counts = $news->groupBy('category')->map->count();
    $totalCount = $news->count();

    $labels = [
        'crumb'    => \App\Support\Cms::text('news.crumb',    ['ru' => 'Новости', 'uz' => 'Yangiliklar', 'en' => 'News'][$cur]),
        'all'      => \App\Support\Cms::text('news.filter_all', ['ru' => 'Все', 'uz' => 'Barchasi', 'en' => 'All'][$cur]),
        'search'   => \App\Support\Cms::text('news.search_placeholder', ['ru' => 'Поиск по новостям…', 'uz' => 'Yangiliklar boʻyicha qidiruv…', 'en' => 'Search news…'][$cur]),
        'empty_q'  => \App\Support\Cms::text('news.empty',    ['ru' => 'По этим фильтрам ничего не найдено', 'uz' => 'Bu filtrlarga mos hech narsa yoʻq', 'en' => 'Nothing matches these filters'][$cur]),
        'reset'    => \App\Support\Cms::text('news.reset',    ['ru' => 'Сбросить фильтры', 'uz' => 'Filtrlarni tozalash', 'en' => 'Reset filters'][$cur]),
        'shown'    => \App\Support\Cms::text('news.shown',    ['ru' => 'Показано', 'uz' => 'Koʻrsatildi', 'en' => 'Showing'][$cur]),
        'of'       => \App\Support\Cms::text('news.of',       ['ru' => 'из', 'uz' => 'jami', 'en' => 'of'][$cur]),
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
    <div style="max-width:50rem;">
      <span class="tag tag-on-dark"><span class="tag-dot"></span>@cms('news.hero_tag', 'Новости')</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">@cms('news.hero_h1', 'Что происходит в мебельной индустрии Узбекистана')</h1>
    </div>
  </div>
</section>

<section>
  <div class="container">

    {{-- Toolbar: поиск + чипы (клиентская фильтрация) --}}
    <div class="news-toolbar" data-news-toolbar>
      <div class="search-input search-input--rounded">
        <span class="search-input__icon material-symbols-outlined" aria-hidden="true">search</span>
        <input type="search" data-news-search value="{{ $q ?? '' }}" placeholder="{{ $labels['search'] }}" aria-label="{{ $labels['search'] }}">
      </div>

      <div class="news-toolbar__chips" data-news-filter>
        <button type="button" class="filter-chip is-active" data-filter="all">
          {{ $labels['all'] }}<span class="filter-chip__count">{{ $totalCount + ($featured ? 1 : 0) }}</span>
        </button>
        @foreach ($categories as $key => $label)
          @php $count = $counts->get($key, 0) + (($featured && $featured->category === $key) ? 1 : 0); @endphp
          @if ($count > 0)
            <button type="button" class="filter-chip" data-filter="{{ $key }}">
              {{ $label }}<span class="filter-chip__count">{{ $count }}</span>
            </button>
          @endif
        @endforeach
      </div>
    </div>

    {{-- Featured — скрываем при фильтрации, т.к. он всегда «из любой категории» --}}
    @if ($featured)
      <a href="{{ route('news.show', $featured->slug) }}"
         data-featured
         data-category="{{ $featured->category }}"
         style="text-decoration:none; color:inherit; display:block; border:1px solid rgb(var(--outline)); border-radius:var(--radius-lg); overflow:hidden; margin-bottom:2rem; max-width:920px; margin-left:auto; margin-right:auto;">
        <div style="display:grid; grid-template-columns:1fr 1.2fr; gap:0;" class="featured-grid">
          <div style="aspect-ratio:4/3; background:rgb(var(--surface-deep));">
            @if($featured->cover_image)<img src="{{ asset('storage/' . $featured->cover_image) }}" alt="{{ $tr($featured, 'image_alt', $tr($featured, 'title')) }}" loading="lazy" decoding="async" class="lazy-img" style="width:100%; height:100%; object-fit:cover;">@endif
          </div>
          <div style="padding:1.25rem 1.5rem;">
            <span class="chip">{{ \App\Models\News::allCategories()[$featured->category] ?? '' }}</span>
            <h2 style="font-size:1.15rem; line-height:1.3; margin:.75rem 0 .5rem;">{{ $tr($featured, 'title') }}</h2>
            <p class="text-mut" style="line-height:1.55; font-size:.9rem; margin:0;">{{ Str::limit($tr($featured, 'preview'), 140) }}</p>
            <div style="margin-top:.85rem; font-size:.7rem; color:rgb(var(--on-surface-mut)); letter-spacing:.1em; text-transform:uppercase;">{{ $featured->published_at->format('d.m.Y') }}</div>
          </div>
        </div>
      </a>
      <style>
        @media (max-width: 720px) {
          .featured-grid { grid-template-columns: 1fr !important; }
          .featured-grid > div:first-child { aspect-ratio: 16/9 !important; }
        }
      </style>
    @endif

    <div class="grid grid-3 news-grid" data-news-grid>
      @foreach ($news as $item)
        @php
          $titleTxt   = $tr($item, 'title');
          $previewTxt = $tr($item, 'preview');
          $searchIdx  = mb_strtolower(trim($titleTxt . ' ' . strip_tags((string) $previewTxt)));
        @endphp
        <a href="{{ route('news.show', $item->slug) }}"
           data-news-card
           data-category="{{ $item->category }}"
           data-search-index="{{ $searchIdx }}"
           style="text-decoration:none; color:inherit; display:block; border:1px solid rgb(var(--outline)); border-radius:var(--radius-lg); overflow:hidden;">
          <div style="aspect-ratio:16/9; background:rgb(var(--surface-deep));">
            @if($item->cover_image)<img src="{{ asset('storage/' . $item->cover_image) }}" alt="{{ $tr($item, 'image_alt', $tr($item, 'title')) }}" loading="lazy" decoding="async" class="lazy-img" style="width:100%; height:100%; object-fit:cover;">@endif
          </div>
          <div style="padding:1.5rem;">
            <span class="chip">{{ \App\Models\News::allCategories()[$item->category] ?? '' }}</span>
            <h3 class="mt-3" style="font-size:1.15rem; line-height:1.3;">{{ $titleTxt }}</h3>
            <div data-card-footer class="news-date" style="margin-top:1rem; font-size:.75rem; color:rgb(var(--on-surface-mut)); letter-spacing:.1em; text-transform:uppercase;">{{ $item->published_at->format('d.m.Y') }}</div>
          </div>
        </a>
      @endforeach
    </div>

    {{-- Empty-state --}}
    <div class="news-empty" data-news-empty>
      <div class="news-empty__icon"><span class="material-symbols-outlined">search_off</span></div>
      <h3 class="news-empty__title">{{ $labels['empty_q'] }}</h3>
      <button type="button" class="btn btn-outline" data-news-reset>{{ $labels['reset'] }}</button>
    </div>

    {{-- X из Y --}}
    <div class="news-status" data-news-status>
      {{ $labels['shown'] }} <strong data-shown-count>{{ $totalCount + ($featured ? 1 : 0) }}</strong> {{ $labels['of'] }} <strong>{{ $totalCount + ($featured ? 1 : 0) }}</strong>
    </div>
  </div>
</section>

@endsection

@push('head')
<style>
.news-toolbar { max-width: 60rem; margin: 0 auto 1.5rem; display: flex; flex-direction: column; gap: .9rem; }
.news-toolbar__chips { display: flex; flex-wrap: wrap; gap: .5rem; }
.news-grid { transition: opacity .2s; }
.news-grid.is-filtering { opacity: .55; }
[data-news-card].is-hidden, [data-featured].is-hidden { display: none !important; }

.news-empty { display: none; text-align: center; padding: 3rem 1rem;
  color: rgb(var(--on-surface-mut)); max-width: 40rem; margin: 1.5rem auto 0; }
.news-empty.is-visible { display: block; }
.news-empty__icon .material-symbols-outlined { font-size: 3rem; opacity: .4; }
.news-empty__title { margin: .5rem 0 1rem; font-size: 1.05rem; font-weight: 600; }

.news-status { display: none; text-align: center; margin-top: 1.25rem;
  font-size: .85rem; color: rgb(var(--on-surface-mut)); }
.news-status.is-visible { display: block; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const grid     = document.querySelector('[data-news-grid]');
  if (!grid) return;
  const emptyBox = document.querySelector('[data-news-empty]');
  const statusBx = document.querySelector('[data-news-status]');
  const shownEl  = document.querySelector('[data-shown-count]');
  const resetBtn = document.querySelector('[data-news-reset]');
  const chips    = document.querySelectorAll('[data-news-filter] .filter-chip');
  const searchIn = document.querySelector('[data-news-search]');
  const cards    = Array.from(document.querySelectorAll('[data-news-card]'));
  const featured = document.querySelector('[data-featured]');
  const total    = cards.length + (featured ? 1 : 0);

  // Featured тоже имеет category и попадает в поиск
  if (featured) {
    const h = featured.querySelector('h2')?.textContent || '';
    const p = featured.querySelector('p')?.textContent || '';
    featured.dataset.searchIndex = (h + ' ' + p).toLowerCase();
  }

  let currentCat = 'all';
  let currentQ   = '';

  const apply = (writeUrl = true) => {
    grid.classList.add('is-filtering');
    setTimeout(() => grid.classList.remove('is-filtering'), 160);

    const words = currentQ.split(/\s+/).filter(w => w.length >= 2);

    let shown = 0;
    const matches = (el) => {
      const catOk    = currentCat === 'all' || el.dataset.category === currentCat;
      const searchOk = words.length === 0 || words.every(w => (el.dataset.searchIndex || '').includes(w));
      return catOk && searchOk;
    };

    cards.forEach(card => {
      const vis = matches(card);
      card.classList.toggle('is-hidden', !vis);
      if (vis) shown++;
    });
    if (featured) {
      const vis = matches(featured);
      featured.classList.toggle('is-hidden', !vis);
      if (vis) shown++;
    }

    if (shownEl) shownEl.textContent = shown;
    if (statusBx) {
      const show = !(shown === total && currentCat === 'all' && currentQ === '');
      statusBx.classList.toggle('is-visible', show);
    }
    if (emptyBox) emptyBox.classList.toggle('is-visible', shown === 0);

    if (writeUrl && history.replaceState) {
      const params = new URLSearchParams(window.location.search);
      currentCat === 'all' ? params.delete('category') : params.set('category', currentCat);
      currentQ === ''      ? params.delete('q')        : params.set('q',        currentQ);
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
    const target = document.querySelector(`[data-news-filter] .filter-chip[data-filter="${initCat}"]`);
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
