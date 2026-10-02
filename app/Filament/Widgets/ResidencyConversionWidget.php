<?php

namespace App\Filament\Widgets;

use App\Models\MembershipApplication;
use App\Models\PageView;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Конверсия: уникальные посетители /residency → отправили заявку.
 * Метрика главная для маркетинга — окно в 30 дней.
 */
class ResidencyConversionWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Воронка резидентства (30 дней)';

    protected function getStats(): array
    {
        $since = now()->subDays(30);

        // ============ Шаг 1: уник. визиты на /residency ============
        $residencyVisitors = PageView::notBot()
            ->where('created_at', '>=', $since)
            ->where(function ($q) {
                $q->where('path', '/residency')
                  ->orWhere('path', 'like', '/residency%')
                  ->orWhere('page_slug', 'residency');
            })
            ->distinct('ip_hash')
            ->count('ip_hash');

        // ============ Шаг 2: заявки за период ============
        // Учитываем только заявки, пришедшие СО страницы /residency или #join
        // (у membership-формы на /residency source_page = /residency, у join-формы
        // на главной — /). Иначе конверсия некорректна: заявки с главной / контактов
        // не относятся к потоку /residency.
        $applications = MembershipApplication::where('created_at', '>=', $since)
            ->where(function ($q) {
                $q->where('source_page', 'like', '%/residency%')
                  ->orWhere('source_page', 'like', '%#join%');
            })
            ->count();

        // ============ Конверсия ============
        // Защита: конверсия не может превышать 100%. Если у нас есть заявки без
        // привязки к источнику (старые/тестовые) — не искажаем метрику.
        $rate = $residencyVisitors > 0
            ? min(100, round($applications / $residencyVisitors * 100, 2))
            : 0;

        // ============ Топ-UTM источник за период ============
        $topUtm = MembershipApplication::where('created_at', '>=', $since)
            ->whereNotNull('utm_source')
            ->selectRaw('utm_source, count(*) as cnt')
            ->groupBy('utm_source')
            ->orderByDesc('cnt')
            ->first();
        $topUtmText = $topUtm
            ? "$topUtm->utm_source ({$topUtm->cnt})"
            : '— нет UTM —';

        // ============ Воронка по дням ============
        $chart = collect(range(29, 0))->map(function ($d) {
            return MembershipApplication::whereDate('created_at', now()->subDays($d)->toDateString())
                ->where(function ($q) {
                    $q->where('source_page', 'like', '%/residency%')
                      ->orWhere('source_page', 'like', '%#join%');
                })
                ->count();
        })->all();

        return [
            Stat::make('Уник. на /residency', $residencyVisitors)
                ->description('за 30 дней (без ботов)')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),

            Stat::make('Заявок отправлено', $applications)
                ->description("Конверсия: $rate%")
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color($rate >= 2 ? 'success' : ($rate >= 0.5 ? 'warning' : 'danger'))
                ->chart($chart),

            Stat::make('Топ UTM источник', $topUtmText)
                ->description('за 30 дней')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('gray'),
        ];
    }
}
