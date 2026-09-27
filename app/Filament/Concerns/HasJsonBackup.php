<?php

namespace App\Filament\Concerns;

use App\Support\JsonBackup;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;

/**
 * Добавляет в ListRecords две кнопки: «Экспорт JSON» и «Импорт JSON».
 *
 * Юзкейс: локально ведём данные (новости, партнёры и т.д.), выгружаем JSON,
 * заходим в прод-админку и загружаем — данные обновляются по slug (upsert)
 * или полностью заменяются (replace для справочников без slug).
 *
 * Ресурс переопределяет свойства ниже, если нужно поведение отличное от
 * дефолтов: strategy=upsert, unique_key=slug.
 */
trait HasJsonBackup
{
    /**
     * Переопределяется в ListPage: 'upsert' (по slug) или 'replace' (truncate + insert).
     * Свойства не используются — PHP не даёт переопределить типизированное свойство трейта.
     */
    protected function backupStrategy(): string
    {
        return 'upsert';
    }

    protected function backupUniqueKey(): string
    {
        return 'slug';
    }

    protected function getBackupActions(): array
    {
        $modelClass = static::getResource()::getModel();
        $strategy   = $this->backupStrategy();
        $uniqueKey  = $this->backupUniqueKey();

        return [
            Action::make('export_json')
                ->label('Экспорт JSON')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () use ($modelClass, $strategy, $uniqueKey) {
                    $payload = JsonBackup::export($modelClass, [
                        'strategy'   => $strategy,
                        'unique_key' => $uniqueKey,
                    ]);
                    $slug = str(class_basename($modelClass))->kebab();
                    $filename = "{$slug}-" . now()->format('Y-m-d-His') . '.json';
                    return response()->streamDownload(function () use ($payload) {
                        echo json_encode(
                            $payload,
                            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                        );
                    }, $filename, ['Content-Type' => 'application/json; charset=UTF-8']);
                }),

            Action::make('import_json')
                ->label('Импорт JSON')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->modalHeading('Загрузить JSON-выгрузку')
                ->modalDescription($strategy === 'replace'
                    ? 'Все записи этого раздела будут удалены, затем созданы заново из файла. Медиа-файлы (изображения) не переносятся.'
                    : 'Записи с совпадающим slug будут обновлены, новые — созданы. Существующие записи, которых нет в файле, останутся. Медиа-файлы не переносятся.')
                ->form([
                    FileUpload::make('file')
                        ->label('JSON-файл')
                        ->acceptedFileTypes(['application/json'])
                        ->required()
                        ->storeFiles(false),
                ])
                ->action(function (array $data) use ($modelClass, $strategy, $uniqueKey) {
                    /** @var \Illuminate\Http\UploadedFile $file */
                    $file = $data['file'];
                    $raw  = file_get_contents($file->getRealPath());
                    $payload = json_decode($raw, true);
                    if (!is_array($payload)) {
                        Notification::make()
                            ->title('Неверный формат')
                            ->body('Файл не является валидным JSON.')
                            ->danger()->send();
                        return;
                    }
                    try {
                        $result = JsonBackup::import($modelClass, $payload, [
                            'strategy'   => $strategy,
                            'unique_key' => $uniqueKey,
                        ]);
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Ошибка импорта')
                            ->body($e->getMessage())
                            ->danger()->send();
                        return;
                    }

                    $parts = [];
                    if ($result['created'] > 0) $parts[] = "создано {$result['created']}";
                    if ($result['updated'] > 0) $parts[] = "обновлено {$result['updated']}";
                    if ($result['deleted'] > 0) $parts[] = "удалено {$result['deleted']}";
                    if ($result['skipped'] > 0) $parts[] = "пропущено {$result['skipped']} (без ключа)";

                    Notification::make()
                        ->title('Импорт завершён')
                        ->body(implode(', ', $parts) ?: 'Изменений нет')
                        ->success()->send();
                }),
        ];
    }
}
