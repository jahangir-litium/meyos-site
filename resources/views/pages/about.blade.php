@extends('layouts.app')

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f, $d = '') => $m?->getTranslation($f, $cur, false) ?: ($m?->getTranslation($f, 'ru', false) ?: $d);
@endphp

@section('content')

<section class="hero" style="padding:5rem 1.5rem;">
  <div class="hero__inner" style="grid-template-columns:1fr;">
    <div style="max-width:50rem;">
      <span class="tag tag-on-dark"><span class="tag-dot"></span>@switch($cur) @case('uz') Kompaniya haqida @break @case('en') About @break @default О компании @endswitch</span>
      <h1 style="font-size:clamp(2rem, 5vw, 3.75rem); margin:1.5rem 0 1.5rem;">{{ $tr($page, 'hero_h1', 'Создаём инфраструктуру мебельной индустрии Узбекистана') }}</h1>
      <p class="lead">{{ $tr($page, 'hero_lead', 'MEYOS — некоммерческая ассоциация, объединяющая производителей, дизайнеров и поставщиков мебельной отрасли.') }}</p>
    </div>
  </div>
</section>

<!-- HORIZONTAL TIMELINE WITH PINNING (GSAP ScrollTrigger) -->
@if ($timeline->count())
<section class="timeline-pin-section" data-timeline-section>
  <div class="container" style="margin-bottom:1.5rem;">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Tarix @break @case('en') History @break @default История @endswitch</span>
      <h2>@switch($cur) @case('uz') Assotsiatsiya yoʻli @break @case('en') Path of the association @break @default Путь ассоциации @endswitch</h2>
      <p class="text-mut" style="font-size:.9rem; margin-top:.5rem;">
        @switch($cur)
          @case('uz') Skrolling bilan vaqt jadvalidan oʻting @break
          @case('en') Scroll to navigate the timeline @break
          @default Прокручивайте, чтобы перейти по временной шкале @endswitch
      </p>
    </div>
  </div>

  <div class="timeline-stage" data-timeline-stage>
    <button class="timeline-arrow timeline-arrow--prev" type="button" aria-label="Назад" data-timeline-prev>
      <span class="material-symbols-outlined">chevron_left</span>
    </button>
    <button class="timeline-arrow timeline-arrow--next" type="button" aria-label="Вперёд" data-timeline-next>
      <span class="material-symbols-outlined">chevron_right</span>
    </button>

    <div class="timeline-track" data-timeline-track>
      <div class="timeline-axis"></div>
      @foreach ($timeline as $i => $item)
        <div class="timeline-step" data-timeline-step>
          <div class="timeline-step__year">{{ $item->year }}</div>
          <div class="timeline-step__dot" aria-hidden="true"></div>
          <div class="timeline-step__card">
            <h3 class="timeline-step__title">{{ $tr($item, 'title') }}</h3>
            <p class="timeline-step__text">{{ $tr($item, 'description') }}</p>
          </div>
        </div>
      @endforeach
    </div>
    {{-- Прогресс-индикатор --}}
    <div class="timeline-progress" aria-hidden="true">
      <div class="timeline-progress__bar" data-timeline-progress></div>
    </div>
  </div>
</section>

@push('head')
<style>
.timeline-pin-section { padding: 5rem 0 6rem; }
.timeline-stage {
  position: relative;
  margin-top: 1rem;
}
.timeline-arrow {
  position: absolute; top: 50%; transform: translateY(-50%);
  width: 3rem; height: 3rem; border-radius: 50%;
  background: rgb(var(--surface)); border: 1px solid rgb(var(--outline));
  box-shadow: 0 4px 16px rgb(var(--primary) / .15);
  cursor: pointer; z-index: 5;
  display: flex; align-items: center; justify-content: center;
  color: rgb(var(--on-surface));
  transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
}
.timeline-arrow:hover { transform: translateY(-50%) scale(1.08); background: rgb(var(--primary)); color: #fff; }
.timeline-arrow:disabled { opacity: .35; cursor: not-allowed; }
.timeline-arrow:disabled:hover { transform: translateY(-50%); background: rgb(var(--surface)); color: rgb(var(--on-surface)); }
.timeline-arrow--prev { left: 1rem; }
.timeline-arrow--next { right: 1rem; }

.timeline-track {
  display: flex; gap: 3rem;
  padding: 3.5rem 6vw 3.5rem;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  scroll-behavior: smooth;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;             /* Firefox */
  position: relative;
}
.timeline-track::-webkit-scrollbar { display: none; }  /* Chrome/Safari */
.timeline-axis {
  position: absolute; top: 50%; left: 0; right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent, rgb(var(--outline)) 6vw, rgb(var(--outline)) calc(100% - 6vw), transparent);
  z-index: 0;
}
.timeline-step {
  flex: 0 0 auto;
  width: min(340px, calc(100vw - 4rem));
  scroll-snap-align: center;
  position: relative; display: flex; flex-direction: column;
  align-items: center; gap: 1rem;
  z-index: 1;
}
.timeline-step__year {
  font-family: var(--font-head); font-weight: 800;
  font-size: clamp(2rem, 4vw, 3rem); line-height: 1;
  color: rgb(var(--primary));
  letter-spacing: -.02em;
}
.timeline-step__dot {
  width: 1rem; height: 1rem; border-radius: 50%;
  background: rgb(var(--primary));
  box-shadow: 0 0 0 6px rgb(var(--surface)), 0 0 0 7px rgb(var(--primary) / .25);
  z-index: 2;
}
.timeline-step__card {
  background: rgb(var(--surface)); border: 1px solid rgb(var(--outline));
  border-radius: var(--radius-lg); padding: 1.25rem 1.4rem;
  box-shadow: 0 4px 20px rgb(var(--primary) / .05);
  text-align: center;
}
.timeline-step__title { font-size: 1.05rem; margin: 0 0 .5rem; line-height: 1.3; }
.timeline-step__text { font-size: .85rem; line-height: 1.55; color: rgb(var(--on-surface-mut)); margin: 0; }
.timeline-progress {
  margin: 0 auto;
  margin-top: -.5rem;
  width: min(360px, 60vw); height: 3px; border-radius: 3px;
  background: rgb(var(--outline));
  overflow: hidden;
}
.timeline-progress__bar { width: 0; height: 100%; background: rgb(var(--primary)); transition: width .25s ease; }

