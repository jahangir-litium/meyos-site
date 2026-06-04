@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
@endphp

@section('content')

<section class="hero" style="padding:5rem 1.5rem;">
  <div class="hero__inner" style="grid-template-columns:1fr;">
    <div style="max-width:50rem;">
      <span class="tag tag-on-dark"><span class="tag-dot"></span>@switch($cur) @case('uz') Kompaniya haqida @break @case('en') About @break @default О компании @endswitch</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">{{ $tr($page, 'hero_h1', 'Создаём инфраструктуру мебельной индустрии Узбекистана') }}</h1>
      <p class="lead">{{ $tr($page, 'hero_lead', 'MEYOS — некоммерческая ассоциация, объединяющая производителей, дизайнеров и поставщиков мебельной отрасли.') }}</p>
    </div>
  </div>
</section>

<!-- TIMELINE -->
@if ($timeline->count())
<section>
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Tarix @break @case('en') History @break @default История @endswitch</span>
      <h2>@switch($cur) @case('uz') Assotsiatsiya yoʻli @break @case('en') Path of the association @break @default Путь ассоциации @endswitch</h2>
    </div>
    <div class="timeline">
      @foreach ($timeline as $i => $item)
        <div class="timeline__item" style="--stagger:{{ $i * 0.08 }}s;">
          <span class="timeline__dot"></span>
          <span class="timeline__year">{{ $item->year }}</span>
          <h3 class="timeline__title">{{ $tr($item, 'title') }}</h3>
          <p class="timeline__text">{{ $tr($item, 'description') }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

@push('head')
<style>
/* Микро-анимация: каждый timeline__item появляется со ступенчатой задержкой
 * (stagger). Триггер — общий IntersectionObserver в layout, который вешает
 * класс .is-visible на элементы с классом .fade-in-up. */
.timeline__item.fade-in-up { transition-delay: var(--stagger, 0s); }
.timeline__dot { transition: transform .4s ease var(--stagger, 0s); }
.timeline__item.is-visible .timeline__dot { animation: dotPulse 1.6s ease-out var(--stagger, 0s); }
@keyframes dotPulse {
  0%   { box-shadow: 0 0 0 0 rgb(var(--primary) / .55); }
  70%  { box-shadow: 0 0 0 14px rgb(var(--primary) / 0); }
  100% { box-shadow: 0 0 0 0 rgb(var(--primary) / 0); }
}
.timeline__year { transition: color .3s ease var(--stagger, 0s), letter-spacing .3s ease var(--stagger, 0s); }
.timeline__item.is-visible .timeline__year { letter-spacing: -.01em; }
</style>
@endpush
@endif

<!-- TEAM -->
@if ($team->count())
<section class="section-alt">
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Direktorlar kengashi @break @case('en') Board of Directors @break @default Совет директоров @endswitch</span>
      <h2>@switch($cur) @case('uz') Sohani boshqaradigan jamoa @break @case('en') The team @break @default Команда, которая ведёт индустрию @endswitch</h2>
    </div>
    <div class="grid grid-4">
      @foreach ($team as $member)
        <div class="card" style="text-align:center;">
          @if($member->photo_image ? asset("storage/" . $member->photo_image) : null)
            <div style="width:100px; height:100px; border-radius:9999px; margin:0 auto 1rem; overflow:hidden;"><img src="{{ $member->photo_image ? asset("storage/" . $member->photo_image) : null }}" style="width:100%; height:100%; object-fit:cover;"></div>
          @else
            <div style="width:100px; height:100px; border-radius:9999px; background:rgb(var(--primary-soft)); margin:0 auto 1rem; display:flex; align-items:center; justify-content:center; font-family:var(--font-head); font-size:2rem; font-weight:800; color:rgb(var(--primary));">{{ $member->initials }}</div>
          @endif
          <h3 style="font-size:1.05rem; margin:0 0 .25rem;">{{ $tr($member, 'name') }}</h3>
          <p class="text-mut" style="font-size:.85rem; margin:0;">{{ $tr($member, 'role') }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- CERTIFICATIONS -->
@if ($certifications->count())
<section>
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Sertifikatlar @break @case('en') Certificates @break @default Сертификаты и соглашения @endswitch</span>
      <h2>@switch($cur) @case('uz') Rasmiy maqom @break @case('en') Official status @break @default Официальный статус и признание @endswitch</h2>
    </div>
    <div class="grid grid-3">
      @foreach ($certifications as $cert)
        <div class="card">
          <span class="icon-box"><span class="material-symbols-outlined">{{ $cert->icon }}</span></span>
          <h3 class="mt-4" style="font-size:1.15rem;">{{ $tr($cert, 'title') }}</h3>
          <p class="text-mut" style="font-size:.9rem; line-height:1.55;">{{ $tr($cert, 'description') }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection
