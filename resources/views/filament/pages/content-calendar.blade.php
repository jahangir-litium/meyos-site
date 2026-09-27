@php $data = $this->getCalendarData(); @endphp
<x-filament-panels::page>
  <div class="fi-section" style="padding:1rem 1.25rem;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
      <div style="display:flex; gap:.5rem;">
        <button wire:click="prevMonth" class="fi-btn fi-btn-color-gray" style="padding:.35rem .75rem;">◄</button>
        <button wire:click="today" class="fi-btn fi-btn-color-gray" style="padding:.35rem .75rem;">Сегодня</button>
        <button wire:click="nextMonth" class="fi-btn fi-btn-color-gray" style="padding:.35rem .75rem;">►</button>
      </div>
      <h2 style="font-size:1.35rem; font-weight:700; text-transform:capitalize; margin:0;">{{ $data['monthLabel'] }}</h2>
      <div style="display:flex; gap:1rem; font-size:.85rem; color:#6b7280;">
        <span><span style="display:inline-block; width:10px; height:10px; background:#3b82f6; border-radius:2px; vertical-align:middle;"></span> Новости</span>
        <span><span style="display:inline-block; width:10px; height:10px; background:#f59e0b; border-radius:2px; vertical-align:middle;"></span> События</span>
      </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(7,1fr); gap:1px; background:#e5e7eb; border:1px solid #e5e7eb; border-radius:8px; overflow:hidden;">
      @foreach(['Пн','Вт','Ср','Чт','Пт','Сб','Вс'] as $dow)
        <div style="background:#f9fafb; padding:.5rem; text-align:center; font-weight:600; font-size:.8rem; color:#6b7280; text-transform:uppercase;">{{ $dow }}</div>
      @endforeach

      @foreach($data['weeks'] as $week)
        @foreach($week as $cell)
          @if($cell === null)
            <div style="background:#fafafa; min-height:110px;"></div>
          @else
            <div style="background:{{ $cell['isToday'] ? '#fef3c7' : 'white' }}; min-height:110px; padding:.5rem; display:flex; flex-direction:column; gap:.25rem;">
              <div style="font-size:.85rem; font-weight:{{ $cell['isToday'] ? '700' : '500' }}; color:{{ $cell['isToday'] ? '#92400e' : '#374151' }};">{{ $cell['day'] }}</div>
              @foreach($cell['news'] as $n)
                <a href="{{ route('filament.admin.resources.news.edit', $n) }}"
                   style="display:block; background:#dbeafe; color:#1e40af; padding:.15rem .35rem; border-radius:3px; font-size:.7rem; text-decoration:none; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; border-left:3px solid #3b82f6;"
                   title="Новость: {{ $n->getTranslation('title','ru',false) }}{{ $n->is_published ? '' : ' [Черновик]' }}">
                  {{ mb_strimwidth($n->getTranslation('title','ru',false), 0, 22, '…') }}
                </a>
              @endforeach
              @foreach($cell['events'] as $e)
                <a href="{{ route('filament.admin.resources.events.edit', $e) }}"
                   style="display:block; background:#fef3c7; color:#92400e; padding:.15rem .35rem; border-radius:3px; font-size:.7rem; text-decoration:none; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; border-left:3px solid #f59e0b;"
                   title="Мероприятие: {{ $e->getTranslation('title','ru',false) }}">
                  {{ mb_strimwidth($e->getTranslation('title','ru',false), 0, 22, '…') }}
                </a>
              @endforeach
            </div>
          @endif
        @endforeach
      @endforeach
    </div>

    <p style="margin-top:1rem; font-size:.85rem; color:#6b7280;">
      Клик по записи открывает редактирование. Новости отображаются по дате публикации, события — по дате проведения.
    </p>
  </div>
</x-filament-panels::page>
