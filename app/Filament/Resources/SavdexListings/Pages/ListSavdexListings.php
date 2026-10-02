<?php

namespace App\Filament\Resources\SavdexListings\Pages;

use App\Filament\Resources\SavdexListings\SavdexListingResource;
use App\Jobs\FetchSavdexListingsJob;
use App\Services\SavdexParser;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSavdexListings extends ListRecords
{
    protected static string $resource = SavdexListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Обновить сейчас')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->requiresConfirmation()
                ->modalDescription('Запустим фоновую задачу: пойдём на savdex.uz и обновим мебельные объявления. Админка не блокируется, результат появится через 1–2 минуты — обновите страницу.')
                ->action(function () {
                    FetchSavdexListingsJob::dispatch();
                    Notification::make()
                        ->title('Задача запущена')
                        ->body('Парсер работает в фоне. Обновите страницу через 1–2 минуты, чтобы увидеть новые объявления.')
                        ->success()->send();
                }),
            Action::make('archive_old')
                ->label('Удалить старые (90+ дней)')
                ->icon('heroicon-o-trash')
                ->color('gray')
                ->requiresConfirmation()
                ->action(function () {
                    $n = app(SavdexParser::class)->pruneExpired(90);
                    Notification::make()
                        ->title("Удалено объявлений: {$n}")
                        ->success()->send();
                }),
        ];
    }
}
