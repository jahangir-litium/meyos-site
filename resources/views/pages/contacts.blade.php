@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
@endphp

@section('content')

<div class="container" style="padding-top:1.25rem;">
  @include('partials.breadcrumbs', ['items' => [
    ['label' => ['ru' => 'Контакты', 'uz' => 'Kontaktlar', 'en' => 'Contacts'][$cur], 'url' => null],
  ]])
</div>

<section class="hero" style="padding:5rem 1.5rem;">
  <div class="hero__inner" style="grid-template-columns:1fr;">
    <div style="max-width:50rem;">
      <span class="tag tag-on-dark"><span class="tag-dot"></span>@switch($cur) @case('uz') Kontaktlar @break @case('en') Contacts @break @default Контакты @endswitch</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">{{ $tr($page, 'hero_h1', 'Свяжитесь с ассоциацией MEYOS') }}</h1>
      <p class="lead">{{ $tr($page, 'hero_lead', 'Менеджеры ассоциации отвечают на вопросы о резидентстве, льготах, программах и партнёрствах.') }}</p>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="grid grid-2">
      <div style="display:grid; gap:1rem;">
        @if(!empty($settings['phone']))
          <div class="card">
            <span class="icon-box"><span class="material-symbols-outlined">call</span></span>
            <h3 class="mt-4" style="font-size:1.05rem;">@switch($cur) @case('uz') Telefon @break @case('en') Phone @break @default Телефон @endswitch</h3>
            <a href="tel:{{ str_replace(' ', '', $settings['phone']) }}" style="color:rgb(var(--primary)); font-weight:600; text-decoration:none;">{{ $settings['phone'] }}</a>
            <p class="text-mut" style="margin-top:.5rem;">{{ $settings['hours'] }}</p>
          </div>
        @endif
        @if(!empty($settings['email']))
          <div class="card">
            <span class="icon-box"><span class="material-symbols-outlined">mail</span></span>
            <h3 class="mt-4" style="font-size:1.05rem;">Email</h3>
            <a href="mailto:{{ $settings['email'] }}" style="color:rgb(var(--primary)); font-weight:600; text-decoration:none;">{{ $settings['email'] }}</a>
          </div>
        @endif
        @if(!empty($settings['address']))
          <div class="card">
            <span class="icon-box"><span class="material-symbols-outlined">location_on</span></span>
            <h3 class="mt-4" style="font-size:1.05rem;">@switch($cur) @case('uz') Ofis @break @case('en') Office @break @default Офис @endswitch</h3>
            <p style="font-weight:600;">{{ $settings['address'] }}</p>
          </div>
        @endif
        @if(!empty($settings['requisites']))
          <div class="card">
            <span class="icon-box"><span class="material-symbols-outlined">description</span></span>
            <h3 class="mt-4" style="font-size:1.05rem;">@switch($cur) @case('uz') Rekvizitlar @break @case('en') Requisites @break @default Реквизиты @endswitch</h3>
            <p style="font-weight:600;">{{ $settings['entity'] ?? '' }}</p>
            <p class="text-mut" style="margin-top:.5rem; font-size:.85rem;">{{ $settings['requisites'] }}</p>
          </div>
        @endif
      </div>

      <form action="{{ route('submit.contact') }}" method="POST" class="form">
        @csrf
        @include('partials.honeypot')
        <h3 style="margin:0 0 .5rem; font-size:1.3rem;">@switch($cur) @case('uz') Savol berish @break @case('en') Ask a question @break @default Задать вопрос @endswitch</h3>
        <label>@switch($cur) @case('uz') Ism @break @case('en') Name @break @default Имя @endswitch <input type="text" name="name" required value="{{ old('name') }}"></label>
        <label>@switch($cur) @case('uz') Kompaniya @break @case('en') Company @break @default Компания @endswitch <input type="text" name="company" value="{{ old('company') }}"></label>
        <label>Email <input type="email" name="email" required value="{{ old('email') }}"></label>
        <label>@switch($cur) @case('uz') Telefon @break @case('en') Phone @break @default Телефон @endswitch <input type="tel" name="phone" value="{{ old('phone') }}"></label>
        <label>@switch($cur) @case('uz') Mavzu @break @case('en') Topic @break @default Тема обращения @endswitch
          <select name="topic"><option>Вступление в ассоциацию</option><option>Льготы и преференции</option><option>Программа EduJob</option><option>Мероприятия</option><option>Партнёрство / медиа</option><option>Другое</option></select>
        </label>
        <label>@switch($cur) @case('uz') Xabar @break @case('en') Message @break @default Сообщение @endswitch <textarea name="message" rows="4" required>{{ old('message') }}</textarea></label>
        <button type="submit" class="btn btn-primary btn-lg mt-4">@switch($cur) @case('uz') Yuborish @break @case('en') Send @break @default Отправить @endswitch</button>
      </form>
    </div>

    {{-- ============ Карта офиса (Яндекс.Карты) ============ --}}
    @php
        $lat = \App\Models\Setting::get('office_lat');
        $lng = \App\Models\Setting::get('office_lng');
        $zoom = (int) (\App\Models\Setting::get('office_zoom', 16) ?: 16);
        $lang = $cur === 'ru' ? 'ru_RU' : ($cur === 'uz' ? 'uz_UZ' : 'en_US');
        // Если заданы координаты — используем точную метку. Иначе — поиск по адресу
        if ($lat && $lng) {
            // pm2rdm = метка "pin marker 2 red medium"
            $mapSrc = "https://yandex.ru/map-widget/v1/?ll={$lng},{$lat}&z={$zoom}&pt={$lng},{$lat},pm2rdm&lang={$lang}";
        } elseif (!empty($settings['address'])) {
            $mapSrc = "https://yandex.ru/map-widget/v1/?text=" . urlencode($settings['address']) . "&z={$zoom}&lang={$lang}";
        } else {
            $mapSrc = null;
        }
    @endphp

    @if($mapSrc)
    <div style="margin-top:3rem;">
      <h3 style="font-size:1.3rem; margin:0 0 1rem;">
        @switch($cur) @case('uz') Xaritada @break @case('en') On the map @break @default На карте @endswitch
      </h3>
      <div class="map-embed">
        <iframe
          src="{{ $mapSrc }}"
          width="100%" height="400"
          frameborder="0" allowfullscreen
          style="border-radius: var(--radius-lg); border: 1px solid rgb(var(--outline)); display: block;"
          title="@switch($cur) @case('uz') MEYOS ofisi xaritada @break @case('en') MEYOS office on map @break @default Офис MEYOS на карте @endswitch"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        @if(!empty($settings['address']))
        <p style="margin-top:.75rem; font-size:.85rem; color:rgb(var(--on-surface-mut));">
          <span class="material-symbols-outlined" style="vertical-align:-4px; font-size:1.1rem;">location_on</span>
          {{ $settings['address'] }}
        </p>
        @endif
      </div>
    </div>
    @endif
  </div>
</section>

@endsection

@push('scripts')
<style>
.map-embed { position: relative; }
@media (max-width: 639px) {
  .map-embed iframe { height: 320px; border-radius: var(--radius-md); }
}
</style>
@endpush
