<?php

namespace App\Filament\Resources\MembershipApplications\Tables;

use App\Models\MembershipApplication;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MembershipApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Дата')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('company')->label('Компания')->searchable(),
                TextColumn::make('name')->label('Имя')->searchable(),
                TextColumn::make('phone')->label('Телефон')->copyable(),
                TextColumn::make('utm_source')->label('UTM')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'new'         => 'warning',
                        'in_progress' => 'info',
                        'approved'    => 'success',
                        'rejected'    => 'danger',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn ($s) => MembershipApplication::STATUSES[$s] ?? $s),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(MembershipApplication::STATUSES),
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
        $filename = 'applications-' . now()->format('Y-m-d_H-i') . '.csv';
        return response()->streamDownload(function () {
            $h = fopen('php://output', 'w');
            fwrite($h, "\xEF\xBB\xBF"); // BOM для Excel UTF-8
            fputcsv($h, [
                'Дата', 'Компания', 'Контакт', 'Email', 'Телефон',
                'Категория', 'Объём', 'Комментарий', 'Статус', 'Источник',
                'UTM source', 'UTM medium', 'UTM campaign',
            ]);
            MembershipApplication::orderByDesc('created_at')->chunk(500, function ($rows) use ($h) {
                foreach ($rows as $r) {
                    fputcsv($h, [
                        $r->created_at?->format('d.m.Y H:i'),
                        $r->company, $r->name, $r->email, $r->phone,
                        $r->category, $r->volume, $r->message,
                        MembershipApplication::STATUSES[$r->status] ?? $r->status,
                        $r->source_page,
                        $r->utm_source, $r->utm_medium, $r->utm_campaign,
                    ]);
                }
            });
            fclose($h);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
