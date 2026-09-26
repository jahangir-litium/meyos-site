@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $labels = [
        'title' => ['ru' => 'Внутренняя ошибка сервера', 'uz' => 'Server ichki xatosi', 'en' => 'Internal server error'][$cur],
        'lead'  => ['ru' => 'Что-то пошло не так с нашей стороны. Мы уже получили уведомление и работаем над решением.', 'uz' => 'Bizning tarafimizda nimadir xato ketdi. Xabar oldik va yechim ustida ishlayapmiz.', 'en' => 'Something went wrong on our side. We have been notified and are working on a fix.'][$cur],
        'home'  => ['ru' => 'На главную', 'uz' => 'Bosh sahifa', 'en' => 'Home'][$cur],
        'retry' => ['ru' => 'Попробовать снова', 'uz' => 'Qayta urinib koʻring', 'en' => 'Try again'][$cur],
        'contact' => ['ru' => 'Если ошибка повторяется — напишите нам', 'uz' => 'Xato takrorlansa — bizga yozing', 'en' => 'If the error persists, please contact us'][$cur],
    ];
@endphp

@section('title', $labels['title'] . ' — MEYOS')
@section('description', $labels['lead'])

@section('content')
<section style="padding:6rem 1.5rem; min-height:60vh; display:flex; align-items:center; justify-content:center;">
  <div class="container" style="max-width:640px; text-align:center;">
    <div style="font-family:var(--font-display,var(--font-head,inherit)); font-size:clamp(6rem, 20vw, 12rem); font-weight:900; color:rgb(var(--primary)); line-height:1; margin-bottom:.5rem; letter-spacing:-.05em;">500</div>
    <h1 style="font-size:clamp(1.5rem, 4vw, 2.25rem); margin:0 0 1rem;">{{ $labels['title'] }}</h1>
    <p class="lead" style="margin:0 0 2rem;">{{ $labels['lead'] }}</p>
    <div style="display:flex; gap:.75rem; justify-content:center; flex-wrap:wrap; margin-bottom:2rem;">
      <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
        <span class="material-symbols-outlined" style="font-size:1.1rem; vertical-align:-3px;">home</span>
        {{ $labels['home'] }}
      </a>
      <a href="{{ url()->previous() ?: route('home') }}" class="btn btn-outline">
        <span class="material-symbols-outlined" style="font-size:1.1rem; vertical-align:-3px;">refresh</span>
        {{ $labels['retry'] }}
      </a>
    </div>
    <p style="color:rgb(var(--on-surface-mut)); font-size:.9rem;">
      {{ $labels['contact'] }}
      @if($mail = \App\Models\Setting::get('email'))
        — <a href="mailto:{{ $mail }}" style="color:rgb(var(--primary));">{{ $mail }}</a>
      @endif
    </p>
  </div>
</section>
@endsection
