<?php

namespace App\Models\Concerns;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Расширение Spatie\LogsActivity с MEYOS-настройками:
 * - Логируем только реально изменённые поля (не спамим лог)
 * - Игнорируем updated_at и денормализованные счётчики
 * - Различаем события created/updated/deleted/restored
 */
trait LogsChanges
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept([
                'updated_at',
                'views_count_total',
                'views_count_30d',
                'last_viewed_at',
                'sort',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName(class_basename($this));
    }
}
