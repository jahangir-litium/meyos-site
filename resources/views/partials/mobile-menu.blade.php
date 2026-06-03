@php $cur = app()->getLocale(); @endphp
<div class="mobile-menu" data-mobile-menu>
  <div class="mobile-menu__head">
    <a href="{{ route('home') }}" class="logo"><span class="logo__mark">M</span>MEYOS</a>
    <button class="burger" data-mobile-close><span class="material-symbols-outlined">close</span></button>
  </div>
  <a href="{{ route('about') }}">@switch($cur) @case('uz') Kompaniya haqida @break @case('en') About @break @default О компании @endswitch</a>
  <a href="{{ route('residency') }}">@switch($cur) @case('uz') Rezidentlik @break @case('en') Residency @break @default Резидентство @endswitch</a>
  <a href="{{ route('programs') }}">@switch($cur) @case('uz') Dasturlar @break @case('en') Programs @break @default Программы @endswitch</a>
  <a href="{{ route('partners') }}">@switch($cur) @case('uz') Hamkorlar @break @case('en') Partners @break @default Партнёры @endswitch</a>
  <a href="{{ route('events') }}">@switch($cur) @case('uz') Tadbirlar @break @case('en') Events @break @default Мероприятия @endswitch</a>
  <a href="{{ route('news') }}">@switch($cur) @case('uz') Yangiliklar @break @case('en') News @break @default Новости @endswitch</a>
  <a href="{{ route('contacts') }}">@switch($cur) @case('uz') Kontaktlar @break @case('en') Contacts @break @default Контакты @endswitch</a>
</div>
