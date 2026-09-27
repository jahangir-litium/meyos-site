@php $cur = $cur ?? app()->getLocale(); @endphp

<button class="fab-ask" id="fab-ask-btn" type="button"
        aria-label="@switch($cur) @case('uz') Savol berish @break @case('en') Ask a question @break @default Задать вопрос @endswitch"
        title="@switch($cur) @case('uz') Savol berish @break @case('en') Ask a question @break @default Задать вопрос @endswitch">
  <span class="material-symbols-outlined">forum</span>
</button>

<div class="fab-modal" id="fab-modal" role="dialog" aria-modal="true" aria-labelledby="fab-modal-title">
  <div class="fab-modal__panel" style="position:relative;">
    <button type="button" class="fab-modal__close" id="fab-modal-close" aria-label="Закрыть">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h3 id="fab-modal-title" style="margin:0 0 .5rem; font-size:1.25rem;">@cms('fab.title', 'Задайте вопрос')</h3>
    <p class="text-mut" style="font-size:.9rem; margin:0 0 1.25rem;">@cms('fab.subtitle', 'Ответим в течение рабочего дня.')</p>

    <form action="{{ route('submit.contact') }}" method="POST" class="form" style="display:grid; gap:.75rem;">
      @csrf
      @include('partials.honeypot')
      <input type="text"  name="name"    required placeholder="@cms('fab.placeholder_name', 'Ваше имя')" />
      <input type="email" name="email"   required placeholder="Email" />
      <input type="tel"   name="phone"            placeholder="@cms('fab.placeholder_phone', 'Телефон')" />
      <input type="hidden" name="topic"  value="fab-ask" />
      <textarea name="message" required rows="3" placeholder="@cms('fab.placeholder_message', 'Ваш вопрос')"></textarea>
      <button type="submit" class="btn btn-primary">@cms('fab.submit', 'Отправить')</button>
    </form>
  </div>
</div>

@push('scripts')
<script>
(function() {
  const btn   = document.getElementById('fab-ask-btn');
  const modal = document.getElementById('fab-modal');
  const close = document.getElementById('fab-modal-close');
  if (!btn || !modal) return;
  const open  = () => { modal.classList.add('is-open'); modal.querySelector('input[name="name"]')?.focus(); };
  const hide  = () => { modal.classList.remove('is-open'); };
  btn.addEventListener('click', open);
  close?.addEventListener('click', hide);
  modal.addEventListener('click', e => { if (e.target === modal) hide(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') hide(); });
})();
</script>
@endpush
