<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
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

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')->disk('public')->label('Фото'),
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
                Action::make('view')
                    ->label('На сайте')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (?\App\Models\Event $r) => $r ? url('/events/' . $r->slug) : null, shouldOpenInNewTab: true)
                    ->visible(fn (?\App\Models\Event $r) => $r && $r->is_published),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label('Опубликовать')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_published' => true]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('unpublish')
                        ->label('Снять с публикации')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_published' => false]))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
