@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
    $coverUrl = $news->cover_image ? asset('storage/' . $news->cover_image) : null;

    // SEO: сначала per-record, потом fallback на заголовок/превью
    $newsTitle    = $tr($news, 'title');
    $seoTitle     = $tr($news, 'seo_title') ?: $newsTitle;
    $previewText  = $tr($news, 'preview') ?: strip_tags($tr($news, 'content'));
    $seoDesc      = $tr($news, 'seo_description') ?: $previewText;
    $seoDesc      = mb_substr(trim($seoDesc), 0, 200);
    $ogImage      = $news->seo_image ? asset('storage/' . $news->seo_image) : $coverUrl;

    // CTA-кнопка
    $ctaText = $tr($news, 'cta_text');
    $ctaUrl  = $news->cta_resolved_url; // accessor

    // Галерея
    $gallery = is_array($news->gallery_images) ? $news->gallery_images : [];
@endphp

@section('title', $seoTitle)
@section('description', $seoDesc)
@section('og_type', 'article')
@if($ogImage)@section('og_image', $ogImage)@endif

@push('head')
{{-- Schema.org Article --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'NewsArticle',
    'headline' => $newsTitle,
    'description' => $seoDesc,
    'image'    => $ogImage ? [$ogImage] : [],
    'datePublished' => $news->published_at?->toIso8601String(),
    'dateModified'  => $news->updated_at?->toIso8601String(),
    'author'   => ['@type' => 'Organization', 'name' => \App\Models\Setting::get('site_name', 'MEYOS')],
    'publisher' => [
        '@type' => 'Organization',
        'name'  => \App\Models\Setting::get('site_name', 'MEYOS'),
        'logo'  => ['@type' => 'ImageObject', 'url' => \App\Models\Setting::logoUrl() ?: url('/favicon.ico')],
    ],
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
    'articleSection' => \App\Models\News::allCategories()[$news->category] ?? null,
    'inLanguage' => $cur,
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

{{-- Стили слайдера галереи --}}
<style>
.gallery-slider { position:relative; margin:2.5rem 0; }
.gallery-track {
  display:flex; gap:.85rem; overflow-x:auto; scroll-snap-type:x mandatory;
  scrollbar-width:thin; padding-bottom:.85rem; -webkit-overflow-scrolling:touch;
}
.gallery-track::-webkit-scrollbar { height:6px; }
.gallery-track::-webkit-scrollbar-thumb { background:rgb(var(--outline)); border-radius:3px; }
.gallery-slide {
  flex:0 0 auto; width:min(80%, 480px); aspect-ratio:16/10;
  scroll-snap-align:start; border-radius:var(--radius-md); overflow:hidden;
  background:rgb(var(--surface-deep)); cursor:zoom-in; position:relative;
}
.gallery-slide img { width:100%; height:100%; object-fit:cover; transition:transform .3s; }
.gallery-slide:hover img { transform:scale(1.03); }
.gallery-nav {
  position:absolute; top:50%; transform:translateY(-50%);
  width:2.5rem; height:2.5rem; border-radius:50%; border:0;
  background:rgb(var(--surface)); box-shadow:0 4px 16px rgba(0,0,0,.18);
  cursor:pointer; display:flex; align-items:center; justify-content:center;
  z-index:2; transition:transform .15s;
}
.gallery-nav:hover { transform:translateY(-50%) scale(1.08); }
.gallery-nav.prev { left:-.25rem; }
.gallery-nav.next { right:-.25rem; }
.gallery-lightbox {
  position:fixed; inset:0; background:rgba(0,0,0,.92); z-index:300;
  display:none; align-items:center; justify-content:center; padding:1rem;
  cursor:zoom-out;
}
.gallery-lightbox.is-open { display:flex; }
.gallery-lightbox img { max-width:100%; max-height:100%; border-radius:.5rem; }

.cta-news-button {
  display:inline-flex; align-items:center; gap:.5rem; padding:1rem 2rem;
  background:rgb(var(--primary)); color:#fff !important; border-radius:var(--radius-md);
  font-weight:700; font-size:1rem; text-decoration:none;
  box-shadow:0 8px 24px rgba(0,0,0,.15);
  transition:transform .15s, box-shadow .15s;
}
.cta-news-button:hover { transform:translateY(-2px); box-shadow:0 12px 30px rgba(0,0,0,.22); }
</style>
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

    <span class="chip">{{ \App\Models\News::allCategories()[$news->category] ?? '' }}</span>
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

    {{-- ============ Галерея-слайдер после текста ============ --}}
    @if (!empty($gallery))
      <div class="gallery-slider" id="news-gallery">
        <h3 style="font-size:1.1rem; margin:0 0 1rem;">
          @switch($cur) @case('uz') Foto-galereya @break @case('en') Photo gallery @break @default Фотогалерея @endswitch
        </h3>
        <button type="button" class="gallery-nav prev" aria-label="←" onclick="document.getElementById('gallery-track').scrollBy({left:-480, behavior:'smooth'})">
          <span class="material-symbols-outlined">chevron_left</span>
        </button>
        <button type="button" class="gallery-nav next" aria-label="→" onclick="document.getElementById('gallery-track').scrollBy({left:480, behavior:'smooth'})">
          <span class="material-symbols-outlined">chevron_right</span>
        </button>
        <div class="gallery-track" id="gallery-track">
          @foreach ($gallery as $img)
            <div class="gallery-slide" data-img="{{ asset('storage/' . $img) }}">
              <img src="{{ asset('storage/' . $img) }}" alt="{{ $newsTitle }}"
                   loading="lazy" decoding="async">
            </div>
          @endforeach
        </div>
      </div>

      {{-- Lightbox для просмотра фото в полноэкранном --}}
      <div class="gallery-lightbox" id="lightbox" role="dialog" aria-modal="true">
        <img src="" id="lightbox-img" alt="">
      </div>
    @endif

    {{-- ============ CTA-кнопка ============ --}}
    @if ($ctaUrl && $ctaText)
      <div style="text-align:center; margin:3rem 0 1rem;">
        <a href="{{ $ctaUrl }}" class="cta-news-button"
           @if(!$news->cta_event_id && !str_starts_with($ctaUrl, url('/'))) target="_blank" rel="noopener" @endif>
          {{ $ctaText }}
          <span class="material-symbols-outlined">arrow_forward</span>
        </a>
      </div>
    @endif

  </div>
</section>

{{-- ============ Связанные новости ============ --}}
@if ($related->count())
<section class="section-alt">
  <div class="container">
    <div class="section-head">
      <h2>@switch($cur) @case('uz') Yana shu turkumda @break @case('en') More in this category @break @default Ещё в категории «{{ \App\Models\News::allCategories()[$news->category] ?? '' }}» @endswitch</h2>
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
          <span class="chip">{{ \App\Models\News::allCategories()[$item->category] ?? '' }}</span>
          <h3 class="mt-3" style="font-size:1.1rem;">{{ $tr($item, 'title') }}</h3>
          <div data-card-footer style="font-size:.75rem; color:rgb(var(--on-surface-mut)); margin-top:1rem;">{{ $item->published_at->format('d.m.Y') }}</div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

@push('scripts')
<script>
// Lightbox для галереи
(function() {
  const slides = document.querySelectorAll('.gallery-slide');
  const lb     = document.getElementById('lightbox');
  const lbImg  = document.getElementById('lightbox-img');
  if (!lb) return;
  slides.forEach(s => s.addEventListener('click', () => {
    lbImg.src = s.dataset.img;
    lb.classList.add('is-open');
  }));
  lb.addEventListener('click', () => lb.classList.remove('is-open'));
  document.addEventListener('keydown', e => { if (e.key === 'Escape') lb.classList.remove('is-open'); });
})();
</script>
@endpush

@endsection
