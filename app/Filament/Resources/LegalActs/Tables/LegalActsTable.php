<?php

namespace App\Filament\Resources\LegalActs\Tables;

use App\Models\LegalAct;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class LegalActsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('act_number')
                    ->label('№')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—')
                    ->weight('semibold'),
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('category')
                    ->label('Категория')
                    ->badge()
                    ->formatStateUsing(fn ($state) => LegalAct::allCategories('ru')[$state] ?? $state),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'active'   => 'success',
                        'draft'    => 'warning',
                        'repealed' => 'gray',
                        default    => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => LegalAct::STATUSES[$state] ?? $state),
                TextColumn::make('act_date')
                    ->label('Дата')
                    ->date('d.m.Y')
                    ->sortable()
                    ->placeholder('—'),
                IconColumn::make('is_featured')
                    ->label('Главная')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Опубл.')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('sort', 'asc')
            ->reorderable('sort')
            ->filters([
                SelectFilter::make('category')->label('Категория')->options(LegalAct::allCategories('ru')),
                SelectFilter::make('status')->label('Статус')->options(LegalAct::STATUSES),
                TernaryFilter::make('is_published')->label('Опубликовано'),
                TernaryFilter::make('is_featured')->label('На главной'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([DeleteBulkAction::make()]);
    }
}
