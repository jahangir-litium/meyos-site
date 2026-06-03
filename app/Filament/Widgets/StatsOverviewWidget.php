<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\EventRegistration;
use App\Models\MembershipApplication;
use App\Models\News;
use App\Models\PageView;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $weekAgo = now()->subDays(7);
        $monthAgo = now()->subDays(30);

        // Заявки
        $appsTotal     = MembershipApplication::count();
        $appsToday     = MembershipApplication::where('created_at', '>=', $today)->count();
        $appsWeek      = MembershipApplication::where('created_at', '>=', $weekAgo)->count();
        $appsNew       = MembershipApplication::where('status', 'new')->count();

        // Регистрации
        $regsTotal = EventRegistration::count();
        $regsWeek  = EventRegistration::where('created_at', '>=', $weekAgo)->count();

        // Сообщения
        $msgsTotal = ContactMessage::count();
        $msgsNew   = ContactMessage::where('status', 'new')->count();

        // Посещения
        $viewsTotal = PageView::notBot()->count();
        $viewsToday = PageView::notBot()->where('created_at', '>=', $today)->count();
        $viewsWeek  = PageView::notBot()->where('created_at', '>=', $weekAgo)->count();
        $uniqueVisitorsWeek = PageView::notBot()
            ->where('created_at', '>=', $weekAgo)
            ->distinct('ip_hash')
            ->count('ip_hash');

        // Конверсия = заявки за неделю / уникальные посетители за неделю
        $conv = $uniqueVisitorsWeek > 0
            ? round($appsWeek / $uniqueVisitorsWeek * 100, 2)
            : 0;

        // График для заявок за 7 дней
        $appsChart = collect(range(6, 0))->map(function ($d) {
            return MembershipApplication::whereDate('created_at', now()->subDays($d)->toDateString())->count();
        })->all();

        $viewsChart = collect(range(6, 0))->map(function ($d) {
            return PageView::notBot()->whereDate('created_at', now()->subDays($d)->toDateString())->count();
        })->all();

        return [
            Stat::make('Заявки на резидентство', $appsTotal)
                ->description("+$appsWeek за 7 дней · $appsNew новых")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart($appsChart),

            Stat::make('Посещений (всего)', $viewsTotal)
                ->description("+$viewsToday сегодня · $viewsWeek за неделю")
                ->descriptionIcon('heroicon-m-eye')
                ->color('success')
                ->chart($viewsChart),

            Stat::make('Уник. посетители за 7 дней', $uniqueVisitorsWeek)
                ->description("Конверсия в заявки: $conv%")
                ->descriptionIcon('heroicon-m-user-group')
                ->color($conv >= 1 ? 'success' : 'warning'),

            Stat::make('Регистрации на события', $regsTotal)
                ->description("+$regsWeek за неделю")
                ->descriptionIcon('heroicon-m-ticket')
                ->color('info'),

            Stat::make('Сообщения', $msgsTotal)
                ->description("$msgsNew не обработано")
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($msgsNew > 0 ? 'warning' : 'gray'),

            Stat::make('Опубликовано новостей', News::published()->count())
                ->description('контента в продакшене')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('gray'),
        ];
    }
}
