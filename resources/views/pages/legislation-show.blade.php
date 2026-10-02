@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
    $title = $tr($act, 'title');
    $summary = $tr($act, 'summary');
    $content = $tr($act, 'content');
    $categories = \App\Models\LegalAct::allCategories();
    $statuses   = \App\Models\LegalAct::allStatuses();

    $labels = [
        'home'      => ['ru' => 'Главная', 'uz' => 'Bosh sahifa', 'en' => 'Home'][$cur],
        'crumb'     => ['ru' => 'Законодательство', 'uz' => 'Qonunchilik', 'en' => 'Legislation'][$cur],
        'source'    => ['ru' => 'Открыть на источнике', 'uz' => 'Manbada ochish', 'en' => 'Open on source'][$cur],
        'pdf'       => ['ru' => 'Скачать PDF', 'uz' => 'PDF yuklab olish', 'en' => 'Download PDF'][$cur],
        'related'   => ['ru' => 'Другие акты в этой категории', 'uz' => 'Shu toifadagi boshqa hujjatlar', 'en' => 'More in this category'][$cur],
        'summary'   => ['ru' => 'Краткое описание', 'uz' => 'Qisqacha', 'en' => 'Summary'][$cur],
    ];

    $seoTitle = $tr($act, 'seo_title') ?: $title . ' — MEYOS';
    $seoDesc  = $tr($act, 'seo_description') ?: Str::limit(strip_tags($summary ?? $title), 200);
@endphp

@section('title', $seoTitle)
@section('description', $seoDesc)

@section('content')

<section style="padding:2rem 1.5rem 1.5rem;">
  <div class="container" style="max-width:60rem;">
    @include('partials.breadcrumbs', ['items' => [
      ['label' => $labels['crumb'], 'url' => route('legislation')],
      ['label' => $act->act_number ?: $title, 'url' => null],
    ]])

    <div class="legal-head">
      <div class="legal-head__meta">
        @if($act->act_number)<span class="legal-head__number">{{ $act->act_number }}</span>@endif
        <span class="legal-head__badge legal-head__badge--{{ $act->status }}">{{ $statuses[$act->status] ?? $act->status }}</span>
        <span class="legal-head__cat">{{ $categories[$act->category] ?? $act->category }}</span>
        @if($act->act_date)<span class="legal-head__date">{{ $act->act_date->format('d.m.Y') }}</span>@endif
      </div>
      <h1>{{ $title }}</h1>

      @if($summary)
        <div class="legal-head__summary">
          <strong>{{ $labels['summary'] }}:</strong>
          {!! nl2br(e($summary)) !!}
        </div>
      @endif

      <div class="legal-head__actions">
        @if($act->pdf_path)
          <a href="{{ $act->pdf_url }}" target="_blank" rel="noopener" class="btn btn-primary">
            <span class="material-symbols-outlined" style="font-size:1.1rem;vertical-align:-3px;">download</span>
            {{ $labels['pdf'] }}
          </a>
        @endif
        @if($act->source_url)
          <a href="{{ $act->source_url }}" target="_blank" rel="noopener" class="btn btn-outline">
            <span class="material-symbols-outlined" style="font-size:1.1rem;vertical-align:-3px;">open_in_new</span>
            {{ $labels['source'] }}
          </a>
        @endif
      </div>
    </div>

    @if($content)
      <article class="prose legal-content">
        {!! $content !!}
      </article>
    @endif

    @if($related->count())
      <section style="margin-top:3rem;">
        <h2 style="font-size:1.4rem;margin:0 0 1.25rem;">{{ $labels['related'] }}</h2>
        <div class="legal-list">
          @foreach($related as $r)
            <a href="{{ route('legislation.show', $r->slug) }}" class="legal-card">
              <div class="legal-card__head">
                @if($r->act_number)<span class="legal-card__number">{{ $r->act_number }}</span>@endif
                <span class="legal-card__badge legal-card__badge--{{ $r->status }}">{{ $statuses[$r->status] ?? $r->status }}</span>
                @if($r->act_date)<span class="legal-card__date">{{ $r->act_date->format('d.m.Y') }}</span>@endif
              </div>
              <h3 class="legal-card__title">{{ $tr($r, 'title') }}</h3>
            </a>
          @endforeach
        </div>
      </section>
    @endif
  </div>
