<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')->collection('cover')->label('Фото'),
                TextColumn::make('title')->label('Название')->limit(50)->searchable(query: fn ($q, $s) => $q->where('title->ru', 'like', "%$s%")),
                TextColumn::make('category')->label('Категория')->badge()->formatStateUsing(fn ($s) => \App\Models\Event::CATEGORIES[$s] ?? $s),
                TextColumn::make('event_date')->label('Дата')->date('d.m.Y')->sortable(),
                TextColumn::make('city')->label('Город'),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')->options(\App\Models\Event::CATEGORIES), TernaryFilter::make('is_published')->label('Опубликовано'),
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
