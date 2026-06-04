@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
@endphp

@section('content')

<section class="hero" style="padding:5rem 1.5rem;">
  <div class="hero__inner" style="grid-template-columns:1fr;">
    <div style="max-width:50rem;">
      <span class="tag tag-on-dark"><span class="tag-dot"></span>@switch($cur) @case('uz') Yangiliklar @break @case('en') News @break @default Новости @endswitch</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">@switch($cur) @case('uz') Mebel industriyasidagi voqealar @break @case('en') Furniture industry news @break @default Что происходит в мебельной индустрии Узбекистана @endswitch</h1>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div style="display:flex; flex-wrap:wrap; gap:.5rem; justify-content:center; margin-bottom:3rem;">
      <a href="{{ route('news') }}" class="filter-chip {{ !$category ? 'is-active' : '' }}">@switch($cur) @case('uz') Barchasi @break @case('en') All @break @default Все @endswitch</a>
      @foreach ($categories as $key => $label)
        <a href="{{ route('news', ['category' => $key]) }}" class="filter-chip {{ $category === $key ? 'is-active' : '' }}">{{ $label }}</a>
      @endforeach
    </div>

    @if ($featured)
      <a href="{{ route('news.show', $featured->slug) }}" style="text-decoration:none; color:inherit; display:block; border:1px solid rgb(var(--outline)); border-radius:var(--radius-lg); overflow:hidden; margin-bottom:2rem; max-width:920px; margin-left:auto; margin-right:auto;">
        <div style="display:grid; grid-template-columns:1fr 1.2fr; gap:0;" class="featured-grid">
          <div style="aspect-ratio:4/3; background:rgb(var(--surface-deep));">
            @if($featured->cover_image)<img src="{{ asset('storage/' . $featured->cover_image) }}" style="width:100%; height:100%; object-fit:cover;">@endif
          </div>
          <div style="padding:1.25rem 1.5rem;">
            <span class="chip">{{ \App\Models\News::CATEGORIES[$featured->category] ?? '' }}</span>
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

    <div class="grid grid-3">
      @foreach ($news as $item)
        <a href="{{ route('news.show', $item->slug) }}" style="text-decoration:none; color:inherit; display:block; border:1px solid rgb(var(--outline)); border-radius:var(--radius-lg); overflow:hidden;">
          <div style="aspect-ratio:16/9; background:rgb(var(--surface-deep));">
            @if($item->cover_image ? asset("storage/" . $item->cover_image) : null)<img src="{{ $item->cover_image ? asset("storage/" . $item->cover_image) : null }}" style="width:100%; height:100%; object-fit:cover;">@endif
          </div>
          <div style="padding:1.5rem;">
            <span class="chip">{{ \App\Models\News::CATEGORIES[$item->category] ?? '' }}</span>
            <h3 class="mt-3" style="font-size:1.15rem; line-height:1.3;">{{ $tr($item, 'title') }}</h3>
            <div class="news-date mt-4" style="margin-top:1rem; font-size:.75rem; color:rgb(var(--on-surface-mut)); letter-spacing:.1em; text-transform:uppercase;">{{ $item->published_at->format('d.m.Y') }}</div>
          </div>
        </a>
      @endforeach
    </div>

    <div style="margin-top:3rem;">{{ $news->links() }}</div>
  </div>
</section>

@endsection
