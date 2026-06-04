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
    <h3 id="fab-modal-title" style="margin:0 0 .5rem; font-size:1.25rem;">
      @switch($cur) @case('uz') Savolingizni yozing @break @case('en') Ask a question @break @default Задайте вопрос @endswitch
    </h3>
    <p class="text-mut" style="font-size:.9rem; margin:0 0 1.25rem;">
      @switch($cur) @case('uz') Biz ish kuni davomida javob beramiz. @break @case('en') We reply within one business day. @break @default Ответим в течение рабочего дня. @endswitch
    </p>

    <form action="{{ route('submit.contact') }}" method="POST" class="form" style="display:grid; gap:.75rem;">
      @csrf
      <input type="text"  name="name"    required placeholder="@switch($cur) @case('uz') Ismingiz @break @case('en') Your name @break @default Ваше имя @endswitch" />
      <input type="email" name="email"   required placeholder="Email" />
      <input type="tel"   name="phone"            placeholder="@switch($cur) @case('uz') Telefon @break @case('en') Phone @break @default Телефон @endswitch" />
      <input type="hidden" name="topic"  value="fab-ask" />
      <textarea name="message" required rows="3" placeholder="@switch($cur) @case('uz') Savolingiz @break @case('en') Your question @break @default Ваш вопрос @endswitch"></textarea>
      <button type="submit" class="btn btn-primary">@switch($cur) @case('uz') Yuborish @break @case('en') Send @break @default Отправить @endswitch</button>
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
