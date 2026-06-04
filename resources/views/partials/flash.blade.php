{{-- Универсальные уведомления — success / error / warning + список ошибок валидации --}}

@php
    $cur = $cur ?? app()->getLocale();
    $closeLabel = match($cur) { 'uz' => 'Yopish', 'en' => 'Close', default => 'Закрыть' };
@endphp

<style>
.flash-banner {
  position: fixed; top: 5rem; right: 1.5rem; padding: 1rem 1.25rem;
  border-radius: var(--radius-md); box-shadow: var(--shadow-lg);
  z-index: 220; animation: slide-in .4s ease-out;
  max-width: min(420px, calc(100vw - 3rem));
  display: flex; gap: .75rem; align-items: flex-start;
  font-size: .95rem; line-height: 1.45;
}
.flash-banner .icon { flex-shrink: 0; font-size: 1.4rem; line-height: 1; margin-top: .1rem; }
.flash-banner .text { flex: 1; }
.flash-banner .text strong { display: block; font-weight: 700; margin-bottom: .15rem; font-size: 1rem; }
.flash-banner .close-x {
  background: transparent; border: 0; cursor: pointer; color: inherit; opacity: .7;
  font-size: 1.1rem; padding: 0; line-height: 1;
}
.flash-banner .close-x:hover { opacity: 1; }
.flash-success { background: rgb(var(--primary)); color: #fff; }
.flash-error   { background: #c43c3c; color: #fff; }
.flash-warning { background: #b8830e; color: #fff; }
@media (max-width: 720px) {
  .flash-banner { top: auto; bottom: 1rem; right: 1rem; left: 1rem; max-width: none; }
}
</style>

@if (session('success'))
  <div class="flash-banner flash-success" role="status">
    <span class="material-symbols-outlined icon">check_circle</span>
    <div class="text">
      <strong>@switch($cur) @case('uz') Bajarildi @break @case('en') Success @break @default Готово @endswitch</strong>
      {{ session('success') }}
    </div>
    <button type="button" class="close-x" aria-label="{{ $closeLabel }}" onclick="this.parentElement.remove()">
      <span class="material-symbols-outlined">close</span>
    </button>
  </div>
@endif

@if (session('error'))
  <div class="flash-banner flash-error" role="alert">
    <span class="material-symbols-outlined icon">error</span>
    <div class="text">
      <strong>@switch($cur) @case('uz') Xato @break @case('en') Error @break @default Ошибка @endswitch</strong>
      {{ session('error') }}
    </div>
    <button type="button" class="close-x" aria-label="{{ $closeLabel }}" onclick="this.parentElement.remove()">
      <span class="material-symbols-outlined">close</span>
    </button>
  </div>
@endif

@if (session('warning'))
  <div class="flash-banner flash-warning" role="status">
    <span class="material-symbols-outlined icon">warning</span>
    <div class="text">
      <strong>@switch($cur) @case('uz') Diqqat @break @case('en') Heads up @break @default Внимание @endswitch</strong>
      {{ session('warning') }}
    </div>
    <button type="button" class="close-x" aria-label="{{ $closeLabel }}" onclick="this.parentElement.remove()">
      <span class="material-symbols-outlined">close</span>
    </button>
  </div>
@endif

@if ($errors->any())
  <div class="flash-banner flash-error" role="alert">
    <span class="material-symbols-outlined icon">report</span>
    <div class="text">
      <strong>@switch($cur) @case('uz') Maydonlarda xato @break @case('en') Form has errors @break @default Проверьте поля @endswitch</strong>
      <ul style="margin:.35rem 0 0; padding:0; list-style:none; font-size:.9rem;">
        @foreach ($errors->all() as $err)
          <li style="display:flex; gap:.4rem; align-items:flex-start;">
            <span style="opacity:.7;">·</span><span>{{ $err }}</span>
          </li>
        @endforeach
      </ul>
    </div>
    <button type="button" class="close-x" aria-label="{{ $closeLabel }}" onclick="this.parentElement.remove()">
      <span class="material-symbols-outlined">close</span>
    </button>
  </div>
@endif

@if (session('success') || session('error') || session('warning'))
<script>
  // Авто-скрытие success через 7 сек; ошибки — 12 сек
  setTimeout(() => document.querySelectorAll('.flash-success, .flash-warning').forEach(el => el.style.opacity='0'), 7000);
  setTimeout(() => document.querySelectorAll('.flash-success, .flash-warning').forEach(el => el.remove()), 7500);
</script>
@endif
