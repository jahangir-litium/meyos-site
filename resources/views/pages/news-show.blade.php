@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
@endphp

@section('title', $tr($news, 'title'))

@section('content')

<section style="padding: 5rem 1.5rem 3rem;">
  <div class="container" style="max-width: 760px;">
    <a href="{{ route('news') }}" style="display:inline-flex; align-items:center; gap:.5rem; color:rgb(var(--primary)); text-decoration:none; font-weight:600; margin-bottom:2rem;">
      <span class="material-symbols-outlined">arrow_back</span>
      @switch($cur) @case('uz') Yangiliklar @break @case('en') News @break @default Все новости @endswitch
    </a>

    <span class="chip">{{ \App\Models\News::CATEGORIES[$news->category] ?? '' }}</span>
    <h1 style="font-size:clamp(1.75rem, 4vw, 2.75rem); margin:1rem 0 1.5rem; line-height:1.2;">{{ $tr($news, 'title') }}</h1>
    <div style="font-size:.85rem; color:rgb(var(--on-surface-mut)); letter-spacing:.1em; text-transform:uppercase; margin-bottom:2rem;">{{ $news->published_at->format('d.m.Y') }}</div>

    @if($news->getFirstMediaUrl('cover'))
      <div style="aspect-ratio:16/9; overflow:hidden; border-radius:var(--radius-lg); margin-bottom:2rem;">
        <img src="{{ $news->getFirstMediaUrl('cover') }}" style="width:100%; height:100%; object-fit:cover;">
      </div>
    @endif

    <div style="font-size:1.05rem; line-height:1.7;">
      {!! $tr($news, 'content') !!}
    </div>
  </div>
</section>

@if ($other->count())
<section class="section-alt">
  <div class="container">
    <div class="section-head">
      <h2>@switch($cur) @case('uz') Boshqa yangiliklar @break @case('en') Other news @break @default Другие новости @endswitch</h2>
    </div>
    <div class="grid grid-3">
      @foreach ($other as $item)
        <a href="{{ route('news.show', $item->slug) }}" style="text-decoration:none; color:inherit;" class="card">
          <span class="chip">{{ \App\Models\News::CATEGORIES[$item->category] ?? '' }}</span>
          <h3 class="mt-3" style="font-size:1.1rem;">{{ $tr($item, 'title') }}</h3>
          <div style="font-size:.75rem; color:rgb(var(--on-surface-mut)); margin-top:1rem;">{{ $item->published_at->format('d.m.Y') }}</div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection
