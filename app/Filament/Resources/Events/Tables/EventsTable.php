<?php

namespace App\Filament\Resources\Events\Tables;

use App\Models\Event;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')->disk('public')->label('Фото'),
                TextColumn::make('title')->label('Название')->limit(50)->searchable(query: fn ($q, $s) => $q->where('title->ru', 'like', "%$s%")),
                TextColumn::make('category')->label('Категория')->badge()->formatStateUsing(fn ($s) => Event::allCategories()[$s] ?? $s),
                TextColumn::make('event_date')->label('Дата')->date('d.m.Y')->sortable(),
                TextColumn::make('city')->label('Город'),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
            ])
            ->reorderable('sort')
            ->defaultSort('event_date', 'desc')
            ->filters([
                SelectFilter::make('category')->options(Event::allCategories()),
                TernaryFilter::make('is_published')->label('Опубликовано'),
                TrashedFilter::make()->label('Корзина'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('На сайте')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (?Event $r) => $r ? url('/events/' . $r->slug) : null, shouldOpenInNewTab: true)
                    ->visible(fn (?Event $r) => $r && $r->is_published && !$r->trashed()),
                EditAction::make(),
                ReplicateAction::make()
                    ->label('Дублировать')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('info')
                    ->beforeReplicaSaved(function (Event $replica): void {
                        $replica->slug = $replica->slug . '-' . substr((string) microtime(true), -4);
                        $titles = $replica->getTranslations('title');
                        $titles['ru'] = ($titles['ru'] ?? 'Без названия') . ' (копия)';
                        $replica->setTranslations('title', $titles);
                        $replica->is_published = false;
                        $replica->is_featured  = false;
                    })
                    ->successNotificationTitle('Мероприятие продублировано'),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
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
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
