<?php

namespace App\Filament\Widgets;

use App\Models\Partner;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopPartnersWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): ?string
    {
        return 'Топ партнёров по просмотрам за 30 дней';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Partner::query()
                    ->where('is_published', true)
                    ->where('views_count_30d', '>', 0)
                    ->orderByDesc('views_count_30d')
            )
            ->columns([
                Tables\Columns\ImageColumn::make('logo_image')
                    ->disk('public')
                    ->label('Лого')
                    ->height(40)
                    ->extraImgAttributes(['style' => 'object-fit:contain;']),
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['ru'] ?? array_values($state)[0] ?? '—') : $state),
                Tables\Columns\TextColumn::make('category')
                    ->label('Категория')
                    ->badge()
                    ->formatStateUsing(fn ($s) => Partner::allCategories()[$s] ?? $s),
                Tables\Columns\TextColumn::make('views_count_30d')
                    ->label('За 30 дней')
                    ->alignRight()
                    ->numeric()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('views_count_total')
                    ->label('Всего')
                    ->alignRight()
                    ->numeric(),
                Tables\Columns\TextColumn::make('last_viewed_at')
                    ->label('Последний просмотр')
                    ->since(),
            ])
            ->paginated([10])
            ->defaultPaginationPageOption(10);
    }
}
