<?php

namespace App\Filament\Resources\Partners\Tables;

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

class PartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_image')->disk('public')->label('Лого'),
                TextColumn::make('name')->label('Название')->searchable(query: fn ($q, $s) => $q->where('name->ru', 'like', "%$s%")),
                TextColumn::make('category')->label('Категория')->badge()->formatStateUsing(fn ($s) => \App\Models\Partner::CATEGORIES[$s] ?? $s),
                IconColumn::make('show_on_home')->label('На главной')->boolean(),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
                TextColumn::make('sort')->label('Порядок')->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')->options(\App\Models\Partner::CATEGORIES),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('На сайте')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn () => url('/partners'), shouldOpenInNewTab: true),
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
