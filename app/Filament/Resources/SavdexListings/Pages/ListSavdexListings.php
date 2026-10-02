<?php

namespace App\Filament\Resources\SavdexListings\Pages;

use App\Filament\Resources\SavdexListings\SavdexListingResource;
use App\Models\SavdexListing;
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
                ->modalDescription('Пойдём на savdex.uz, перечитаем мебельные объявления. Может занять до 60 секунд.')
                ->action(function () {
                    $parser = app(SavdexParser::class);
                    $stats  = $parser->sync();
                    $pruned = $parser->pruneExpired(60);
                    Notification::make()
                        ->title('Обновление завершено')
                        ->body("Найдено: {$stats['found_in_sitemap']} · загружено: {$stats['fetched']} · создано: {$stats['created']} · обновлено: {$stats['updated']} · удалено устаревших: {$pruned}")
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
