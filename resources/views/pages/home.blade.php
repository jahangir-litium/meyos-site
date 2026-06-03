@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($model, $field, $default = '') => $model?->getTranslation($field, $cur, false) ?: ($model?->getTranslation($field, 'ru', false) ?: $default);
    $heroTag  = $tr($page, 'hero_tag', 'Цифровая экосистема');
    $heroH1   = $tr($page, 'hero_h1', 'MEYOS — драйвер роста мебельной отрасли Узбекистана');
    $heroLead = $tr($page, 'hero_lead', '');
@endphp

@section('content')

<!-- HERO -->
<section class="hero">
  <div class="hero__inner">
    <div>
      <span class="tag tag-on-dark"><span class="tag-dot"></span>{{ $heroTag }}</span>
      <h1>{{ $heroH1 }}</h1>
      <p class="lead">{{ $heroLead }}</p>
      <div class="hero__actions">
        <a href="{{ route('residency') }}#join" class="btn btn-white btn-lg">@switch($cur) @case('uz') Rezident boʻlish @break @case('en') Become a Resident @break @default Стать резидентом @endswitch</a>
        <a href="#benefits" class="btn btn-outline-white btn-lg">@switch($cur) @case('uz') Imtiyozlarni koʻrish @break @case('en') Explore Benefits @break @default Узнать преимущества @endswitch</a>
      </div>
      <div class="hero__stats">
        @foreach (($settings['stats'] ?? []) as $key => $value)
            <div>
              <div class="hero__stat-num">{{ $value }}</div>
              <div class="hero__stat-lbl">
                @switch($key)
                  @case('companies') @switch($cur) @case('uz') Rezident kompaniyalar @break @case('en') Resident companies @break @default Компаний-резидентов @endswitch @break
                  @case('growth') @switch($cur) @case('uz') Daromad oʻsishi @break @case('en') Revenue growth @break @default Средний рост выручки @endswitch @break
                  @case('countries') @switch($cur) @case('uz') Eksport mamlakatlari @break @case('en') Export countries @break @default Стран экспорта @endswitch @break
                  @case('years') @switch($cur) @case('uz') Bozorda yil @break @case('en') Years on market @break @default Лет на рынке @endswitch @break
                @endswitch
              </div>
            </div>
        @endforeach
      </div>
    </div>
    <div style="position:relative;">
      <div class="hero__media">
        @php $heroUrl = $page?->getFirstMediaUrl('hero'); @endphp
        <img src="{{ $heroUrl ?: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=900&auto=format&fit=crop&q=80' }}" alt="MEYOS" />
      </div>
    </div>
  </div>
</section>

