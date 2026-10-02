@extends('layouts.app')

@php
    $cur = app()->getLocale();
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
      <span class="tag tag-on-dark"><span class="tag-dot"></span>{{ $labels['tag'] }}</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">{{ $labels['h1'] }}</h1>
      <p class="lead">{{ $labels['lead'] }}</p>
    </div>
  </div>
</section>

<section>
  <div class="container">
    {{-- Поиск --}}
    <form method="GET" action="{{ route('listings') }}" class="search-form">
      @if($type)<input type="hidden" name="type" value="{{ $type }}">@endif
      <div class="search-input">
        <span class="search-input__icon material-symbols-outlined" aria-hidden="true">search</span>
        <input type="search" name="q" value="{{ $q ?? '' }}" placeholder="{{ $labels['search'] }}">
        @if(!empty($q))
          <a href="{{ route('listings', array_filter(['type' => $type])) }}" class="search-input__clear" aria-label="Clear">
            <span class="material-symbols-outlined">close</span>
          </a>
        @endif
      </div>
    </form>

    {{-- Чипы фильтр --}}
    <div class="filter-chips">
      <a href="{{ route('listings', array_filter(['q' => $q])) }}"
         class="filter-chip {{ !$type ? 'is-active' : '' }}">{{ $labels['all'] }}</a>
      @foreach ($types as $key => $label)
        <a href="{{ route('listings', array_filter(['type' => $key, 'q' => $q])) }}"
           class="filter-chip {{ $type === $key ? 'is-active' : '' }}">{{ $label }}</a>
      @endforeach
    </div>

    @if($items->isEmpty())
      <div style="text-align:center; padding:3rem 1rem; color:rgb(var(--on-surface-mut));">
        <span class="material-symbols-outlined" style="font-size:3rem; opacity:.4;">search_off</span>
        <p style="margin:.5rem 0 0;">{{ $labels['empty'] }}</p>
      </div>
    @endif

    <div class="grid grid-3 listings-grid">
      @foreach ($items as $item)
        <a href="{{ $item->source_url }}" target="_blank" rel="noopener" class="listing-card" data-type="{{ $item->listing_type }}">
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

    <div style="margin-top:3rem;">{{ $items->links('vendor.pagination.meyos') }}</div>

    <p style="text-align:center; margin:2rem 0 0; color:rgb(var(--on-surface-mut)); font-size:.82rem;">
      {{ $labels['source'] }} {{ $items->first()?->fetched_at?->diffForHumans() ?? '' }}
    </p>
  </div>
</section>

@endsection
