<?php

namespace App\Filament\Resources\Programs\Tables;

use App\Models\Program;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProgramsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Название')->searchable(query: fn ($q, $s) => $q->where('title->ru', 'like', "%$s%")),
                TextColumn::make('icon')->label('Иконка'),
                IconColumn::make('is_flagship')->label('Флагман')->boolean(),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
                TextColumn::make('sort')->sortable(),
            ])
            ->reorderable('sort')
            ->filters([
                TernaryFilter::make('is_published'),
                TrashedFilter::make()->label('Корзина'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('На сайте')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn () => url('/programs'), shouldOpenInNewTab: true)
                    ->visible(fn (?Program $r) => $r && !$r->trashed()),
                EditAction::make(),
                ReplicateAction::make()
                    ->label('Дублировать')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('info')
                    ->beforeReplicaSaved(function (Program $replica): void {
                        $replica->slug = $replica->slug . '-' . substr((string) microtime(true), -4);
                        $titles = $replica->getTranslations('title');
                        $titles['ru'] = ($titles['ru'] ?? 'Без названия') . ' (копия)';
                        $replica->setTranslations('title', $titles);
                        $replica->is_published = false;
                        $replica->is_flagship  = false;
                    })
                    ->successNotificationTitle('Программа продублирована'),
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
