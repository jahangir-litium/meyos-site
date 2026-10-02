<?php

namespace App\Filament\Resources\SavdexListings\Pages;

use App\Filament\Concerns\HasJsonBackup;
use App\Filament\Resources\SavdexListings\SavdexListingResource;
use App\Services\SavdexParser;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\Process\Process;

class ListSavdexListings extends ListRecords
{
    use HasJsonBackup;

    protected static string $resource = SavdexListingResource::class;

    /** У SavdexListing уникальный маркер — external_id (ID объявления на savdex.uz),
     *  а не slug. При import JSON существующие записи матчатся по нему → upsert.
     *  Парсер потом тоже матчит по external_id → дубликатов не будет. */
    protected function backupUniqueKey(): string
    {
        return 'external_id';
    }

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getBackupActions(),
            Action::make('refresh')
                ->label('Обновить сейчас')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->requiresConfirmation()
                ->modalDescription('Запустим фоновую задачу: пойдём на savdex.uz и обновим мебельные объявления. Админка не блокируется — задача работает в отдельном процессе. Через 1–2 минуты обновите страницу.')
                ->action(function () {
                    // Запускаем artisan-команду в отдельном child-процессе.
                    // Веб-реквест отвечает мгновенно — не ждём завершения парсера.
                    // Работает одинаково на artisan serve, nginx+fpm, apache.
                    $phpBinary = (new \Symfony\Component\Process\PhpExecutableFinder())->find(false) ?: 'php';
                    $process = new Process(
                        [$phpBinary, base_path('artisan'), 'meyos:fetch-savdex'],
                        base_path()
                    );
                    $process->setTimeout(null);
                    // На Windows это открывает новое окно, что для фоновой задачи норм;
                    // на Linux просто фоновый процесс.
                    if (defined('PHP_WINDOWS_VERSION_MAJOR')) {
                        $process->setOptions(['create_new_console' => true]);
                    }
                    $process->disableOutput();
                    $process->start();

                    Notification::make()
                        ->title('Задача запущена')
                        ->body('Парсер работает в фоне (отдельный процесс). Обновите страницу через 1–2 минуты, чтобы увидеть новые объявления.')
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
