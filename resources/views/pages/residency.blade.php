@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
@endphp

@section('content')

<section class="hero" style="padding:5rem 1.5rem;">
  <div class="hero__inner" style="grid-template-columns:1fr;">
    <div style="max-width:50rem;">
      <span class="tag tag-on-dark"><span class="tag-dot"></span>@switch($cur) @case('uz') Maxsus sanoat maqomi @break @case('en') Special industrial status @break @default Особый индустриальный статус @endswitch</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">{{ $tr($page, 'hero_h1', 'Резидентство MEYOS — льготы, защита интересов и доступ к рынку') }}</h1>
      <p class="lead">{{ $tr($page, 'hero_lead', 'Статус резидента даёт измеримую финансовую выгоду уже в первый год.') }}</p>
      <div class="hero__actions">
        <a href="#join" class="btn btn-white btn-lg">@switch($cur) @case('uz') Ariza qoldirish @break @case('en') Leave a request @break @default Оставить заявку @endswitch</a>
      </div>
    </div>
  </div>
</section>

@if ($taxRows->count())
<section>
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Moliyaviy imtiyozlar @break @case('en') Financial advantages @break @default Финансовые преференции @endswitch</span>
      <h2>@switch($cur) @case('uz') Rezident imtiyozlari raqamlarda @break @case('en') Resident benefits in numbers @break @default Льготы резидента — в цифрах @endswitch</h2>
    </div>
    <div style="overflow-x:auto;">
      <table class="tax-table">
        <thead><tr><th>@switch($cur) @case('uz') Parametr @break @case('en') Parameter @break @default Параметр @endswitch</th><th>@switch($cur) @case('uz') Standart @break @case('en') Standard @break @default Стандартная ставка @endswitch</th><th>@switch($cur) @case('uz') MEYOS rezident @break @case('en') Resident @break @default Для резидента MEYOS @endswitch</th><th>@switch($cur) @case('uz') Tejash @break @case('en') Savings @break @default Экономия @endswitch</th></tr></thead>
        <tbody>
          @foreach ($taxRows as $row)
            <tr><td>{{ $tr($row, 'parameter') }}</td><td>{{ $tr($row, 'standard_rate') }}</td><td><span class="big">{{ $tr($row, 'resident_rate') }}</span></td><td class="text-primary" style="font-weight:700;">{{ $tr($row, 'savings') }}</td></tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endif

