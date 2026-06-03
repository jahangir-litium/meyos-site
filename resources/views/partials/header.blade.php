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

    <nav class="nav">
      @foreach ($nav as $i => $item)
        <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'is-active' : '' }}">{{ $navLabels[$cur][$i] ?? $item['label'] }}</a>
      @endforeach
    </nav>

    <div style="display:flex; align-items:center; gap:.75rem;">
      <div class="lang">
        <a href="?lang=ru" class="{{ $cur === 'ru' ? 'is-active' : '' }}" style="text-decoration:none; padding:.35rem .7rem; font-size:.65rem; font-weight:800; letter-spacing:.1em; border-radius:9999px; color:{{ $cur === 'ru' ? '#fff' : 'rgb(var(--on-surface-mut))' }}; background:{{ $cur === 'ru' ? 'rgb(var(--primary))' : 'transparent' }};">RU</a>
        <a href="?lang=uz" class="{{ $cur === 'uz' ? 'is-active' : '' }}" style="text-decoration:none; padding:.35rem .7rem; font-size:.65rem; font-weight:800; letter-spacing:.1em; border-radius:9999px; color:{{ $cur === 'uz' ? '#fff' : 'rgb(var(--on-surface-mut))' }}; background:{{ $cur === 'uz' ? 'rgb(var(--primary))' : 'transparent' }};">UZ</a>
        <a href="?lang=en" class="{{ $cur === 'en' ? 'is-active' : '' }}" style="text-decoration:none; padding:.35rem .7rem; font-size:.65rem; font-weight:800; letter-spacing:.1em; border-radius:9999px; color:{{ $cur === 'en' ? '#fff' : 'rgb(var(--on-surface-mut))' }}; background:{{ $cur === 'en' ? 'rgb(var(--primary))' : 'transparent' }};">EN</a>
      </div>
      <a href="{{ route('residency') }}#join" class="btn btn-primary" style="padding:.7rem 1.4rem; font-size:.85rem;">
        @switch($cur) @case('uz') Aʼzo boʻlish @break @case('en') Join @break @default Вступить @endswitch
      </a>
      <button class="burger" data-burger><span class="material-symbols-outlined">menu</span></button>
    </div>
  </div>
</header>
