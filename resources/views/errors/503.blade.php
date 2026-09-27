@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $labels = [
        'title' => ['ru' => 'Сайт временно недоступен', 'uz' => 'Sayt vaqtincha ishlamayapti', 'en' => 'Site temporarily unavailable'][$cur],
        'lead'  => ['ru' => 'Проводим технические работы. Скоро всё вернётся в норму.', 'uz' => 'Texnik ishlar olib borilmoqda. Tez orada hammasi normallashadi.', 'en' => 'We are doing maintenance. Everything will be back shortly.'][$cur],
        'retry' => ['ru' => 'Обновить страницу', 'uz' => 'Sahifani yangilash', 'en' => 'Refresh page'][$cur],
    ];
@endphp

@section('title', $labels['title'] . ' — MEYOS')
@section('description', $labels['lead'])

@section('content')
<section style="padding:6rem 1.5rem; min-height:60vh; display:flex; align-items:center; justify-content:center;">
  <div class="container" style="max-width:640px; text-align:center;">
    <div style="font-family:var(--font-display,var(--font-head,inherit)); font-size:clamp(4rem, 12vw, 7rem); line-height:1; margin-bottom:1rem; color:rgb(var(--primary)); font-weight:900; letter-spacing:-.05em;">503</div>
    <h1 style="font-size:clamp(1.5rem, 4vw, 2.25rem); margin:0 0 1rem;">{{ $labels['title'] }}</h1>
    <p class="lead" style="margin:0 0 2rem;">{{ $labels['lead'] }}</p>
    <a href="{{ url()->current() }}" class="btn btn-primary btn-lg">
      <span class="material-symbols-outlined" style="font-size:1.1rem; vertical-align:-3px;">refresh</span>
      {{ $labels['retry'] }}
    </a>
  </div>
</section>
@endsection
