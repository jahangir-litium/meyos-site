@php
    $settings = $settings ?? [];
    $cur = app()->getLocale();
    $tagline = match($cur) {
        'uz' => 'Oʻzbekiston mebelsozlari assotsiatsiyasi. Mebel biznesining IT va strategik hamkori.',
        'en' => 'Uzbekistan Furniture Association. IT and strategic partner for the furniture business.',
        default => 'Ассоциация мебельщиков Узбекистана. IT и стратегический партнёр мебельного бизнеса.',
    };
    $sections   = match($cur) { 'uz' => 'Boʻlimlar', 'en' => 'Sections', default => 'Разделы' };
    $activities = match($cur) { 'uz' => 'Faolliklar', 'en' => 'Activities', default => 'Активности' };
    $contacts   = match($cur) { 'uz' => 'Aloqa', 'en' => 'Contacts', default => 'Контакты' };
@endphp
<footer class="footer">
  <div class="footer__grid">
    @php $logoUrl = \App\Models\Setting::logoUrl(); $siteName = \App\Models\Setting::get('site_name', 'MEYOS'); @endphp
    <div>
      <a href="{{ route('home') }}" class="logo" style="color:#fff;">
        @if ($logoUrl)
          <img src="{{ $logoUrl }}" alt="{{ $siteName }}" style="height:32px; width:auto; filter:brightness(0) invert(1);" />
        @else
          <span class="logo__mark">{{ mb_substr($siteName, 0, 1) }}</span> {{ $siteName }}
        @endif
      </a>
      <p style="opacity:.7; font-size:.9rem; line-height:1.6; margin-top:1rem; max-width:22rem;">{{ $tagline }}</p>
    </div>
    <div>
      <h4>{{ $sections }}</h4>
      <a href="{{ route('about') }}">@switch($cur) @case('uz') Kompaniya haqida @break @case('en') About @break @default О компании @endswitch</a>
      <a href="{{ route('residency') }}">@switch($cur) @case('uz') Rezidentlik @break @case('en') Residency @break @default Резидентство @endswitch</a>
      <a href="{{ route('programs') }}">@switch($cur) @case('uz') Dasturlar @break @case('en') Programs @break @default Программы @endswitch</a>
      <a href="{{ route('partners') }}">@switch($cur) @case('uz') Hamkorlar @break @case('en') Partners @break @default Партнёры @endswitch</a>
    </div>
    <div>
      <h4>{{ $activities }}</h4>
      <a href="{{ route('events') }}">@switch($cur) @case('uz') Tadbirlar @break @case('en') Events @break @default Мероприятия @endswitch</a>
      <a href="{{ route('news') }}">@switch($cur) @case('uz') Yangiliklar @break @case('en') News @break @default Новости @endswitch</a>
      <a href="{{ route('programs') }}#edujob">EduJob</a>
      <a href="{{ route('residency') }}#join">@switch($cur) @case('uz') Rezident boʻlish @break @case('en') Become a resident @break @default Стать резидентом @endswitch</a>
    </div>
    <div>
      <h4>{{ $contacts }}</h4>
      @if(!empty($settings['phone'])) <a href="tel:{{ str_replace(' ', '', $settings['phone']) }}">{{ $settings['phone'] }}</a> @endif
      @if(!empty($settings['email'])) <a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a> @endif
      @if(!empty($settings['address'])) <p style="opacity:.7; font-size:.85rem; margin:.8rem 0 0;">{{ $settings['address'] }}</p> @endif
    </div>
  </div>
  <div class="footer__bottom">
    <span>© {{ date('Y') }} MEYOS · Ассоциация мебельщиков Узбекистана</span>
    <span>@switch($cur) @case('uz') Maxfiylik siyosati @break @case('en') Privacy Policy @break @default Политика конфиденциальности @endswitch</span>
  </div>
</footer>
