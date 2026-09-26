@php
    $nav = [
        ['route' => 'about',     'label' => 'О компании'],
        ['route' => 'residency', 'label' => 'Резидентство'],
        ['route' => 'programs',  'label' => 'Программы'],
        ['route' => 'partners',  'label' => 'Партнёры'],
        ['route' => 'events',    'label' => 'Мероприятия'],
        ['route' => 'news',      'label' => 'Новости'],
        ['route' => 'contacts',  'label' => 'Контакты'],
    ];
    $navLabels = [
        'ru' => ['О компании','Резидентство','Программы','Партнёры','Мероприятия','Новости','Контакты'],
        'uz' => ['Kompaniya','Rezidentlik','Dasturlar','Hamkorlar','Tadbirlar','Yangiliklar','Kontaktlar'],
        'en' => ['About','Residency','Programs','Partners','Events','News','Contacts'],
    ];
    $cur = app()->getLocale();
@endphp

<header class="header">
  <div class="header__inner">
    @php $logoUrl = \App\Models\Setting::logoUrl(); $siteName = \App\Models\Setting::get('site_name', 'MEYOS'); @endphp
    <a href="{{ route('home') }}" class="logo">
      @if ($logoUrl)
        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" style="height:36px; width:auto; display:block;" />
      @else
        <span class="logo__mark">{{ mb_substr($siteName, 0, 1) }}</span> {{ $siteName }}
      @endif
    </a>

    <nav class="nav" aria-label="@switch($cur) @case('uz') Asosiy navigatsiya @break @case('en') Main navigation @break @default Основная навигация @endswitch">
      @foreach ($nav as $i => $item)
        @php $isActive = request()->routeIs($item['route']); @endphp
        <a href="{{ route($item['route']) }}"
           class="{{ $isActive ? 'is-active' : '' }}"
           @if($isActive) aria-current="page" @endif>{{ $navLabels[$cur][$i] ?? $item['label'] }}</a>
      @endforeach
    </nav>

    <div style="display:flex; align-items:center; gap:.75rem;">
      <div class="lang" role="group" aria-label="@switch($cur) @case('uz') Til tanlash @break @case('en') Language switcher @break @default Переключатель языка @endswitch">
        <a href="?lang=ru" hreflang="ru" lang="ru" aria-label="Русский" @if($cur === 'ru') aria-current="true" @endif class="{{ $cur === 'ru' ? 'is-active' : '' }}" style="text-decoration:none; padding:.35rem .7rem; font-size:.65rem; font-weight:800; letter-spacing:.1em; border-radius:9999px; color:{{ $cur === 'ru' ? '#fff' : 'rgb(var(--on-surface-mut))' }}; background:{{ $cur === 'ru' ? 'rgb(var(--primary))' : 'transparent' }};">RU</a>
        <a href="?lang=uz" hreflang="uz" lang="uz" aria-label="Oʻzbekcha" @if($cur === 'uz') aria-current="true" @endif class="{{ $cur === 'uz' ? 'is-active' : '' }}" style="text-decoration:none; padding:.35rem .7rem; font-size:.65rem; font-weight:800; letter-spacing:.1em; border-radius:9999px; color:{{ $cur === 'uz' ? '#fff' : 'rgb(var(--on-surface-mut))' }}; background:{{ $cur === 'uz' ? 'rgb(var(--primary))' : 'transparent' }};">UZ</a>
        <a href="?lang=en" hreflang="en" lang="en" aria-label="English" @if($cur === 'en') aria-current="true" @endif class="{{ $cur === 'en' ? 'is-active' : '' }}" style="text-decoration:none; padding:.35rem .7rem; font-size:.65rem; font-weight:800; letter-spacing:.1em; border-radius:9999px; color:{{ $cur === 'en' ? '#fff' : 'rgb(var(--on-surface-mut))' }}; background:{{ $cur === 'en' ? 'rgb(var(--primary))' : 'transparent' }};">EN</a>
      </div>
      <a href="{{ route('residency') }}#join" class="btn btn-primary" style="padding:.7rem 1.4rem; font-size:.85rem;">
        @switch($cur) @case('uz') Aʼzo boʻlish @break @case('en') Join @break @default Вступить @endswitch
      </a>
      <button class="burger" data-burger type="button"
              aria-label="@switch($cur) @case('uz') Menyuni ochish @break @case('en') Open menu @break @default Открыть меню @endswitch"
              aria-controls="mobile-menu"
              aria-expanded="false">
        <span class="material-symbols-outlined" aria-hidden="true">menu</span>
      </button>
    </div>
  </div>
</header>
