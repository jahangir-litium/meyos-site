<?php

namespace App\Filament\Resources\BusinessCases\Tables;

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

class BusinessCasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')->disk('public')->label('Фото'),
                TextColumn::make('title')->label('Заголовок')->limit(50)->searchable(query: fn ($q, $s) => $q->where('title->ru', 'like', "%$s%")),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
                TextColumn::make('sort')->label('Порядок')->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_published'),
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
