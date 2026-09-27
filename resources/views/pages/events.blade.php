@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
@endphp

@section('content')

<div class="container" style="padding-top:1.25rem;">
  @include('partials.breadcrumbs', ['items' => [
    ['label' => ['ru' => 'Мероприятия', 'uz' => 'Tadbirlar', 'en' => 'Events'][$cur], 'url' => null],
  ]])
</div>

<section class="hero" style="padding:5rem 1.5rem;">
  <div class="hero__inner" style="grid-template-columns:1fr;">
    <div style="max-width:50rem;">
      <span class="tag tag-on-dark"><span class="tag-dot"></span>@switch($cur) @case('uz') Tadbirlar @break @case('en') Events @break @default Афиша MEYOS @endswitch</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">@switch($cur) @case('uz') Forumlar, koʻrgazmalar, uchrashuvlar @break @case('en') Forums, exhibitions, meetings @break @default Форумы, выставки и деловые встречи @endswitch</h1>
    </div>
  </div>
</section>

@if ($featured)
<section>
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Asosiy tadbir @break @case('en') Main event @break @default Главное событие года @endswitch</span>
      <h2>{{ $tr($featured, 'title') }}</h2>
    </div>
    <div class="case">
      <div class="case__img">
        @if($featured->cover_image ? asset("storage/" . $featured->cover_image) : null)<img src="{{ $featured->cover_image ? asset("storage/" . $featured->cover_image) : null }}" style="width:100%; height:100%; object-fit:cover;">@else<img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=900" style="width:100%; height:100%; object-fit:cover;">@endif
      </div>
      <div>
        <span class="case__num">{{ $featured->event_date->format('d.m.Y') }} · {{ $tr($featured, 'location') }}</span>
        <h3 class="case__title">{{ $tr($featured, 'title') }}</h3>
        <p class="text-mut" style="line-height:1.65;">{{ $tr($featured, 'description') }}</p>
        <a href="{{ route('events.show', $featured->slug) }}" class="btn btn-primary mt-6" style="margin-top:1.5rem;">@switch($cur) @case('uz') Roʻyxatdan oʻtish @break @case('en') Register @break @default Зарегистрироваться @endswitch</a>
      </div>
    </div>
  </div>
</section>
@endif

@if ($upcoming->count())
<section class="section-alt">
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Yaqinlashayotgan @break @case('en') Upcoming @break @default Ближайшие мероприятия @endswitch</span>
      <h2>@switch($cur) @case('uz') Yaqin oylar afishasi @break @case('en') Upcoming months @break @default Афиша на ближайшие месяцы @endswitch</h2>
    </div>
    <div style="display:grid; gap:1rem;">
      @foreach ($upcoming as $event)
        <div class="event-card">
          <div class="event-card__date">
            <div class="event-card__day">{{ $event->event_date->format('d') }}</div>
            <div class="event-card__month">{{ $event->event_date->format('M') }}</div>
            <div class="event-card__year">{{ $event->event_date->format('Y') }}</div>
          </div>
          <div class="event-card__body">
            <span class="chip">{{ \App\Models\Event::allCategories()[$event->category] ?? '' }}</span>
            <h3 class="event-card__title">{{ $tr($event, 'title') }}</h3>
            <p class="event-card__preview">{{ $tr($event, 'preview') }}</p>
            <div class="event-card__meta">
              <span>{{ $event->event_date->format('d.m.Y') }}</span>
              <span>{{ $tr($event, 'location') }}</span>
            </div>
          </div>
          <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary event-card__cta">@switch($cur) @case('uz') Ariza @break @case('en') Apply @break @default Подать заявку @endswitch</a>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection
