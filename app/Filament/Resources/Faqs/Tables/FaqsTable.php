<?php

namespace App\Filament\Resources\Faqs\Tables;

use App\Models\Faq;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->label('Вопрос')
                    ->wrap()
                    ->limit(120)
                    ->getStateUsing(fn (Faq $r) => $r->getTranslation('question', 'ru', false) ?: '— нет перевода —')
                    ->searchable(query: fn ($q, $s) => $q->where('question->ru', 'like', "%$s%")),
                TextColumn::make('page_slug')
                    ->label('Страница')
                    ->badge()
                    ->formatStateUsing(fn ($s) => [
                        'home' => 'Главная',
                        'residency' => 'Резидентство',
                        'programs' => 'Программы',
                        'about' => 'О компании',
                        'partners' => 'Партнёры',
                        'contacts' => 'Контакты',
                    ][$s] ?? $s),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
                TextColumn::make('sort')->label('Порядок')->sortable(),
            ])
            ->reorderable('sort')
            ->defaultSort('sort')
            ->filters([
                SelectFilter::make('page_slug')->label('Страница')->options([
                    'home' => 'Главная', 'residency' => 'Резидентство', 'programs' => 'Программы',
                    'about' => 'О компании', 'partners' => 'Партнёры', 'contacts' => 'Контакты',
                ]),
                TernaryFilter::make('is_published')->label('Опубликован'),
            ])
            ->recordActions([
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
