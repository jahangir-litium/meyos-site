@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $labels = [
        'title' => ['ru' => 'Страница устарела', 'uz' => 'Sahifa eskirgan', 'en' => 'Page expired'][$cur],
        'lead'  => ['ru' => 'Форма или страница была открыта слишком давно. Обновите — и попробуйте снова.', 'uz' => 'Forma yoki sahifa juda uzoq vaqt ochiq turdi. Yangilang va qayta urinib koʻring.', 'en' => 'This form or page was open for too long. Please refresh and try again.'][$cur],
        'retry' => ['ru' => 'Обновить страницу', 'uz' => 'Sahifani yangilash', 'en' => 'Refresh page'][$cur],
        'home'  => ['ru' => 'На главную', 'uz' => 'Bosh sahifa', 'en' => 'Home'][$cur],
    ];
@endphp

@section('title', $labels['title'] . ' — MEYOS')
@section('description', $labels['lead'])

@section('content')
<section style="padding:6rem 1.5rem; min-height:60vh; display:flex; align-items:center; justify-content:center;">
  <div class="container" style="max-width:640px; text-align:center;">
    <div style="font-family:var(--font-display,var(--font-head,inherit)); font-size:clamp(4rem, 15vw, 8rem); font-weight:900; color:rgb(var(--primary)); line-height:1; margin-bottom:1rem; letter-spacing:-.05em;">419</div>
    <h1 style="font-size:clamp(1.5rem, 4vw, 2.25rem); margin:0 0 1rem;">{{ $labels['title'] }}</h1>
    <p class="lead" style="margin:0 0 2rem;">{{ $labels['lead'] }}</p>
    <div style="display:flex; gap:.75rem; justify-content:center; flex-wrap:wrap;">
      <a href="{{ url()->previous() ?: route('home') }}" class="btn btn-primary btn-lg">
        <span class="material-symbols-outlined" style="font-size:1.1rem; vertical-align:-3px;">refresh</span>
        {{ $labels['retry'] }}
      </a>
      <a href="{{ route('home') }}" class="btn btn-outline">{{ $labels['home'] }}</a>
    </div>
  </div>
</section>
@endsection