</section>

@endsection

@push('head')
<style>
.legal-head { margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgb(var(--outline)); }
.legal-head__meta { display:flex; flex-wrap:wrap; gap:.6rem; align-items:center; margin-bottom:1rem; font-size:.82rem; }
.legal-head__number { font-family: var(--font-head); font-weight:800; color:rgb(var(--primary)); font-size:1rem; }
.legal-head__badge { padding:.2rem .6rem; border-radius: var(--radius-pill); font-weight:600;
  text-transform:uppercase; letter-spacing:.05em; font-size:.72rem; }
.legal-head__badge--active { background:#e3f3e7; color:#2a7c3b; }
.legal-head__badge--draft { background:#fff2d6; color:#a05a00; }
.legal-head__badge--repealed { background:rgb(var(--surface-deep)); color:rgb(var(--on-surface-mut)); }
.legal-head__cat { color: rgb(var(--on-surface-mut)); }
.legal-head__date { margin-left: auto; color: rgb(var(--on-surface-mut)); font-variant-numeric:tabular-nums; }
.legal-head h1 { font-size: clamp(1.6rem, 3.5vw, 2.4rem); line-height:1.2; margin:0 0 1rem; }
.legal-head__summary { background: rgb(var(--surface-deep)); padding:1rem 1.25rem;
  border-radius: var(--radius-md); margin-bottom: 1.25rem; line-height:1.55; font-size:.95rem;
  border-left: 3px solid rgb(var(--primary)); }
.legal-head__actions { display:flex; flex-wrap:wrap; gap:.6rem; }
.legal-content { line-height: 1.65; }
.legal-content p { margin: 0 0 1rem; }
.legal-content h2 { font-size:1.3rem; margin:2rem 0 .75rem; }
.legal-content h3 { font-size:1.1rem; margin:1.5rem 0 .5rem; }
.legal-content ul, .legal-content ol { margin: 0 0 1rem 1.5rem; }
.legal-content li { margin-bottom: .4rem; }

/* Переиспользуем .legal-list / .legal-card из страницы списка */
.legal-list { display:grid; gap:1rem; }
.legal-card { display:block; padding:1.15rem 1.4rem; border:1px solid rgb(var(--outline));
  border-radius: var(--radius-lg); background: rgb(var(--surface));
  text-decoration:none; color:inherit; transition: border-color .2s, transform .2s; }
.legal-card:hover { border-color: rgb(var(--primary)); transform: translateY(-2px); }
.legal-card__head { display:flex; flex-wrap:wrap; gap:.5rem; align-items:center;
  margin-bottom:.5rem; font-size:.75rem; }
.legal-card__number { font-family: var(--font-head); font-weight:800; color:rgb(var(--primary)); font-size:.9rem; }
.legal-card__badge { padding:.15rem .5rem; border-radius: var(--radius-pill);
  font-weight:600; text-transform:uppercase; letter-spacing:.05em; font-size:.68rem; }
.legal-card__badge--active { background:#e3f3e7; color:#2a7c3b; }
.legal-card__badge--draft { background:#fff2d6; color:#a05a00; }
.legal-card__badge--repealed { background:rgb(var(--surface-deep)); color:rgb(var(--on-surface-mut)); }
.legal-card__date { margin-left:auto; color:rgb(var(--on-surface-mut)); font-variant-numeric:tabular-nums; }
.legal-card__title { font-size:1rem; margin:0; line-height:1.35; }

@media (max-width:639px) {
  .legal-head__date { margin-left:0; width:100%; }
}
</style>
@endpush