@if ($benefits->count())
<section class="section-alt">
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Soliqlardan tashqari @break @case('en') Beyond taxes @break @default За рамками налогов @endswitch</span>
      <h2>@switch($cur) @case('uz') Nomoliyaviy imtiyozlar @break @case('en') Non-financial benefits @break @default Нефинансовые преимущества резидентства @endswitch</h2>
    </div>
    <div class="grid grid-3">
      @foreach ($benefits as $b)
        <div class="card">
          <span class="icon-box"><span class="material-symbols-outlined">{{ $b->icon }}</span></span>
          <h3 class="mt-4" style="font-size:1.15rem;">{{ $tr($b, 'title') }}</h3>
          <p class="text-mut" style="font-size:.9rem; line-height:1.55;">{{ $tr($b, 'description') }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if ($steps->count())
<section>
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Aʼzolik jarayoni @break @case('en') Joining process @break @default Процесс вступления @endswitch</span>
      <h2>@switch($cur) @case('uz') Arizadan rezident maqomigacha @break @case('en') From application to resident status @break @default Путь от заявки до статуса резидента @endswitch</h2>
    </div>
    <div class="steps">
      @foreach ($steps as $step)
        <div class="step"><h4>{{ $tr($step, 'title') }}</h4><p>{{ $tr($step, 'description') }}</p></div>
      @endforeach
    </div>
  </div>
</section>
@endif

<section id="join">
  <div class="container" style="max-width:720px;">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Ariza topshirish @break @case('en') Application @break @default Подача заявки @endswitch</span>
      <h2>@switch($cur) @case('uz') Aʼzolik uchun ariza @break @case('en') Membership application @break @default Заявка на вступление в ассоциацию @endswitch</h2>
    </div>
    <form action="{{ route('submit.membership') }}" method="POST" class="form">
      @csrf
      <div style="display:grid; gap:1rem; grid-template-columns:1fr 1fr;">
        <label>@switch($cur) @case('uz') Kompaniya @break @case('en') Company @break @default Название компании @endswitch <input type="text" name="company" required value="{{ old('company') }}" /></label>
        <label>@switch($cur) @case('uz') Kontakt @break @case('en') Contact @break @default Контактное лицо @endswitch <input type="text" name="name" required value="{{ old('name') }}" /></label>
        <label>Email <input type="email" name="email" required value="{{ old('email') }}" /></label>
        <label>@switch($cur) @case('uz') Telefon @break @case('en') Phone @break @default Телефон @endswitch <input type="tel" name="phone" required value="{{ old('phone') }}" /></label>
        <label style="grid-column:1/-1;">@switch($cur) @case('uz') Toifa @break @case('en') Category @break @default Категория @endswitch
          <select name="category"><option>Производство мебели</option><option>Дизайн-студия</option><option>Поставщик материалов</option><option>Логистика и розница</option><option>Другое</option></select>
        </label>
        <label style="grid-column:1/-1;">@switch($cur) @case('uz') Ishlab chiqarish hajmi @break @case('en') Production volume @break @default Объём производства @endswitch
          <select name="volume"><option>До 5 000 изделий</option><option>5 000 – 20 000 изделий</option><option>20 000 – 100 000 изделий</option><option>Более 100 000 изделий</option></select>
        </label>
        <label style="grid-column:1/-1;">@switch($cur) @case('uz') Izoh @break @case('en') Comment @break @default Комментарий @endswitch <textarea name="message" rows="3">{{ old('message') }}</textarea></label>
      </div>
      <button type="submit" class="btn btn-primary btn-lg mt-4">@switch($cur) @case('uz') Yuborish @break @case('en') Send @break @default Отправить заявку @endswitch</button>
    </form>
  </div>
</section>

{{-- ============ ДОКУМЕНТЫ ============ --}}
@if (isset($documents) && $documents->count())
<section class="section-deep">
  <div class="container" style="max-width:880px;">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Hujjatlar @break @case('en') Documents @break @default Документы @endswitch</span>
      <h2>@switch($cur) @case('uz') Rasmiy hujjatlar va shartnomalar @break @case('en') Official documents and agreements @break @default Официальные документы и соглашения @endswitch</h2>
      <p>@switch($cur) @case('uz') Yuklab oling: shartnomalar, uslubiy materiallar va ariza shakllari @break @case('en') Download: contracts, methodology and application forms @break @default Скачайте: типовые договоры, методические материалы и формы заявок @endswitch</p>
    </div>

    <div style="display:grid; gap:.75rem;">
      @foreach ($documents as $doc)
        <a href="{{ $doc->file_path ? asset('storage/' . $doc->file_path) : '#' }}"
           target="_blank" rel="noopener"
           style="display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1.1rem 1.5rem; background:rgb(var(--surface)); border:1px solid rgb(var(--outline)); border-radius:var(--radius-md); text-decoration:none; color:inherit; transition:all .2s;"
           onmouseover="this.style.borderColor='rgb(var(--primary))';"
           onmouseout="this.style.borderColor='rgb(var(--outline))';">
          <div style="display:flex; align-items:center; gap:.85rem; min-width:0;">
            <span class="material-symbols-outlined" style="color:rgb(var(--primary)); font-size:1.5rem; flex-shrink:0;">description</span>
            <div style="min-width:0;">
              <div style="font-weight:600; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $tr($doc, 'title') }}</div>
              @if ($doc->file_name)
                <div class="text-mut" style="font-size:.8rem; margin-top:.15rem;">{{ $doc->file_name }}</div>
              @endif
            </div>
          </div>
          <span class="material-symbols-outlined" style="color:rgb(var(--on-surface-mut)); font-size:1.3rem;">download</span>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection
