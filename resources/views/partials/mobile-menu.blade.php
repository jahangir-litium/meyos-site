@php $cur = app()->getLocale(); @endphp
<div class="mobile-menu" data-mobile-menu>
  <div class="mobile-menu__head">
    <a href="{{ route('home') }}" class="logo"><span class="logo__mark">M</span>MEYOS</a>
    <button class="burger" data-mobile-close aria-label="Закрыть"><span class="material-symbols-outlined">close</span></button>
  </div>

  <a href="{{ route('about') }}">@switch($cur) @case('uz') Kompaniya haqida @break @case('en') About @break @default О компании @endswitch</a>
  <a href="{{ route('residency') }}">@switch($cur) @case('uz') Rezidentlik @break @case('en') Residency @break @default Резидентство @endswitch</a>
  <a href="{{ route('programs') }}">@switch($cur) @case('uz') Dasturlar @break @case('en') Programs @break @default Программы @endswitch</a>
  <a href="{{ route('partners') }}">@switch($cur) @case('uz') Hamkorlar @break @case('en') Partners @break @default Партнёры @endswitch</a>
  <a href="{{ route('events') }}">@switch($cur) @case('uz') Tadbirlar @break @case('en') Events @break @default Мероприятия @endswitch</a>
  <a href="{{ route('news') }}">@switch($cur) @case('uz') Yangiliklar @break @case('en') News @break @default Новости @endswitch</a>
  <a href="{{ route('contacts') }}">@switch($cur) @case('uz') Kontaktlar @break @case('en') Contacts @break @default Контакты @endswitch</a>

  {{-- ============ Переключатель языка в mobile-меню ============ --}}
  <div class="mobile-menu__lang">
    <span class="mobile-menu__lang-label">@switch($cur) @case('uz') Til @break @case('en') Language @break @default Язык @endswitch</span>
    <div class="mobile-menu__lang-options">
      <a href="?lang=ru" class="{{ $cur === 'ru' ? 'is-active' : '' }}">RU · Русский</a>
      <a href="?lang=uz" class="{{ $cur === 'uz' ? 'is-active' : '' }}">UZ · Oʻzbekcha</a>
      <a href="?lang=en" class="{{ $cur === 'en' ? 'is-active' : '' }}">EN · English</a>
    </div>
  </div>

  {{-- ============ CTA в меню ============ --}}
  <a href="{{ route('residency') }}#join" class="btn btn-primary btn-lg" style="display:block; text-align:center; margin-top:1.5rem;">
    @switch($cur) @case('uz') Aʼzo boʻlish @break @case('en') Join MEYOS @break @default Вступить в MEYOS @endswitch
  </a>
</div>
