<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopPagesWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): ?string
    {
        return 'Топ страниц за 7 дней';
    }

    public function table(Table $table): Table
    {
        // GROUP BY + SELECT с aggregates ломает Filament pagination (count-query
        // не переопределяется, лезет в несуществующий `*`). Решение: отдаём
        // top-10 без pagination, limit(10) на уровне БД.
        return $table
            ->query(
                PageView::query()
                    ->notBot()
                    ->where('created_at', '>=', now()->subDays(7))
                    ->selectRaw('MIN(id) as id, path, COUNT(*) as views, COUNT(DISTINCT ip_hash) as unique_visitors, MAX(created_at) as last_visit')
                    ->groupBy('path')
                    ->orderByDesc('views')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('path')->label('Страница'),
                Tables\Columns\TextColumn::make('views')->label('Просмотры'),
                Tables\Columns\TextColumn::make('unique_visitors')->label('Уник. посетителей'),
                Tables\Columns\TextColumn::make('last_visit')->label('Последний визит')->dateTime('d.m.Y H:i'),
            ])
            ->paginated(false);
    }

    /** Используем path как уникальный ключ строки (GROUP BY делает id null). */
    public function getTableRecordKey($record): string
    {
        return (string) ($record->path ?? $record->getKey() ?? uniqid());
    }
}
