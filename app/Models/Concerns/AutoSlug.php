<?php

namespace App\Models\Concerns;

use App\Support\Slugifier;

/**
 * Если slug пустой при сохранении — генерирует его автоматически
 * из source-поля (по умолчанию `title`, RU-перевод).
 *
 * Если slug-кандидат уже занят — добавляет суффикс -2, -3, …
 */
trait AutoSlug
{
    protected static function bootAutoSlug(): void
    {
        static::saving(function ($model) {
            if (!empty($model->slug)) return;

            $sourceField = $model->autoSlugFrom ?? 'title';

            // Берём перевод RU из переводимого поля, либо обычную строку
            $sourceValue = $model->{$sourceField};
            if (is_array($sourceValue)) {
                $sourceValue = $sourceValue['ru'] ?? reset($sourceValue);
            } elseif (method_exists($model, 'getTranslation')) {
                try {
                    $sourceValue = $model->getTranslation($sourceField, 'ru', false) ?: $sourceValue;
                } catch (\Throwable $e) { /* поле не translatable */ }
            }

            $base = Slugifier::make((string) $sourceValue);
            if ($base === '') return; // нечего генерить

            $slug = $base;
            $i = 2;
            while (static::query()
                ->where('slug', $slug)
                ->when($model->exists, fn ($q) => $q->where('id', '!=', $model->id))
                ->exists()
            ) {
                $slug = $base . '-' . $i++;
            }
            $model->slug = $slug;
        });
    }
}
