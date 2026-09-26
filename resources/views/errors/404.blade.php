@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $labels = [
        'title'  => ['ru' => 'Страница не найдена', 'uz' => 'Sahifa topilmadi', 'en' => 'Page not found'][$cur],
        'lead'   => ['ru' => 'Такой страницы нет — возможно, ссылка устарела или вы ошиблись при вводе адреса.', 'uz' => 'Bunday sahifa yoʻq — ehtimol havola eskirgan yoki manzilda xato bor.', 'en' => 'This page does not exist. The link may be outdated or contain a typo.'][$cur],
        'home'   => ['ru' => 'На главную', 'uz' => 'Bosh sahifa', 'en' => 'Home'][$cur],
        'quick'  => ['ru' => 'Быстрые переходы', 'uz' => 'Tez havolalar', 'en' => 'Quick links'][$cur],
        'search' => ['ru' => 'Может, вы искали одного из наших партнёров?', 'uz' => 'Balki hamkorlarimizdan birini qidiryapsizmi?', 'en' => 'Maybe you were looking for one of our partners?'][$cur],
        'openReg' => ['ru' => 'Открыть реестр партнёров', 'uz' => 'Hamkorlar reyestrini ochish', 'en' => 'Open partner registry'][$cur],
    ];
    $nav = [
        'about'     => ['ru' => 'О компании',    'uz' => 'Kompaniya haqida', 'en' => 'About'],
        'residency' => ['ru' => 'Резидентство',  'uz' => 'Rezidentlik',      'en' => 'Residency'],
        'programs'  => ['ru' => 'Программы',     'uz' => 'Dasturlar',        'en' => 'Programs'],
        'partners'  => ['ru' => 'Партнёры',      'uz' => 'Hamkorlar',        'en' => 'Partners'],
        'events'    => ['ru' => 'Мероприятия',   'uz' => 'Tadbirlar',        'en' => 'Events'],
        'news'      => ['ru' => 'Новости',       'uz' => 'Yangiliklar',      'en' => 'News'],
        'contacts'  => ['ru' => 'Контакты',      'uz' => 'Kontaktlar',       'en' => 'Contacts'],
    ];
@endphp

@section('title', $labels['title'] . ' — MEYOS')
@section('description', $labels['lead'])

@section('content')
<section style="padding:6rem 1.5rem; min-height:60vh; display:flex; align-items:center; justify-content:center;">
  <div class="container" style="max-width:720px; text-align:center;">

    <div style="font-family:var(--font-display,var(--font-head,inherit)); font-size:clamp(6rem, 20vw, 12rem); font-weight:900; color:rgb(var(--primary)); line-height:1; margin-bottom:.5rem; letter-spacing:-.05em;">
      404
    </div>

    <h1 style="font-size:clamp(1.5rem, 4vw, 2.25rem); margin:0 0 1rem;">{{ $labels['title'] }}</h1>
    <p class="lead" style="margin:0 0 2rem;">{{ $labels['lead'] }}</p>

    <div style="display:flex; gap:.75rem; justify-content:center; flex-wrap:wrap; margin-bottom:3rem;">
      <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
        <span class="material-symbols-outlined" style="font-size:1.1rem; vertical-align:-3px;">home</span>
        {{ $labels['home'] }}
      </a>
      <a href="{{ route('partners') }}" class="btn btn-outline">
        <span class="material-symbols-outlined" style="font-size:1.1rem; vertical-align:-3px;">groups</span>
        {{ $labels['openReg'] }}
      </a>
    </div>

    <div style="padding:2rem 1.5rem; background:rgb(var(--surface-deep)); border-radius:var(--radius-lg); text-align:left;">
      <h3 style="margin:0 0 1rem; font-size:1rem; color:rgb(var(--on-surface-mut)); text-transform:uppercase; letter-spacing:.05em;">
        {{ $labels['quick'] }}
      </h3>
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:.5rem;">
        @foreach($nav as $route => $labs)
          <a href="{{ route($route) }}"
             style="display:flex; align-items:center; justify-content:space-between; padding:.85rem 1rem; background:rgb(var(--surface)); border-radius:var(--radius-sm); color:rgb(var(--on-surface)); text-decoration:none; font-weight:500; transition:transform .15s, background .15s;"
             onmouseover="this.style.transform='translateX(3px)'; this.style.background='rgb(var(--primary) / .08)';"
             onmouseout="this.style.transform=''; this.style.background='rgb(var(--surface))';">
            {{ $labs[$cur] ?? $labs['ru'] }}
            <span class="material-symbols-outlined" style="font-size:1rem; color:rgb(var(--primary));">arrow_forward</span>
          </a>
        @endforeach
      </div>

      <p style="margin:1.5rem 0 0; color:rgb(var(--on-surface-mut)); font-size:.85rem; text-align:center;">
        {{ $labels['search'] }}
      </p>
    </div>

  </div>
</section>
@endsection
