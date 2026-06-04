<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Дата')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('name')->label('Имя')->searchable(),
                TextColumn::make('email')->label('Email')->searchable()->copyable(),
                TextColumn::make('topic')->label('Тема'),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'new'      => 'warning',
                        'replied'  => 'success',
                        'archived' => 'gray',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn ($s) => ContactMessage::STATUSES[$s] ?? $s),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(ContactMessage::STATUSES),
                TrashedFilter::make()->label('Корзина'),
            ])
            ->headerActions([
                Action::make('export_csv')
                    ->label('Экспорт CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(fn () => self::exportCsv()),
            ])
            ->recordActions([
                Action::make('mark_replied')
                    ->label('Ответил')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (?ContactMessage $r) => $r && $r->status !== 'replied' && !$r->trashed())
                    ->action(function (ContactMessage $r) {
                        $r->update(['status' => 'replied']);
                        Notification::make()->success()->title('Помечено как «Ответил»')->send();
                    }),
                Action::make('archive')
                    ->label('Архив')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->visible(fn (?ContactMessage $r) => $r && $r->status !== 'archived' && !$r->trashed())
                    ->action(function (ContactMessage $r) {
                        $r->update(['status' => 'archived']);
                        Notification::make()->success()->title('В архив')->send();
                    }),
                Action::make('spam')
                    ->label('Спам')
                    ->icon('heroicon-o-shield-exclamation')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (?ContactMessage $r) => $r && !$r->trashed())
                    ->action(function (ContactMessage $r) {
                        $r->delete(); // soft delete — попадёт в корзину
                        Notification::make()->warning()->title('Удалено как спам (в корзине)')->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('mark_replied_bulk')
                        ->label('Пометить «Ответил»')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'replied']))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('archive_bulk')
                        ->label('В архив')
                        ->icon('heroicon-o-archive-box')
                        ->action(fn ($records) => $records->each->update(['status' => 'archived']))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function exportCsv(): StreamedResponse
    {
        $filename = 'contact-messages-' . now()->format('Y-m-d_H-i') . '.csv';
        return response()->streamDownload(function () {
            $h = fopen('php://output', 'w');
            fwrite($h, "\xEF\xBB\xBF"); // BOM — чтобы Excel правильно открыл UTF-8
            fputcsv($h, ['Дата', 'Имя', 'Компания', 'Email', 'Телефон', 'Тема', 'Сообщение', 'Статус']);
            ContactMessage::orderByDesc('created_at')->chunk(500, function ($rows) use ($h) {
                foreach ($rows as $r) {
                    fputcsv($h, [
                        $r->created_at?->format('d.m.Y H:i'),
                        $r->name, $r->company, $r->email, $r->phone, $r->topic, $r->message,
                        ContactMessage::STATUSES[$r->status] ?? $r->status,
                    ]);
                }
            });
            fclose($h);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
