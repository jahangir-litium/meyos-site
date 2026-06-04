@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
    $coverUrl = $news->cover_image ? asset('storage/' . $news->cover_image) : null;
    $newsTitle = $tr($news, 'title');
    $newsDesc  = $tr($news, 'preview') ?: strip_tags($tr($news, 'content'));
    $newsDesc  = mb_substr(trim($newsDesc), 0, 200);
@endphp

@section('title', $newsTitle)
@section('description', $newsDesc)
@section('og_type', 'article')
@if($coverUrl)@section('og_image', $coverUrl)@endif

@push('head')
{{-- Schema.org Article --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'NewsArticle',
    'headline' => $newsTitle,
    'description' => $newsDesc,
    'image'    => $coverUrl ? [$coverUrl] : [],
    'datePublished' => $news->published_at?->toIso8601String(),
    'dateModified'  => $news->updated_at?->toIso8601String(),
    'author'   => ['@type' => 'Organization', 'name' => \App\Models\Setting::get('site_name', 'MEYOS')],
    'publisher' => [
        '@type' => 'Organization',
        'name'  => \App\Models\Setting::get('site_name', 'MEYOS'),
        'logo'  => ['@type' => 'ImageObject', 'url' => \App\Models\Setting::logoUrl() ?: url('/favicon.ico')],
    ],
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- Schema.org Breadcrumbs --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => [
        ['@type'=>'ListItem','position'=>1,'name'=>'Главная','item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>'Новости','item'=>route('news')],
        ['@type'=>'ListItem','position'=>3,'name'=>$newsTitle,'item'=>url()->current()],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

<section style="padding: 5rem 1.5rem 3rem;">
  <div class="container" style="max-width: 760px;">

    {{-- ============ Хлебные крошки ============ --}}
    <nav aria-label="Breadcrumb" style="margin-bottom:1.5rem;">
      <ol style="display:flex; gap:.4rem; padding:0; margin:0; list-style:none; font-size:.85rem; color:rgb(var(--on-surface-mut)); flex-wrap:wrap;">
        <li><a href="{{ url('/') }}" style="color:inherit; text-decoration:none;">@switch($cur) @case('uz') Bosh sahifa @break @case('en') Home @break @default Главная @endswitch</a></li>
        <li aria-hidden="true">›</li>
        <li><a href="{{ route('news') }}" style="color:inherit; text-decoration:none;">@switch($cur) @case('uz') Yangiliklar @break @case('en') News @break @default Новости @endswitch</a></li>
        <li aria-hidden="true">›</li>
        <li aria-current="page" style="color:rgb(var(--on-surface)); max-width:30ch; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $newsTitle }}</li>
      </ol>
    </nav>

    <span class="chip">{{ \App\Models\News::CATEGORIES[$news->category] ?? '' }}</span>
    <h1 style="font-size:clamp(1.75rem, 4vw, 2.75rem); margin:1rem 0 1.5rem; line-height:1.2;">{{ $newsTitle }}</h1>
    <div style="font-size:.85rem; color:rgb(var(--on-surface-mut)); letter-spacing:.1em; text-transform:uppercase; margin-bottom:2rem;">{{ $news->published_at->format('d.m.Y') }}</div>

    @if($coverUrl)
      <div style="aspect-ratio:16/9; overflow:hidden; border-radius:var(--radius-lg); margin-bottom:2rem;">
        <img src="{{ $coverUrl }}" alt="{{ $tr($news, 'image_alt', $newsTitle) }}"
             loading="lazy" decoding="async" class="lazy-img"
             style="width:100%; height:100%; object-fit:cover;">
      </div>
    @endif

    <div style="font-size:1.05rem; line-height:1.7;">
      {!! $tr($news, 'content') !!}
    </div>
  </div>
</section>

{{-- ============ Связанные новости ============ --}}
@if ($related->count())
<section class="section-alt">
  <div class="container">
    <div class="section-head">
      <h2>@switch($cur) @case('uz') Yana shu turkumda @break @case('en') More in this category @break @default Ещё в категории «{{ \App\Models\News::CATEGORIES[$news->category] ?? '' }}» @endswitch</h2>
    </div>
    <div class="grid grid-3">
      @foreach ($related as $item)
        <a href="{{ route('news.show', $item->slug) }}" style="text-decoration:none; color:inherit;" class="card">
          @if($item->cover_image)
            <div style="aspect-ratio:16/9; background:rgb(var(--surface-deep)); border-radius:var(--radius-md); overflow:hidden; margin-bottom:1rem;">
              <img src="{{ asset('storage/' . $item->cover_image) }}" alt="{{ $tr($item, 'image_alt', $tr($item, 'title')) }}"
                   loading="lazy" decoding="async" class="lazy-img"
                   style="width:100%; height:100%; object-fit:cover;">
            </div>
          @endif
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
