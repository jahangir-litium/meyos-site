<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Универсальный JSON-экспорт/импорт для Eloquent-моделей.
 *
 * Экспорт: массив записей со всеми колонками БД (кроме id/timestamps
 * при upsert-стратегии). Translatable-поля Spatie выгружаются как
 * ассоциативные массивы {ru: "...", uz: "...", en: "..."} — так их можно
 * читать глазами и править в git.
 *
 * Импорт: две стратегии:
 *  - "upsert"       — matcher по уникальному ключу (обычно slug); id не переносим,
 *                      обновляем существующие записи, создаём новые.
 *  - "replace"      — TRUNCATE + insert; подходит для справочников без slug.
 */
class JsonBackup
{
    /** Колонки, которые никогда не пишем в JSON (генерируются автоматически). */
    protected const IGNORE_COLUMNS = ['created_at', 'updated_at', 'deleted_at'];

    public static function export(string $modelClass, array $options = []): array
    {
        /** @var Model $sample */
        $sample = new $modelClass;
        $table  = $sample->getTable();

        $strategy   = $options['strategy'] ?? 'upsert';
        $uniqueKey  = $options['unique_key'] ?? 'slug';

        $rows = $modelClass::query()
            ->when(method_exists($sample, 'ordered'), fn ($q) => $q->ordered())
            ->get();

        $data = $rows->map(function (Model $row) use ($strategy, $uniqueKey) {
            $arr = $row->attributesToArray();
            foreach (self::IGNORE_COLUMNS as $col) {
                unset($arr[$col]);
            }
            // При upsert-стратегии id не имеет смысла (в другом окружении будет свой)
            if ($strategy === 'upsert') {
                unset($arr['id']);
            }
            return $arr;
        })->all();

        return [
            'meta' => [
                'model'      => $modelClass,
                'table'      => $table,
                'strategy'   => $strategy,
                'unique_key' => $strategy === 'upsert' ? $uniqueKey : null,
                'count'      => count($data),
                'exported_at'=> now()->toIso8601String(),
                'app'        => config('app.name'),
            ],
            'data' => $data,
        ];
    }

    public static function import(string $modelClass, array $payload, array $options = []): array
    {
        if (!isset($payload['data']) || !is_array($payload['data'])) {
            throw new \InvalidArgumentException('Некорректный JSON: нет ключа "data".');
        }
        $data = $payload['data'];

        // Стратегия из payload имеет приоритет над дефолтом (полезно если пользователь
        // случайно импортирует upsert-выгрузку в модель без slug)
        $strategy   = $payload['meta']['strategy']   ?? $options['strategy']   ?? 'upsert';
        $uniqueKey  = $payload['meta']['unique_key'] ?? $options['unique_key'] ?? 'slug';

        /** @var Model $sample */
        $sample = new $modelClass;
        $table  = $sample->getTable();
        $validCols = Schema::getColumnListing($table);

        // Отфильтруем колонки, которых нет в текущей БД (миграции могли разойтись)
        $data = array_map(function ($row) use ($validCols) {
            return array_intersect_key($row, array_flip($validCols));
        }, $data);

        $result = ['created' => 0, 'updated' => 0, 'deleted' => 0, 'skipped' => 0];

        DB::transaction(function () use ($modelClass, $data, $strategy, $uniqueKey, &$result, $sample) {
            if ($strategy === 'replace') {
                // TRUNCATE не работает в транзакции SQLite → используем delete()
                $deleted = $modelClass::query()->delete();
                $result['deleted'] = $deleted;
                foreach ($data as $row) {
                    unset($row['id']); // при replace тоже игнорируем id
                    $modelClass::create($row);
                    $result['created']++;
                }
                return;
            }

            // upsert по уникальному ключу
            foreach ($data as $row) {
                if (empty($row[$uniqueKey])) {
                    $result['skipped']++;
                    continue;
                }
                $existing = $modelClass::query()->where($uniqueKey, $row[$uniqueKey])->first();
                if ($existing) {
                    $existing->fill($row)->save();
                    $result['updated']++;
                } else {
                    $modelClass::create($row);
                    $result['created']++;
                }
            }
        });

        return $result;
    }
}
