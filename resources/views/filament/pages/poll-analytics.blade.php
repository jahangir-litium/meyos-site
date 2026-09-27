@php
    $total = $poll->total_votes ?: 1;
    $options = $poll->options ?? [];
    // Timeline: последние 30 дней с нулями
    $days = collect(range(29, 0))->map(fn ($d) => now()->subDays($d)->format('Y-m-d'));
    $timeline = $days->map(fn ($d) => ['day' => $d, 'count' => $byDay[$d] ?? 0]);
    $maxDayCount = max([...$timeline->pluck('count')->all(), 1]);
@endphp

<div style="display:grid; gap:1.5rem; padding:.5rem;">

    {{-- Summary --}}
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1rem;">
        <div style="padding:1rem 1.25rem; background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
            <div style="font-size:.75rem; color:#6b7280; text-transform:uppercase; letter-spacing:.05em;">Всего голосов</div>
            <div style="font-size:2rem; font-weight:800; color:#111827;">{{ $poll->total_votes }}</div>
        </div>
        <div style="padding:1rem 1.25rem; background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
            <div style="font-size:.75rem; color:#6b7280; text-transform:uppercase; letter-spacing:.05em;">Уник. IP</div>
            <div style="font-size:2rem; font-weight:800; color:#111827;">{{ $poll->votes()->distinct('ip_hash')->count('ip_hash') }}</div>
        </div>
        <div style="padding:1rem 1.25rem; background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
            <div style="font-size:.75rem; color:#6b7280; text-transform:uppercase; letter-spacing:.05em;">Статус</div>
            <div style="font-size:1.1rem; font-weight:700; color:{{ $poll->is_active && !$poll->hasEnded() ? '#059669' : '#6b7280' }};">
                {{ $poll->is_active && !$poll->hasEnded() ? 'Активен' : 'Завершён' }}
            </div>
        </div>
    </div>

    {{-- Results bar-chart --}}
    <div>
        <h4 style="margin:0 0 .75rem; font-size:1rem; font-weight:600;">Результаты по вариантам</h4>
        @foreach($options as $i => $opt)
            @php
                $count = $counts[$i] ?? 0;
                $percent = round($count * 100 / $total);
                $label = is_array($opt) ? ($opt['ru'] ?? '—') : $opt;
            @endphp
            <div style="margin-bottom:.75rem;">
                <div style="display:flex; justify-content:space-between; font-size:.9rem; margin-bottom:.25rem;">
                    <strong>{{ $label }}</strong>
                    <span style="color:#6b7280;">{{ $count }} · {{ $percent }}%</span>
                </div>
                <div style="height:10px; background:#e5e7eb; border-radius:5px; overflow:hidden;">
                    <div style="height:100%; width:{{ $percent }}%; background:linear-gradient(90deg,#8c5e3c,#c68a5e);"></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Timeline (голоса по дням, sparkline) --}}
    <div>
        <h4 style="margin:0 0 .75rem; font-size:1rem; font-weight:600;">Голоса за последние 30 дней</h4>
        <div style="display:flex; align-items:flex-end; gap:2px; height:80px; padding:.5rem; background:#f9fafb; border-radius:6px; border:1px solid #e5e7eb;">
            @foreach($timeline as $d)
                @php $h = round(($d['count'] / $maxDayCount) * 70); @endphp
                <div title="{{ $d['day'] }}: {{ $d['count'] }}"
                     style="flex:1; height:{{ max(1, $h) }}px; background:{{ $d['count'] > 0 ? '#8c5e3c' : '#e5e7eb' }}; border-radius:2px 2px 0 0;">
                </div>
            @endforeach
        </div>
        <div style="display:flex; justify-content:space-between; margin-top:.25rem; font-size:.7rem; color:#9ca3af;">
            <span>{{ $timeline->first()['day'] }}</span>
            <span>Сегодня</span>
        </div>
    </div>

    {{-- Разбивка по устройствам --}}
    @if(!empty($byUa))
    <div>
        <h4 style="margin:0 0 .75rem; font-size:1rem; font-weight:600;">Устройства голосующих</h4>
        <table style="width:100%; font-size:.85rem; border-collapse:collapse;">
            <thead>
                <tr style="text-align:left; color:#6b7280; border-bottom:1px solid #e5e7eb;">
                    <th style="padding:.35rem 0;">Браузер / OS</th>
                    <th style="padding:.35rem 0; text-align:right;">Голосов</th>
                    <th style="padding:.35rem 0; text-align:right;">%</th>
                </tr>
            </thead>
            <tbody>
                @foreach($byUa as $ua => $count)
                    <tr style="border-bottom:1px solid #f3f4f6;">
                        <td style="padding:.35rem 0;">{{ $ua ?: 'Неизвестно' }}</td>
                        <td style="padding:.35rem 0; text-align:right;">{{ $count }}</td>
                        <td style="padding:.35rem 0; text-align:right; color:#6b7280;">{{ round($count * 100 / $total) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Даты --}}
    <div style="padding:.75rem 1rem; background:#eff6ff; border-radius:6px; border-left:3px solid #3b82f6; font-size:.85rem; color:#1e40af;">
        <strong>Период голосования:</strong>
        {{ $poll->starts_at?->format('d.m.Y H:i') ?: 'с момента создания' }}
        —
        {{ $poll->ends_at?->format('d.m.Y H:i') ?: 'без ограничений' }}
    </div>

</div>
