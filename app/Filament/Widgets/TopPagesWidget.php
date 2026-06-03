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
        return $table
            ->query(
                PageView::query()
                    ->notBot()
                    ->where('created_at', '>=', now()->subDays(7))
                    ->selectRaw('MIN(id) as id, path, COUNT(*) as views, COUNT(DISTINCT ip_hash) as unique_visitors, MAX(created_at) as last_visit')
                    ->groupBy('path')
                    ->orderByDesc('views')
            )
            ->columns([
                Tables\Columns\TextColumn::make('path')->label('Страница'),
                Tables\Columns\TextColumn::make('views')->label('Просмотры')->sortable(),
                Tables\Columns\TextColumn::make('unique_visitors')->label('Уник. посетителей')->sortable(),
                Tables\Columns\TextColumn::make('last_visit')->label('Последний визит')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->paginated([10])
            ->defaultPaginationPageOption(10);
    }

    /** Используем path как уникальный ключ строки (GROUP BY делает id null). */
    public function getTableRecordKey($record): string
    {
        return (string) ($record->path ?? $record->getKey() ?? uniqid());
    }
}
