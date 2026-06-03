<?php

namespace App\Filament\Widgets;

use App\Models\MembershipApplication;
use Filament\Widgets\ChartWidget;

class ApplicationsTrendChart extends ChartWidget
{
    protected ?string $heading = 'Заявки и посещения за 30 дней';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = collect(range(29, 0))->map(fn ($d) => now()->subDays($d)->format('d.m'))->all();

        $applications = collect(range(29, 0))->map(function ($d) {
            return MembershipApplication::whereDate('created_at', now()->subDays($d)->toDateString())->count();
        })->all();

        $views = collect(range(29, 0))->map(function ($d) {
            return \App\Models\PageView::notBot()
                ->whereDate('created_at', now()->subDays($d)->toDateString())
                ->count();
        })->all();

        return [
            'datasets' => [
                [
                    'label' => 'Посещения',
                    'data' => $views,
                    'borderColor' => 'rgb(140, 94, 60)',
                    'backgroundColor' => 'rgba(140, 94, 60, 0.1)',
                    'yAxisID' => 'y1',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Заявки',
                    'data' => $applications,
                    'borderColor' => 'rgb(78, 52, 46)',
                    'backgroundColor' => 'rgba(78, 52, 46, 0.2)',
                    'yAxisID' => 'y',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'position' => 'left',
                    'title' => ['display' => true, 'text' => 'Заявки'],
                ],
                'y1' => [
                    'type' => 'linear',
                    'position' => 'right',
                    'title' => ['display' => true, 'text' => 'Посещения'],
                    'grid' => ['drawOnChartArea' => false],
                ],
            ],
        ];
    }
}
