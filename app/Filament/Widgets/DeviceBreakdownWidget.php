<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;

class DeviceBreakdownWidget extends ChartWidget
{
    protected ?string $heading = 'Устройства посетителей (за 30 дней)';
    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = 'half';

    protected function getData(): array
    {
        $rows = PageView::query()
            ->notBot()
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('device, COUNT(*) as cnt')
            ->groupBy('device')
            ->pluck('cnt', 'device')
            ->toArray();

        if (empty($rows)) {
            $rows = ['Нет данных' => 1];
        }

        $labelMap = [
            'mobile'  => 'Мобильные',
            'tablet'  => 'Планшеты',
            'desktop' => 'Десктоп',
        ];

        $labels = [];
        $data   = [];
        foreach ($rows as $device => $cnt) {
            $labels[] = $labelMap[$device] ?? $device;
            $data[]   = $cnt;
        }

        return [
            'labels'   => $labels,
            'datasets' => [[
                'label' => 'Посещения',
                'data'  => $data,
                'backgroundColor' => ['#4e342e', '#8c5e3c', '#c39b6e'],
            ]],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
