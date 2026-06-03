<?php

namespace App\Models\Concerns;

trait HasSorting
{
    protected static function bootHasSorting(): void
    {
        static::creating(function ($model) {
            if (empty($model->sort)) {
                $model->sort = (static::max('sort') ?? 0) + 1;
            }
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
