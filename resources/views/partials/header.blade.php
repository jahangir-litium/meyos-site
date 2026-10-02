@php
    // Пункт с children рендерится как dropdown. Активен когда любой из потомков активен.
    $cur = app()->getLocale();
    $t = fn (string $ru, string $uz, string $en) => [$ru, $uz, $en][['ru'=>0,'uz'=>1,'en'=>2][$cur] ?? 0];

    $nav = [
        [
            'label'    => $t('О компании', 'Kompaniya haqida', 'About'),
            'route'    => 'about',
            'children' => [
                ['label' => $t('История ассоциации', 'Uyushma tarixi', 'History'),       'route' => 'about'],
                ['label' => $t('Законодательство',    'Qonunchilik',     'Legislation'), 'route' => 'legislation'],
            ],
        ],
        ['route' => 'residency', 'label' => $t('Резидентство', 'Rezidentlik', 'Residency')],
        [
            'label'    => $t('Программы', 'Dasturlar', 'Programs'),
            'route'    => 'programs',
            'children' => [
                ['label' => $t('Проекты',    'Loyihalar',  'Projects'),  'route' => 'programs'],
                ['label' => $t('Объявления', 'Eʼlonlar',    'Listings'),  'route' => 'listings'],
            ],
        ],
        ['route' => 'partners',  'label' => $t('Партнёры',     'Hamkorlar',     'Partners')],
        ['route' => 'events',    'label' => $t('Мероприятия',  'Tadbirlar',     'Events')],
        ['route' => 'news',      'label' => $t('Новости',      'Yangiliklar',   'News')],
        ['route' => 'contacts',  'label' => $t('Контакты',     'Kontaktlar',    'Contacts')],
    ];
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
      @foreach ($nav as $item)
        @php
          $hasChildren = !empty($item['children']);
          $childRoutes = $hasChildren ? array_column($item['children'], 'route') : [$item['route']];
          $isActive    = request()->routeIs(...$childRoutes);
        @endphp
        @if ($hasChildren)
          <div class="nav-item nav-item--dropdown {{ $isActive ? 'is-active' : '' }}">
            <a href="{{ route($item['route']) }}" class="nav-item__trigger" @if($isActive) aria-current="page" @endif>
              {{ $item['label'] }}
              <span class="material-symbols-outlined nav-item__chevron" aria-hidden="true">expand_more</span>
            </a>
            <div class="nav-item__menu" role="menu">
              @foreach ($item['children'] as $child)
                <a href="{{ route($child['route']) }}" role="menuitem"
                   class="{{ request()->routeIs($child['route']) ? 'is-active' : '' }}">{{ $child['label'] }}</a>
              @endforeach
            </div>
          </div>
        @else
          <a href="{{ route($item['route']) }}"
             class="{{ $isActive ? 'is-active' : '' }}"
             @if($isActive) aria-current="page" @endif>{{ $item['label'] }}</a>
        @endif
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
