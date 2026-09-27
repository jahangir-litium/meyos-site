<?php

namespace App\Filament\Resources\Polls\Tables;

use App\Models\Poll;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')->label('Slug')->searchable(),
                TextColumn::make('question')
                    ->label('Вопрос')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['ru'] ?? '—') : $state)
                    ->limit(60),
                TextColumn::make('total_votes')
                    ->label('Голосов')
                    ->icon('heroicon-o-user-group')
                    ->sortable()
                    ->numeric()
                    ->alignRight(),
                IconColumn::make('is_active')->label('Активен')->boolean(),
                IconColumn::make('show_on_home')->label('На главной')->boolean(),
                TextColumn::make('ends_at')
                    ->label('Окончание')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('без ограничений')
                    ->color(fn (Poll $r) => $r->ends_at?->isPast() ? 'gray' : 'success'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')->label('Активные'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