<!-- ПРЕИМУЩЕСТВА -->
<section id="benefits" class="section-alt">
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Rezidentlik imtiyozlari @break @case('en') Residency advantages @break @default Преимущества резидентства @endswitch</span>
      <h2>@switch($cur) @case('uz') MEYOSga aʼzo boʻlishning sabablari @break @case('en') Five reasons to join MEYOS @break @default Пять измеримых причин вступить в MEYOS @endswitch</h2>
    </div>
    <div class="grid grid-3">
      @foreach ($benefits as $benefit)
        <div class="card">
          <span class="icon-box"><span class="material-symbols-outlined">{{ $benefit->icon }}</span></span>
          <h3 class="mt-4" style="font-size:1.2rem;">{{ $tr($benefit, 'title') }}</h3>
          <p class="text-mut" style="font-size:.95rem; line-height:1.55;">{{ $tr($benefit, 'description') }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ПРОБЛЕМА → РЕШЕНИЕ -->
<section>
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Soha toʻsiqlari @break @case('en') Industry barriers @break @default Барьеры отрасли @endswitch</span>
      <h2>@switch($cur) @case('uz') Muammolar va yechimlar @break @case('en') Problems and solutions @break @default Проблемы мебельного бизнеса и их решения @endswitch</h2>
    </div>
    <div class="ps-grid">
      <div class="ps-col ps-col--pain">
        <h3><span class="material-symbols-outlined" style="color:#c43c3c;">warning</span> @switch($cur) @case('uz') Assotsiatsiyasiz @break @case('en') Without association @break @default Без ассоциации @endswitch</h3>
        <ul>
          @foreach ($painSols as $ps)
            <li><span class="bullet"></span><div><h4>{{ $tr($ps, 'pain_title') }}</h4><p>{{ $tr($ps, 'pain_description') }}</p></div></li>
          @endforeach
        </ul>
      </div>
      <div class="ps-col ps-col--gain">
        <h3><span class="material-symbols-outlined text-primary">check_circle</span> @switch($cur) @case('uz') MEYOS bilan @break @case('en') With MEYOS @break @default С MEYOS @endswitch</h3>
        <ul>
          @foreach ($painSols as $ps)
            <li><span class="bullet"></span><div><h4>{{ $tr($ps, 'solution_title') }}</h4><p>{{ $tr($ps, 'solution_description') }}</p></div></li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- БИЗНЕС-КЕЙСЫ -->
@if ($cases->count())
<section class="section-alt">
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Sanoat oʻsishi · keyslar @break @case('en') Industrial growth · cases @break @default Индустриальный рост · кейсы @endswitch</span>
      <h2>@switch($cur) @case('uz') MEYOS rezidentlari biznesni qanday rivojlantirmoqda @break @case('en') How MEYOS residents grow business @break @default Как резиденты MEYOS развивают свой бизнес @endswitch</h2>
    </div>
    <div style="display:grid; gap:1.5rem;">
      @foreach ($cases as $case)
        <article class="case">
          <div class="case__img">
            @if($case->getFirstMediaUrl('cover'))
              <img src="{{ $case->getFirstMediaUrl('cover') }}" alt="{{ $tr($case, 'title') }}" />
            @else
              <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&auto=format&fit=crop&q=80" alt="" />
            @endif
          </div>
          <div>
            <span class="case__num">{{ $tr($case, 'tag') }}</span>
            <h3 class="case__title">{{ $tr($case, 'title') }}</h3>
            <p class="text-mut" style="line-height:1.6;">{{ $tr($case, 'description') }}</p>
            <div class="case__metrics">
              <div><div class="case__metric-num">{{ $tr($case, 'metric1_value') }}</div><div class="case__metric-lbl">{{ $tr($case, 'metric1_label') }}</div></div>
              <div><div class="case__metric-num">{{ $tr($case, 'metric2_value') }}</div><div class="case__metric-lbl">{{ $tr($case, 'metric2_label') }}</div></div>
              <div><div class="case__metric-num">{{ $tr($case, 'metric3_value') }}</div><div class="case__metric-lbl">{{ $tr($case, 'metric3_label') }}</div></div>
            </div>
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ПРОГРАММЫ -->
@if ($programs->count())
<section>
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Assotsiatsiya dasturlari @break @case('en') Programs @break @default Программы ассоциации @endswitch</span>
      <h2>@switch($cur) @case('uz') MEYOS ishga tushirayotgan loyihalar @break @case('en') Projects launched by MEYOS @break @default Проекты, которые запускает MEYOS @endswitch</h2>
    </div>
    <div class="bento">
      @foreach ($programs as $program)
        <div class="card{{ $program->is_flagship ? ' span-2' : '' }}" @if($program->is_flagship) style="background: linear-gradient(135deg, rgb(var(--primary-soft)), rgb(var(--accent-soft))); border:none;" @endif>
          @if ($tr($program, 'chip'))<span class="chip">{{ $tr($program, 'chip') }}</span>@endif
          @if (!$program->is_flagship)<span class="icon-box"><span class="material-symbols-outlined">{{ $program->icon }}</span></span>@endif
          <h3 class="mt-4" style="font-size:{{ $program->is_flagship ? '1.6rem' : '1.2rem' }};">{{ $tr($program, 'title') }}</h3>
          <p class="text-mut mt-3" style="line-height:1.55; font-size:{{ $program->is_flagship ? '1rem' : '.9rem' }};">{{ $tr($program, 'description') }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ТАБЛИЦА ЛЬГОТ -->
@if ($taxRows->count())
<section class="section-deep">
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Moliyaviy imtiyozlar @break @case('en') Financial advantages @break @default Финансовые преференции @endswitch</span>
      <h2>@switch($cur) @case('uz') Soliq va bojxona imtiyozlari @break @case('en') Tax and customs benefits @break @default Налоговые и таможенные льготы резидента @endswitch</h2>
    </div>
    <div style="overflow-x:auto;">
      <table class="tax-table">
        <thead><tr>
          <th>@switch($cur) @case('uz') Parametr @break @case('en') Parameter @break @default Параметр @endswitch</th>
          <th>@switch($cur) @case('uz') Standart @break @case('en') Standard @break @default Стандартная ставка @endswitch</th>
          <th>@switch($cur) @case('uz') MEYOS rezident @break @case('en') For resident @break @default Для резидента MEYOS @endswitch</th>
          <th>@switch($cur) @case('uz') Tejash @break @case('en') Savings @break @default Экономия @endswitch</th>
        </tr></thead>
        <tbody>
          @foreach ($taxRows as $row)
            <tr>
              <td>{{ $tr($row, 'parameter') }}</td>
              <td>{{ $tr($row, 'standard_rate') }}</td>
              <td><span class="big">{{ $tr($row, 'resident_rate') }}</span></td>
              <td class="text-primary" style="font-weight:700;">{{ $tr($row, 'savings') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endif

<!-- ШАГИ -->
@if ($steps->count())
<section>
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Rezident yoʻli @break @case('en') Resident path @break @default Путь резидента @endswitch</span>
      <h2>@switch($cur) @case('uz') 4 qadamda aʼzo boʻlish @break @case('en') Join in 4 steps @break @default Как вступить в ассоциацию за 4 шага @endswitch</h2>
    </div>
    <div class="steps">
      @foreach ($steps as $step)
        <div class="step">
          <h4>{{ $tr($step, 'title') }}</h4>
          <p>{{ $tr($step, 'description') }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ПАРТНЁРЫ -->
@if ($partners->count())
<section class="section-alt">
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Hamkorlar @break @case('en') Partners @break @default Партнёры @endswitch</span>
      <h2>@switch($cur) @case('uz') Biz hamkorlik qiladigan kompaniyalar @break @case('en') Companies we work with @break @default Компании и институты, с которыми мы работаем @endswitch</h2>
    </div>
    <div class="partners-grid">
      @foreach ($partners as $partner)
        <div class="partner-logo">{{ $partner->logo_text ?: $tr($partner, 'name') }}</div>
      @endforeach
    </div>
    <div style="text-align:center; margin-top:2rem;">
      <a href="{{ route('partners') }}" class="btn btn-ghost">@switch($cur) @case('uz') Barchasini koʻrish @break @case('en') View all @break @default Смотреть всех партнёров @endswitch</a>
    </div>
  </div>
</section>
@endif

<!-- МЕРОПРИЯТИЯ -->
@if ($events->count())
<section>
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Yaqinlashayotgan tadbirlar @break @case('en') Upcoming events @break @default Ближайшие мероприятия @endswitch</span>
      <h2>@switch($cur) @case('uz') Forumlar va koʻrgazmalar @break @case('en') Forums and exhibitions @break @default Форумы, выставки и деловые встречи @endswitch</h2>
    </div>
    <div class="grid grid-3">
      @foreach ($events as $event)
        <div class="card">
          <span class="chip">{{ $event->event_date->format('d.m.Y') }} · {{ $tr($event, 'city') }}</span>
          <h3 class="mt-3" style="font-size:1.2rem;">{{ $tr($event, 'title') }}</h3>
          <p class="text-mut mt-3" style="font-size:.9rem; line-height:1.55;">{{ $tr($event, 'preview') }}</p>
          <a href="{{ route('events.show', $event->slug) }}" class="btn btn-ghost mt-4" style="margin-top:1rem;">@switch($cur) @case('uz') Roʻyxatdan oʻtish @break @case('en') Register @break @default Зарегистрироваться @endswitch</a>
        </div>
      @endforeach
    </div>
    <div style="text-align:center; margin-top:2.5rem;">
      <a href="{{ route('events') }}" class="btn btn-ghost">@switch($cur) @case('uz') Barcha tadbirlar @break @case('en') All events @break @default Все мероприятия @endswitch</a>
    </div>
  </div>
</section>
@endif

<!-- НОВОСТИ -->
@if ($news->count())
<section class="section-alt">
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Assotsiatsiya yangiliklari @break @case('en') Association news @break @default Новости ассоциации @endswitch</span>
      <h2>@switch($cur) @case('uz') Soʻnggi voqealar @break @case('en') Latest events @break @default Последние события MEYOS @endswitch</h2>
    </div>
    <div class="grid grid-3">
      @foreach ($news as $newsItem)
        <article class="card">
          <span class="chip">{{ \App\Models\News::CATEGORIES[$newsItem->category] ?? $newsItem->category }}</span>
          <h3 class="mt-4" style="font-size:1.15rem;">{{ $tr($newsItem, 'title') }}</h3>
          <p class="text-mut mt-3" style="font-size:.9rem; line-height:1.55;">{{ $tr($newsItem, 'preview') }}</p>
          <a href="{{ route('news.show', $newsItem->slug) }}" class="text-primary mt-4" style="display:inline-block; margin-top:1rem; font-weight:700; text-decoration:none;">@switch($cur) @case('uz') Oʻqish → @break @case('en') Read → @break @default Читать → @endswitch</a>
        </article>
      @endforeach
    </div>
    <div style="text-align:center; margin-top:2.5rem;">
      <a href="{{ route('news') }}" class="btn btn-ghost">@switch($cur) @case('uz') Barcha yangiliklar @break @case('en') All news @break @default Все новости @endswitch</a>
    </div>
  </div>
</section>
@endif

<!-- FAQ -->
@if ($faqs->count())
<section>
  <div class="container" style="max-width:880px;">
    <div class="section-head">
      <span class="tag">FAQ</span>
      <h2>@switch($cur) @case('uz') Koʻp beriladigan savollar @break @case('en') Frequently asked questions @break @default Часто задаваемые вопросы @endswitch</h2>
    </div>
    <div>
      @foreach ($faqs as $i => $faq)
        <details class="faq-item" {{ $i === 0 ? 'open' : '' }}>
          <summary class="faq-q">{{ $tr($faq, 'question') }}</summary>
          <div class="faq-a">{{ $tr($faq, 'answer') }}</div>
        </details>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- CTA + ФОРМА -->
<section id="join" class="section-alt">
  <div class="container">
    <div class="cta-bar">
      <h2>@switch($cur) @case('uz') Mebel biznesidasiz — oʻrningiz MEYOSda @break @case('en') If you\'re in furniture business — your place is in MEYOS @break @default Если вы в мебельном бизнесе — ваше место в MEYOS @endswitch</h2>
      <p>@switch($cur) @case('uz') Ariza qoldiring, menejer bir ish kuni ichida bogʻlanadi @break @case('en') Leave a request, a manager will contact you @break @default Оставьте заявку, и менеджер свяжется с вами в течение рабочего дня @endswitch</p>
      <a href="#join-form" class="btn btn-white btn-lg">@switch($cur) @case('uz') Ariza qoldirish @break @case('en') Leave a request @break @default Оставить заявку @endswitch</a>
    </div>

    <form id="join-form" action="{{ route('submit.membership') }}" method="POST" class="form mt-8" style="margin-top:3rem;">
      @csrf
      <h3 style="margin:0 0 .5rem; font-size:1.4rem;">@switch($cur) @case('uz') Aʼzolik uchun ariza @break @case('en') Membership application @break @default Заявка на вступление в ассоциацию @endswitch</h3>
      <p class="text-mut" style="margin:0 0 1rem; font-size:.95rem;">@switch($cur) @case('uz') Maydonlarni toʻldiring @break @case('en') Fill in the fields @break @default Заполните поля @endswitch</p>
      <div style="display:grid; gap:1rem; grid-template-columns:1fr 1fr;">
        <label>@switch($cur) @case('uz') Kompaniya @break @case('en') Company @break @default Название компании @endswitch <input type="text" name="company" required value="{{ old('company') }}" placeholder="ООО «Ваша компания»" /></label>
        <label>@switch($cur) @case('uz') Kontakt shaxs @break @case('en') Contact person @break @default Контактное лицо @endswitch <input type="text" name="name" required value="{{ old('name') }}" placeholder="Имя и фамилия" /></label>
        <label>Email <input type="email" name="email" required value="{{ old('email') }}" placeholder="email@company.uz" /></label>
        <label>@switch($cur) @case('uz') Telefon @break @case('en') Phone @break @default Телефон @endswitch <input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="+998 __ ___ __ __" /></label>
        <label style="grid-column:1/-1;">@switch($cur) @case('uz') Biznes toifasi @break @case('en') Business category @break @default Категория бизнеса @endswitch
          <select name="category">
            <option>Производство мебели</option>
            <option>Дизайн-студия</option>
            <option>Поставщик материалов и фурнитуры</option>
            <option>Логистика и розница</option>
            <option>Другое</option>
          </select>
        </label>
        <label style="grid-column:1/-1;">@switch($cur) @case('uz') Izoh @break @case('en') Comment @break @default Комментарий @endswitch <textarea name="message" rows="3">{{ old('message') }}</textarea></label>
      </div>
      <button type="submit" class="btn btn-primary btn-lg mt-4" style="margin-top:.5rem;">@switch($cur) @case('uz') Yuborish @break @case('en') Send @break @default Отправить заявку @endswitch</button>
      @error('company') <p style="color:#c43c3c; font-size:.85rem;">{{ $message }}</p> @enderror
      @error('email')   <p style="color:#c43c3c; font-size:.85rem;">{{ $message }}</p> @enderror
    </form>
  </div>
</section>

@endsection
