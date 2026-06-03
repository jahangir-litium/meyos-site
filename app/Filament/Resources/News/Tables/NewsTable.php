<?php

namespace App\Filament\Resources\News\Tables;

use App\Models\News;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')->disk('public')->label('Фото'),
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->limit(60)
                    ->searchable(query: fn ($q, $s) => $q->where('title->ru', 'like', "%$s%")),
                TextColumn::make('category')
                    ->label('Категория')
                    ->badge()
                    ->formatStateUsing(fn ($s) => News::CATEGORIES[$s] ?? $s),
                TextColumn::make('published_at')->label('Дата')->date('d.m.Y')->sortable(),
                IconColumn::make('is_featured')->label('Главная')->boolean(),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('category')->options(News::CATEGORIES)->label('Категория'),
                TernaryFilter::make('is_published')->label('Опубликована'),
                TernaryFilter::make('is_featured')->label('Главная'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('На сайте')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (?News $r) => $r ? url('/news/' . $r->slug) : null, shouldOpenInNewTab: true)
                    ->visible(fn (?News $r) => $r && $r->is_published),
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
