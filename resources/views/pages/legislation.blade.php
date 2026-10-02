@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
    $labels = [
        'home'      => ['ru' => 'Главная', 'uz' => 'Bosh sahifa', 'en' => 'Home'][$cur],
        'crumb'     => ['ru' => 'Законодательство', 'uz' => 'Qonunchilik', 'en' => 'Legislation'][$cur],
        'all'       => ['ru' => 'Все', 'uz' => 'Barchasi', 'en' => 'All'][$cur],
        'search'    => ['ru' => 'Поиск по номеру и тексту…', 'uz' => 'Raqam va matn boʻyicha qidirish…', 'en' => 'Search by number or text…'][$cur],
        'empty'     => ['ru' => 'По этим фильтрам ничего не найдено', 'uz' => 'Bu filtrlarga mos hech narsa yoʻq', 'en' => 'Nothing matches these filters'][$cur],
        'source'    => ['ru' => 'Источник', 'uz' => 'Manba', 'en' => 'Source'][$cur],
        'pdf'       => ['ru' => 'Скачать PDF', 'uz' => 'PDF yuklab olish', 'en' => 'Download PDF'][$cur],
        'open'      => ['ru' => 'Открыть', 'uz' => 'Ochish', 'en' => 'Open'][$cur],
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
      <span class="tag tag-on-dark"><span class="tag-dot"></span>@switch($cur) @case('uz') Normativ baza @break @case('en') Regulatory base @break @default Нормативная база @endswitch</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">
        @switch($cur) @case('uz') Mebel sanoati qonunchiligi @break @case('en') Furniture industry legislation @break @default Законодательство мебельной индустрии Узбекистана @endswitch
      </h1>
      <p class="lead">
        @switch($cur) @case('uz') Postanovleniyalar va qonun loyihalari toʻplami: PQ-193, PQ-5155, PQ-2973 va boshqalar — matn, PDF va manba. @break
        @case('en') Collected decrees and draft bills: PP-193, PP-5155, PP-2973 and more — text, PDF, source. @break
        @default Собрание постановлений и законопроектов: ПП-193, ПП-5155, ПП-2973 и другие — текст, PDF, источник. @endswitch
      </p>
    </div>
  </div>
</section>

<section>
  <div class="container">
    {{-- Поиск --}}
    <form method="GET" action="{{ route('legislation') }}" class="search-form">
      @if($category)<input type="hidden" name="category" value="{{ $category }}">@endif
      @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
      <div class="search-input">
        <span class="search-input__icon material-symbols-outlined" aria-hidden="true">search</span>
        <input type="search" name="q" value="{{ $q ?? '' }}" placeholder="{{ $labels['search'] }}">
        @if(!empty($q))
          <a href="{{ route('legislation', array_filter(['category' => $category, 'status' => $status])) }}" class="search-input__clear" aria-label="Clear">
            <span class="material-symbols-outlined">close</span>
          </a>
        @endif
      </div>
    </form>

    {{-- Чипы категории --}}
    <div class="filter-chips">
      <a href="{{ route('legislation', array_filter(['status' => $status, 'q' => $q])) }}"
         class="filter-chip {{ !$category ? 'is-active' : '' }}">{{ $labels['all'] }}</a>
      @foreach ($categories as $key => $label)
        <a href="{{ route('legislation', array_filter(['category' => $key, 'status' => $status, 'q' => $q])) }}"
           class="filter-chip {{ $category === $key ? 'is-active' : '' }}">{{ $label }}</a>
      @endforeach
    </div>

    @if($acts->isEmpty())
      <div style="text-align:center; padding:3rem 1rem; color:rgb(var(--on-surface-mut));">
        <span class="material-symbols-outlined" style="font-size:3rem; opacity:.4;">search_off</span>
        <p style="margin:.5rem 0 0;">{{ $labels['empty'] }}</p>
      </div>
    @endif

    <div class="legal-list">
      @foreach ($acts as $act)
        <a href="{{ route('legislation.show', $act->slug) }}" class="legal-card">
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
          <h3 class="legal-card__title">{{ $tr($act, 'title') }}</h3>
          @if($summary = $tr($act, 'summary'))
            <p class="legal-card__summary">{{ Str::limit(strip_tags($summary), 220) }}</p>
          @endif
          <span class="legal-card__cta">{{ $labels['open'] }} →</span>
        </a>
      @endforeach
    </div>

    <div style="margin-top:3rem;">{{ $acts->links('vendor.pagination.meyos') }}</div>
  </div>
</section>

@endsection

@push('head')
<style>
.legal-list { display: grid; gap: 1.25rem; max-width: 60rem; margin: 0 auto; }
.legal-card {
  display: block; padding: 1.5rem 1.75rem; border: 1px solid rgb(var(--outline));
  border-radius: var(--radius-lg); background: rgb(var(--surface));
  text-decoration: none; color: inherit; transition: border-color .2s, transform .2s, box-shadow .2s;
}
.legal-card:hover { border-color: rgb(var(--primary)); transform: translateY(-2px);
  box-shadow: 0 8px 20px rgb(0 0 0 / .06); }
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
.legal-card__summary { color: rgb(var(--on-surface-mut)); font-size: .9rem; line-height: 1.55;
  margin: 0 0 .75rem; }
.legal-card__cta { color: rgb(var(--primary)); font-weight: 600; font-size: .9rem; }
@media (max-width: 639px) {
  .legal-card { padding: 1.15rem 1.25rem; }
  .legal-card__date { margin-left: 0; width: 100%; }
}
</style>
@endpush
