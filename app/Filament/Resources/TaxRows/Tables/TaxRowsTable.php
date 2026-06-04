<?php

namespace App\Filament\Resources\TaxRows\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TaxRowsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('parameter')->label('Параметр')->limit(40),
                TextColumn::make('standard_rate')->label('Стандарт'),
                TextColumn::make('resident_rate')->label('Резидент'),
                TextColumn::make('savings')->label('Экономия'),
                TextColumn::make('sort')->sortable(),
            ])
            ->reorderable('sort')
            ->filters([
                //,
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
