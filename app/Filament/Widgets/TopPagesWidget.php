<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

/**
 * «Топ страниц за 7 дней» — простой виджет с собственным Blade-шаблоном.
 *
 * Раньше был TableWidget с GROUP BY, но Filament lazy-mount + MySQL
 * (ONLY_FULL_GROUP_BY) давали 500 на проде. Сейчас:
 *  - lazy-mount отключён ($isLazy=false)
 *  - данные подготовлены коллекцией в getViewData(), минуя Filament pagination/count
 *  - Blade рендерит простую HTML-таблицу
 */
class TopPagesWidget extends Widget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.top-pages';

    protected function getViewData(): array
    {
        // Собираем топ-10 просмотров за 7 дней. Запрос без агрегатных трюков,
        // которые ломаются в MySQL ONLY_FULL_GROUP_BY на некоторых хостингах.
        $rows = PageView::query()
            ->notBot()
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('path')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)
            ->get([
                'path',
                DB::raw('COUNT(*) as views'),
                DB::raw('COUNT(DISTINCT ip_hash) as unique_visitors'),
                DB::raw('MAX(created_at) as last_visit'),
            ])
            ->map(fn ($r) => (object) [
                'path'             => $r->path,
                'views'            => (int) $r->views,
                'unique_visitors'  => (int) $r->unique_visitors,
                'last_visit'       => $r->last_visit ? \Carbon\Carbon::parse($r->last_visit) : null,
            ]);

        return [
            'rows' => $rows,
        ];
    }
}
