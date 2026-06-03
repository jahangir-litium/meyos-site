<?php

namespace App\Filament\Widgets;

use App\Models\MembershipApplication;
use App\Models\PageView;
use Filament\Widgets\ChartWidget;

class CategoryBreakdownWidget extends ChartWidget
{
    protected ?string $heading = 'Сегментация трафика и заявок';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'half';

    protected function getData(): array
    {
        // Заявки по категориям
        $byCategory = MembershipApplication::query()
            ->selectRaw('category, COUNT(*) as cnt')
            ->groupBy('category')
            ->pluck('cnt', 'category')
            ->toArray();

        if (empty($byCategory)) {
            return [
                'labels'   => ['Нет заявок'],
                'datasets' => [['data' => [1], 'backgroundColor' => ['#e6d5c3']]],
            ];
        }

        return [
            'labels'   => array_keys($byCategory),
            'datasets' => [[
                'label' => 'Заявки',
                'data'  => array_values($byCategory),
                'backgroundColor' => ['#4e342e', '#8c5e3c', '#a87047', '#c39b6e', '#e6d5c3', '#fbe0cc'],
            ]],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
