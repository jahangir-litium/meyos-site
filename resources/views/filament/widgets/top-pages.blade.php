<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Топ страниц за 7 дней
        </x-slot>

        @if ($rows->isEmpty())
            <div style="padding: 1.5rem 0; text-align:center; color: rgb(107 114 128); font-size:.9rem;">
                Пока нет данных о просмотрах за последние 7 дней.
            </div>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse: collapse; font-size: .875rem;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgb(229 231 235); color: rgb(107 114 128); text-align:left;">
                            <th style="padding: .6rem .5rem; font-weight: 500;">Страница</th>
                            <th style="padding: .6rem .5rem; font-weight: 500; text-align:right;">Просмотры</th>
                            <th style="padding: .6rem .5rem; font-weight: 500; text-align:right;">Уник. посетителей</th>
                            <th style="padding: .6rem .5rem; font-weight: 500;">Последний визит</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr style="border-bottom: 1px solid rgb(243 244 246);">
                                <td style="padding: .6rem .5rem;">
                                    <code style="background: rgb(243 244 246); padding: .1rem .4rem; border-radius: .25rem;">{{ $row->path }}</code>
                                </td>
                                <td style="padding: .6rem .5rem; text-align:right; font-variant-numeric: tabular-nums;">{{ number_format($row->views, 0, ',', ' ') }}</td>
                                <td style="padding: .6rem .5rem; text-align:right; font-variant-numeric: tabular-nums;">{{ number_format($row->unique_visitors, 0, ',', ' ') }}</td>
                                <td style="padding: .6rem .5rem; color: rgb(107 114 128);">
                                    {{ $row->last_visit?->format('d.m.Y H:i') ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