@media (max-width: 720px) {
  .timeline-arrow { display: none; }
  .timeline-step__year { font-size: 2rem; }
  .timeline-track { padding: 2rem 1rem; gap: 1.5rem; }
}
</style>
@endpush

@push('scripts')
<script>
(() => {
  const track    = document.querySelector('[data-timeline-track]');
  const prevBtn  = document.querySelector('[data-timeline-prev]');
  const nextBtn  = document.querySelector('[data-timeline-next]');
  const progress = document.querySelector('[data-timeline-progress]');
  if (!track) return;

  const stepWidth = () => {
    const step = track.querySelector('[data-timeline-step]');
    return step ? step.offsetWidth + 48 /* gap */ : 380;
  };

  const updateProgress = () => {
    if (!progress) return;
    const max = track.scrollWidth - track.clientWidth;
    const pct = max > 0 ? (track.scrollLeft / max) * 100 : 0;
    progress.style.width = pct + '%';
    if (prevBtn) prevBtn.disabled = track.scrollLeft <= 4;
    if (nextBtn) nextBtn.disabled = track.scrollLeft >= max - 4;
  };

  prevBtn?.addEventListener('click', () => track.scrollBy({ left: -stepWidth(), behavior: 'smooth' }));
  nextBtn?.addEventListener('click', () => track.scrollBy({ left:  stepWidth(), behavior: 'smooth' }));

  // Колесо мыши → горизонтальный скролл (десктоп)
  track.addEventListener('wheel', (e) => {
    if (Math.abs(e.deltaY) <= Math.abs(e.deltaX)) return;
    e.preventDefault();
    track.scrollBy({ left: e.deltaY, behavior: 'auto' });
  }, { passive: false });

  track.addEventListener('scroll', updateProgress, { passive: true });
  window.addEventListener('resize', updateProgress);
  updateProgress();

  // Авто-фокус на следующий шаг каждые 4 сек если пользователь не интерактил
  let userInteracted = false;
  ['wheel','touchstart','mousedown','keydown'].forEach(ev =>
    track.addEventListener(ev, () => userInteracted = true, { once: true, passive: true }));
  setInterval(() => {
    if (userInteracted) return;
    const max = track.scrollWidth - track.clientWidth;
    if (track.scrollLeft >= max - 4) { track.scrollTo({ left: 0, behavior: 'smooth' }); return; }
    track.scrollBy({ left: stepWidth(), behavior: 'smooth' });
  }, 4500);
})();
</script>
@endpush
@endif

<!-- TEAM -->
@if ($team->count())
<section class="section-alt">
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Direktorlar kengashi @break @case('en') Board of Directors @break @default Совет директоров @endswitch</span>
      <h2>@switch($cur) @case('uz') Sohani boshqaradigan jamoa @break @case('en') The team @break @default Команда, которая ведёт индустрию @endswitch</h2>
    </div>
    <div class="grid grid-4">
      @foreach ($team as $member)
        <div class="card" style="text-align:center;">
          @if($member->photo_image ? asset("storage/" . $member->photo_image) : null)
            <div style="width:100px; height:100px; border-radius:9999px; margin:0 auto 1rem; overflow:hidden;"><img src="{{ $member->photo_image ? asset("storage/" . $member->photo_image) : null }}" style="width:100%; height:100%; object-fit:cover;"></div>
          @else
            <div style="width:100px; height:100px; border-radius:9999px; background:rgb(var(--primary-soft)); margin:0 auto 1rem; display:flex; align-items:center; justify-content:center; font-family:var(--font-head); font-size:2rem; font-weight:800; color:rgb(var(--primary));">{{ $member->initials }}</div>
          @endif
          <h3 style="font-size:1.05rem; margin:0 0 .25rem;">{{ $tr($member, 'name') }}</h3>
          <p class="text-mut" style="font-size:.85rem; margin:0;">{{ $tr($member, 'role') }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- CERTIFICATIONS -->
@if ($certifications->count())
<section>
  <div class="container">
    <div class="section-head">
      <span class="tag">@switch($cur) @case('uz') Sertifikatlar @break @case('en') Certificates @break @default Сертификаты и соглашения @endswitch</span>
      <h2>@switch($cur) @case('uz') Rasmiy maqom @break @case('en') Official status @break @default Официальный статус и признание @endswitch</h2>
    </div>
    <div class="grid grid-3">
      @foreach ($certifications as $cert)
        <div class="card">
          <span class="icon-box"><span class="material-symbols-outlined">{{ $cert->icon }}</span></span>
          <h3 class="mt-4" style="font-size:1.15rem;">{{ $tr($cert, 'title') }}</h3>
          <p class="text-mut" style="font-size:.9rem; line-height:1.55;">{{ $tr($cert, 'description') }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection
