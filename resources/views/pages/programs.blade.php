@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
@endphp

@section('content')

<section class="hero" style="padding:5rem 1.5rem;">
  <div class="hero__inner" style="grid-template-columns:1fr;">
    <div style="max-width:50rem;">
      <span class="tag tag-on-dark"><span class="tag-dot"></span>@switch($cur) @case('uz') Assotsiatsiya dasturlari @break @case('en') Programs @break @default Программы ассоциации @endswitch</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">{{ $tr($page, 'hero_h1', 'Проекты, которые запускает MEYOS для роста отрасли') }}</h1>
      <p class="lead">{{ $tr($page, 'hero_lead', 'От кадровой программы EduJob до коллективных экспортных миссий.') }}</p>
    </div>
  </div>
</section>

@foreach ($programs as $idx => $program)
  @php
      $hasBlocks = $program->blocks->count() > 0;
      $hasAdvantages = $program->advantages->count() > 0;
      $altSection = $idx % 2 === 1;
  @endphp
  <section id="{{ $program->slug }}" @class([
    'section-alt' => $altSection,
  ])>
    <div class="container">

      {{-- HEADER ПРОГРАММЫ --}}
      <div class="section-head" style="text-align:left; max-width:none;">
        @if ($tr($program, 'chip'))
          <span class="chip" @if($program->is_flagship) style="background:rgb(var(--primary)); color:#fff;" @endif>
            {{ $tr($program, 'chip') }}
          </span>
        @endif
        <div style="display:grid; gap:2.5rem; grid-template-columns:1fr; align-items:start;">
          <div>
            <h2 style="margin:1rem 0 0;">
              @if($program->icon)
                <span class="material-symbols-outlined" style="vertical-align:-6px; color:rgb(var(--primary)); margin-right:.5rem;">{{ $program->icon }}</span>
              @endif
              {{ $tr($program, 'hero_h1') ?: $tr($program, 'title') }}
            </h2>
            <p style="font-size:1.05rem; color:rgb(var(--on-surface-mut)); margin:1rem 0 0; line-height:1.6;">
              {{ $tr($program, 'hero_lead') ?: $tr($program, 'description') }}
            </p>
          </div>
        </div>
      </div>

      {{-- ОПИСАНИЕ-БЛОКИ (до 4) --}}
      @if ($hasBlocks)
        <div class="grid grid-{{ min($program->blocks->count(), 4) === 1 ? '1' : (min($program->blocks->count(), 4) <= 2 ? '2' : ($program->blocks->count() === 3 ? '3' : '4')) }}" style="margin-top:2.5rem;">
          @foreach ($program->blocks as $block)
            <div @class([
              'card',
              'card-filled' => $block->type === 'metric',
            ])>
              @if ($block->icon)
                <span class="icon-box"><span class="material-symbols-outlined">{{ $block->icon }}</span></span>
              @endif
              @if ($block->type === 'module')
                <span class="chip mt-3" style="margin-top:.75rem;">@switch($cur) @case('uz') Modul @break @case('en') Module @break @default Модуль @endswitch</span>
              @endif
              <h3 class="mt-4" style="font-size:1.2rem;">{{ $tr($block, 'title') }}</h3>
              @if ($tr($block, 'description'))
                <p @class(['mt-3', 'text-mut' => $block->type !== 'metric'])
                   style="line-height:1.55; font-size:.95rem; {{ $block->type === 'metric' ? 'color:rgb(255 255 255 / .9);' : '' }}">
                  {!! nl2br(e($tr($block, 'description'))) !!}
                </p>
              @endif
              @if ($tr($block, 'meta'))
                <div style="margin-top:1rem; padding:.5rem .85rem; background:rgb(var(--primary-soft)); color:rgb(var(--primary-dark)); border-radius:9999px; display:inline-block; font-size:.75rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
                  {{ $block->type === 'metric' ? 'background:rgb(255 255 255 / .2); color:#fff;' : '' }}">
                  {{ $tr($block, 'meta') }}
                </div>
              @endif
            </div>
          @endforeach
        </div>
      @endif

      {{-- ПРЕИМУЩЕСТВА --}}
      @if ($hasAdvantages)
        <div style="margin-top:3rem;">
          <div style="text-align:center; margin-bottom:2rem;">
            <span class="tag">@switch($cur) @case('uz') Imtiyozlar @break @case('en') Advantages @break @default Преимущества @endswitch</span>
            <h3 style="font-size:1.5rem; margin:.75rem 0 0;">@switch($cur) @case('uz') Nima beradi @break @case('en') What you get @break @default Что даёт программа @endswitch</h3>
          </div>
          <div class="grid grid-{{ min($program->advantages->count(), 3) === 1 ? '1' : (min($program->advantages->count(), 3) === 2 ? '2' : '3') }}">
            @foreach ($program->advantages as $advantage)
              <div class="card" style="background:rgb(var(--primary-soft)); border:none;">
                @if ($advantage->icon)
                  <span class="icon-box" style="background:rgb(var(--primary)); color:#fff;"><span class="material-symbols-outlined">{{ $advantage->icon }}</span></span>
                @endif
                <h4 class="mt-4" style="font-size:1.1rem;">{{ $tr($advantage, 'title') }}</h4>
                @if ($tr($advantage, 'description'))
                  <p class="text-mut mt-3" style="font-size:.9rem; line-height:1.55;">{{ $tr($advantage, 'description') }}</p>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      @endif

    </div>
  </section>
@endforeach

<section class="section-alt">
  <div class="container">
    <div class="cta-bar">
      <h2>@switch($cur) @case('uz') Barcha dasturlardan foydalanish @break @case('en') Access all programs @break @default Получите доступ ко всем программам MEYOS @endswitch</h2>
      <p>@switch($cur) @case('uz') Rezidentlik bir vaqtning oʻzida barcha loyihalarga eshik ochadi @break @case('en') Residency opens doors to all projects at once @break @default Резидентство открывает двери во все проекты ассоциации одновременно @endswitch</p>
      <a href="{{ route('residency') }}#join" class="btn btn-white btn-lg">@switch($cur) @case('uz') Rezident boʻlish @break @case('en') Become a resident @break @default Стать резидентом @endswitch</a>
    </div>
  </div>
</section>

@endsection
