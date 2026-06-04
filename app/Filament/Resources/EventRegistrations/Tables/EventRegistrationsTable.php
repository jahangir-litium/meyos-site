<?php

namespace App\Filament\Resources\EventRegistrations\Tables;

use App\Models\EventRegistration;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Дата')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('event_name')->label('Мероприятие'),
                TextColumn::make('company')->label('Компания')->searchable(),
                TextColumn::make('name')->label('Имя')->searchable(),
                TextColumn::make('phone')->label('Телефон')->copyable(),
                TextColumn::make('status')
                    ->label('Статус')->badge()
                    ->color(fn ($state) => match ($state) {
                        'new'       => 'warning',
                        'confirmed' => 'info',
                        'attended'  => 'success',
                        'no_show'   => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($s) => EventRegistration::STATUSES[$s] ?? $s),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(EventRegistration::STATUSES),
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
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function exportCsv(): StreamedResponse
    {
        $filename = 'event-registrations-' . now()->format('Y-m-d_H-i') . '.csv';
        return response()->streamDownload(function () {
            $h = fopen('php://output', 'w');
            fwrite($h, "\xEF\xBB\xBF");
            fputcsv($h, ['Дата', 'Мероприятие', 'Компания', 'Имя', 'Email', 'Телефон', 'Кол-во', 'Статус']);
            EventRegistration::orderByDesc('created_at')->chunk(500, function ($rows) use ($h) {
                foreach ($rows as $r) {
                    fputcsv($h, [
                        $r->created_at?->format('d.m.Y H:i'),
                        $r->event_name, $r->company, $r->name, $r->email, $r->phone,
                        $r->attendees_count,
                        EventRegistration::STATUSES[$r->status] ?? $r->status,
                    ]);
                }
            });
            fclose($h);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
