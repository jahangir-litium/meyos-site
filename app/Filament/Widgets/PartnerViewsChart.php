<?php

namespace App\Filament\Widgets;

use App\Models\PartnerView;
use Filament\Widgets\ChartWidget;

class PartnerViewsChart extends ChartWidget
{
    protected ?string $heading = 'Просмотры страниц партнёров за 30 дней';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = collect(range(29, 0))->map(fn ($d) => now()->subDays($d)->format('d.m'))->all();

        $totals = collect(range(29, 0))->map(function ($d) {
            return PartnerView::whereDate('viewed_at', now()->subDays($d)->toDateString())->count();
        })->all();

        $unique = collect(range(29, 0))->map(function ($d) {
            return PartnerView::whereDate('viewed_at', now()->subDays($d)->toDateString())
                ->distinct('ip_hash')
                ->count('ip_hash');
        })->all();

        return [
            'datasets' => [
                [
                    'label' => 'Всего просмотров',
                    'data' => $totals,
                    'borderColor' => 'rgb(140, 94, 60)',
                    'backgroundColor' => 'rgba(140, 94, 60, 0.15)',
                    'tension' => 0.3,
                    'fill' => true,
                ],
                [
                    'label' => 'Уникальных посетителей',
                    'data' => $unique,
                    'borderColor' => 'rgb(78, 52, 46)',
                    'backgroundColor' => 'rgba(78, 52, 46, 0.15)',
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
}
