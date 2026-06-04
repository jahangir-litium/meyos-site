@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);

    $coverUrl = $event->cover_image ? asset('storage/' . $event->cover_image) : null;
    $title    = $tr($event, 'title');
    $seoTitle = $tr($event, 'seo_title') ?: $title;
    $previewText = $tr($event, 'preview') ?: strip_tags($tr($event, 'description'));
    $seoDesc  = $tr($event, 'seo_description') ?: $previewText;
    $seoDesc  = mb_substr(trim($seoDesc), 0, 200);
    $ogImage  = $event->seo_image ? asset('storage/' . $event->seo_image) : $coverUrl;
@endphp

@section('title', $seoTitle)
@section('description', $seoDesc)
@section('og_type', 'event')
@if($ogImage)@section('og_image', $ogImage)@endif

@push('head')
{{-- Schema.org Event --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'Event',
    'name'        => $title,
    'description' => $seoDesc,
    'image'       => $ogImage ? [$ogImage] : [],
    'startDate'   => $event->event_date?->toIso8601String(),
    'endDate'     => $event->end_date?->toIso8601String() ?: $event->event_date?->toIso8601String(),
    'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
    'eventStatus' => 'https://schema.org/EventScheduled',
    'location' => $tr($event, 'location') ? [
        '@type' => 'Place',
        'name'  => $tr($event, 'location'),
        'address' => $tr($event, 'city') ?: null,
    ] : null,
    'organizer'   => [
        '@type' => 'Organization',
        'name'  => \App\Models\Setting::get('site_name', 'MEYOS'),
        'url'   => url('/'),
    ],
    'inLanguage' => $cur,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => [
        ['@type'=>'ListItem','position'=>1,'name'=>'Главная','item'=>url('/')],
        ['@type'=>'ListItem','position'=>2,'name'=>'Мероприятия','item'=>route('events')],
        ['@type'=>'ListItem','position'=>3,'name'=>$title,'item'=>url()->current()],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

<section style="padding:5rem 1.5rem 3rem;">
  <div class="container" style="max-width:880px;">

    <nav aria-label="Breadcrumb" style="margin-bottom:1.5rem;">
      <ol style="display:flex; gap:.4rem; padding:0; margin:0; list-style:none; font-size:.85rem; color:rgb(var(--on-surface-mut)); flex-wrap:wrap;">
        <li><a href="{{ url('/') }}" style="color:inherit; text-decoration:none;">@switch($cur) @case('uz') Bosh sahifa @break @case('en') Home @break @default Главная @endswitch</a></li>
        <li aria-hidden="true">›</li>
        <li><a href="{{ route('events') }}" style="color:inherit; text-decoration:none;">@switch($cur) @case('uz') Tadbirlar @break @case('en') Events @break @default Мероприятия @endswitch</a></li>
        <li aria-hidden="true">›</li>
        <li aria-current="page" style="color:rgb(var(--on-surface)); max-width:30ch; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $title }}</li>
      </ol>
    </nav>

    <span class="chip">{{ \App\Models\Event::allCategories()[$event->category] ?? '' }}</span>
    <h1 style="font-size:clamp(1.75rem, 4vw, 3rem); margin:1rem 0 1.5rem;">{{ $title }}</h1>

    @if($coverUrl)
      <div style="aspect-ratio:16/9; overflow:hidden; border-radius:var(--radius-lg); margin-bottom:2rem;">
        <img src="{{ $coverUrl }}" alt="{{ $tr($event, 'image_alt', $title) }}"
             loading="lazy" decoding="async" class="lazy-img"
             style="width:100%; height:100%; object-fit:cover;">
      </div>
    @endif

    <div style="display:flex; gap:2rem; flex-wrap:wrap; padding:1.5rem; background:rgb(var(--surface-alt)); border-radius:var(--radius-lg); margin-bottom:2rem;">
      <div><strong>@switch($cur) @case('uz') Sana @break @case('en') Date @break @default Дата @endswitch:</strong> {{ $event->event_date->format('d.m.Y') }}</div>
      @if($tr($event, 'location'))<div><strong>@switch($cur) @case('uz') Joy @break @case('en') Location @break @default Локация @endswitch:</strong> {{ $tr($event, 'location') }}</div>@endif
      @if($event->expected_attendees)<div><strong>@switch($cur) @case('uz') Ishtirokchilar @break @case('en') Attendees @break @default Участников @endswitch:</strong> {{ $event->expected_attendees }}</div>@endif
    </div>

    @if($tr($event, 'description'))<div style="font-size:1.05rem; line-height:1.7;">{!! nl2br(e($tr($event, 'description'))) !!}</div>@endif

    <form action="{{ route('submit.event', $event->slug) }}" method="POST" class="form" style="margin-top:3rem;">
      @csrf
      <h3 style="margin:0 0 .5rem;">@switch($cur) @case('uz') Roʻyxatdan oʻtish @break @case('en') Registration @break @default Регистрация на мероприятие @endswitch</h3>
      <p class="text-mut" style="margin:0 0 1.25rem; font-size:.9rem;">
        @switch($cur)
          @case('uz') Maydonlarni toʻldiring, koordinator tasdiq uchun bogʻlanadi @break
          @case('en') Fill the fields, a coordinator will reach out to confirm @break
          @default Заполните поля — координатор свяжется для подтверждения @endswitch
      </p>
      <div style="display:grid; gap:1rem; grid-template-columns:1fr 1fr;">
        <label>@switch($cur) @case('uz') Kompaniya @break @case('en') Company @break @default Компания @endswitch <input type="text" name="company" required></label>
        <label>@switch($cur) @case('uz') Ism @break @case('en') Name @break @default Имя @endswitch <input type="text" name="name" required></label>
        <label>Email <input type="email" name="email" required></label>
        <label>@switch($cur) @case('uz') Telefon @break @case('en') Phone @break @default Телефон @endswitch <input type="tel" name="phone" required></label>
        <label style="grid-column:1/-1;">@switch($cur) @case('uz') Ishtirokchilar soni @break @case('en') Attendees count @break @default Количество участников @endswitch <input type="number" name="attendees_count" value="1" min="1" max="20"></label>
      </div>
      <button type="submit" class="btn btn-primary btn-lg mt-4">@switch($cur) @case('uz') Roʻyxatdan oʻtish @break @case('en') Register @break @default Подать заявку @endswitch</button>
    </form>
  </div>
</section>

@endsection
