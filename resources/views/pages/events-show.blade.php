@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
@endphp

@section('title', $tr($event, 'title'))

@section('content')

<section style="padding:5rem 1.5rem 3rem;">
  <div class="container" style="max-width:880px;">
    <a href="{{ route('events') }}" style="display:inline-flex; gap:.5rem; color:rgb(var(--primary)); text-decoration:none; font-weight:600; margin-bottom:2rem;">
      <span class="material-symbols-outlined">arrow_back</span>
      @switch($cur) @case('uz') Tadbirlar @break @case('en') Events @break @default Все мероприятия @endswitch
    </a>

    <span class="chip">{{ \App\Models\Event::CATEGORIES[$event->category] ?? '' }}</span>
    <h1 style="font-size:clamp(1.75rem, 4vw, 3rem); margin:1rem 0 1.5rem;">{{ $tr($event, 'title') }}</h1>

    <div style="display:flex; gap:2rem; flex-wrap:wrap; padding:1.5rem; background:rgb(var(--surface-alt)); border-radius:var(--radius-lg); margin-bottom:2rem;">
      <div><strong>@switch($cur) @case('uz') Sana @break @case('en') Date @break @default Дата @endswitch:</strong> {{ $event->event_date->format('d.m.Y') }}</div>
      @if($tr($event, 'location'))<div><strong>@switch($cur) @case('uz') Joy @break @case('en') Location @break @default Локация @endswitch:</strong> {{ $tr($event, 'location') }}</div>@endif
      @if($event->expected_attendees)<div><strong>@switch($cur) @case('uz') Ishtirokchilar @break @case('en') Attendees @break @default Участников @endswitch:</strong> {{ $event->expected_attendees }}</div>@endif
    </div>

    @if($tr($event, 'description'))<div style="font-size:1.05rem; line-height:1.7;">{!! nl2br(e($tr($event, 'description'))) !!}</div>@endif

    <form action="{{ route('submit.event', $event->slug) }}" method="POST" class="form" style="margin-top:3rem;">
      @csrf
      <h3 style="margin:0 0 1rem;">@switch($cur) @case('uz') Roʻyxatdan oʻtish @break @case('en') Registration @break @default Регистрация на мероприятие @endswitch</h3>
      <div style="display:grid; gap:1rem; grid-template-columns:1fr 1fr;">
        <label>@switch($cur) @case('uz') Kompaniya @break @case('en') Company @break @default Компания @endswitch <input type="text" name="company" required></label>
        <label>@switch($cur) @case('uz') Ism @break @case('en') Name @break @default Имя @endswitch <input type="text" name="name" required></label>
        <label>Email <input type="email" name="email" required></label>
        <label>@switch($cur) @case('uz') Telefon @break @case('en') Phone @break @default Телефон @endswitch <input type="tel" name="phone" required></label>
        <label style="grid-column:1/-1;">@switch($cur) @case('uz') Ishtirokchilar soni @break @case('en') Attendees count @break @default Количество участников @endswitch <input type="number" name="attendees_count" value="1" min="1" max="20"></label>
      </div>
      <button type="submit" class="btn btn-primary btn-lg mt-4">@switch($cur) @case('uz') Yuborish @break @case('en') Send @break @default Подать заявку @endswitch</button>
    </form>
  </div>
</section>

@endsection
