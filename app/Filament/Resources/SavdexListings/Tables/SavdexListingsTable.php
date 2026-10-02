<?php

namespace App\Filament\Resources\SavdexListings\Tables;

use App\Models\SavdexListing;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class SavdexListingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('listing_type')
                    ->label('Тип')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'demand' => 'warning',
                        'offer'  => 'success',
                        'tender' => 'info',
                        default  => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => SavdexListing::TYPES[$state] ?? $state),
                TextColumn::make('city')
                    ->label('Город')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Цена')
                    ->placeholder('—')
                    ->limit(30),
                TextColumn::make('published_at')
                    ->label('Опубл.')
                    ->date('d.m.Y')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('fetched_at')
                    ->label('Обновлено')
                    ->since()
                    ->sortable()
                    ->tooltip(fn ($record) => $record->fetched_at?->format('d.m.Y H:i')),
                ToggleColumn::make('is_featured')
                    ->label('На главной')
                    ->sortable(),
                ToggleColumn::make('is_hidden')
                    ->label('Скрыто')
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('listing_type')->label('Тип')->options(SavdexListing::TYPES),
                TernaryFilter::make('is_featured')->label('На главной'),
                TernaryFilter::make('is_hidden')->label('Скрыто'),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('open')
                    ->label('Открыть на SAVDEX')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn ($record) => $record->source_url, true),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                BulkAction::make('feature')
                    ->label('На главную')
                    ->icon('heroicon-o-star')
                    ->color('success')
                    ->action(fn (Collection $records) => $records->each->update(['is_featured' => true]))
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('hide')
                    ->label('Скрыть')
                    ->icon('heroicon-o-eye-slash')
                    ->color('warning')
                    ->action(fn (Collection $records) => $records->each->update(['is_hidden' => true]))
                    ->deselectRecordsAfterCompletion(),
            ]);
    }
}
