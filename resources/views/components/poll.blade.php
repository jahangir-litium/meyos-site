@props(['poll'])

@php
    $cur = app()->getLocale();
    $tr = fn ($m, $f) => $m?->getTranslation($f, $cur, false) ?: $m?->getTranslation($f, 'ru', false);
    $question = $tr($poll, 'question');
    $options = $poll->options ?? [];
    $counts = $poll->getVoteCounts();
    $total = $poll->total_votes ?: array_sum($counts);
    $ended = $poll->hasEnded();
    $endsLabel = ['ru' => 'До окончания:', 'uz' => 'Tugashigacha:', 'en' => 'Ends in:'][$cur] ?? 'До окончания:';
@endphp

<div class="poll" data-poll-slug="{{ $poll->slug }}" data-poll-total="{{ $total }}">
  <div class="poll__head">
    <span class="poll__icon material-symbols-outlined" aria-hidden="true">how_to_vote</span>
    <div>
      <div class="poll__meta">@switch($cur) @case('uz') Soʻrovnoma @break @case('en') Poll @break @default Опрос @endswitch · <strong data-poll-total-txt>{{ $total }}</strong> @switch($cur) @case('uz') ovoz @break @case('en') votes @break @default голосов @endswitch</div>
      <div class="poll__question">{{ $question }}</div>
    </div>
  </div>

  {{-- Форма голосования (показана если пользователь ещё не голосовал) --}}
  @if(!$ended)
    <form class="poll__form" action="{{ route('polls.vote', $poll->slug) }}" method="POST" data-poll-form>
      @csrf
      <div class="poll__options">
        @foreach($options as $i => $opt)
          @php $optText = is_array($opt) ? ($opt[$cur] ?? $opt['ru'] ?? '') : $opt; @endphp
          <label class="poll__option">
            <input type="radio" name="option_index" value="{{ $i }}" required>
            <span>{{ $optText }}</span>
          </label>
        @endforeach
      </div>
      <button type="submit" class="btn btn-primary poll__submit">
        @switch($cur) @case('uz') Ovoz berish @break @case('en') Vote @break @default Проголосовать @endswitch
      </button>
    </form>
  @endif

  {{-- Результаты (после голосования или если голосование окончено) --}}
  <div class="poll__results" @if(!$ended) style="display:none;" @endif data-poll-results>
    @foreach($options as $i => $opt)
      @php
        $optText = is_array($opt) ? ($opt[$cur] ?? $opt['ru'] ?? '') : $opt;
        $count = $counts[$i] ?? 0;
        $percent = $total > 0 ? round($count * 100 / $total) : 0;
      @endphp
      <div class="poll__result" data-option-index="{{ $i }}">
        <div class="poll__result-head">
          <span class="poll__result-label">{{ $optText }}</span>
          <span class="poll__result-value"><strong data-count>{{ $count }}</strong> · <span data-percent>{{ $percent }}</span>%</span>
        </div>
        <div class="poll__bar-wrap">
          <div class="poll__bar" style="width: {{ $percent }}%;" data-bar></div>
        </div>
      </div>
    @endforeach
  </div>

  @if($ended)
    <p class="poll__ended">@switch($cur) @case('uz') Soʻrov tugadi @break @case('en') Poll ended @break @default Голосование завершено @endswitch</p>
  @elseif($poll->ends_at)
    <p class="poll__ends-in">{{ $endsLabel }} <strong>{{ $poll->ends_at->diffForHumans(['parts' => 2, 'short' => true]) }}</strong></p>
  @endif
</div>

@once
@push('head')
<style>
.poll { background: rgb(var(--surface)); border: 1px solid rgb(var(--outline)); border-radius: var(--radius-lg); padding: 1.5rem; margin: 1.5rem 0; }
.poll__head { display: flex; gap: .75rem; margin-bottom: 1.25rem; align-items: flex-start; }
.poll__icon { font-size: 1.75rem; line-height: 1; color: rgb(var(--primary)); }
.poll__meta { font-size: .75rem; color: rgb(var(--on-surface-mut)); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem; }
.poll__question { font-size: 1.15rem; font-weight: 700; color: rgb(var(--on-surface)); line-height: 1.3; }
.poll__options { display: grid; gap: .5rem; margin-bottom: 1rem; }
.poll__option { display: flex; align-items: center; gap: .65rem; padding: .85rem 1rem; background: rgb(var(--surface-deep)); border: 1px solid transparent; border-radius: var(--radius-md); cursor: pointer; transition: all .15s; }
.poll__option:hover { border-color: rgb(var(--primary) / .3); }
.poll__option input[type=radio] { accent-color: rgb(var(--primary)); width: 18px; height: 18px; }
.poll__option:has(input:checked) { background: rgb(var(--primary) / .08); border-color: rgb(var(--primary)); }
.poll__submit { width: 100%; }
.poll__results { display: grid; gap: .85rem; }
.poll__result-head { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: .35rem; font-size: .9rem; }
.poll__result-label { color: rgb(var(--on-surface)); font-weight: 500; }
.poll__result-value { color: rgb(var(--on-surface-mut)); font-size: .8rem; white-space: nowrap; }
.poll__bar-wrap { height: 8px; background: rgb(var(--surface-deep)); border-radius: 4px; overflow: hidden; }
.poll__bar { height: 100%; background: linear-gradient(90deg, rgb(var(--primary)), rgb(var(--accent))); transition: width .6s cubic-bezier(.2,.7,.3,1); border-radius: 4px; }
.poll__ended, .poll__ends-in { margin: 1rem 0 0; text-align: center; font-size: .85rem; color: rgb(var(--on-surface-mut)); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-poll-form]').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const poll = form.closest('.poll');
      const btn = form.querySelector('.poll__submit');
      btn.disabled = true;
      try {
        const res = await fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          credentials: 'same-origin',
        });
        const data = await res.json();
        if (data.ok || data.results) {
          // Обновляем результаты
          const total = data.total || 0;
          poll.querySelector('[data-poll-total-txt]').textContent = total;
          poll.querySelectorAll('.poll__result').forEach(r => {
            const idx = +r.dataset.optionIndex;
            const count = data.results[idx] || 0;
            const percent = total > 0 ? Math.round(count * 100 / total) : 0;
            r.querySelector('[data-count]').textContent = count;
            r.querySelector('[data-percent]').textContent = percent;
            r.querySelector('[data-bar]').style.width = percent + '%';
          });
          form.style.display = 'none';
          poll.querySelector('[data-poll-results]').style.display = 'grid';
          if (data.message) {
            const msg = document.createElement('p');
            msg.className = 'poll__ends-in';
            msg.textContent = data.message;
            poll.appendChild(msg);
          }
        }
      } catch (err) {
        btn.disabled = false;
        alert('Ошибка отправки. Попробуйте ещё раз.');
      }
    });
  });
});
</script>
@endpush
@endonce
