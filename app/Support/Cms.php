<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Тонкий фасад над Setting для i18n-текстов, которые редактор
 * меняет из админки.
 *
 * Хранение: Setting.value — либо строка, либо ассоциативный массив
 * ['ru' => …, 'uz' => …, 'en' => …]. Метод text() возвращает вариант
 * для текущей локали с fallback на RU, а если и RU пусто — на $default.
 */
class Cms
{
    /** Локализованный текст из настроек. */
    public static function text(string $key, string $default = ''): string
    {
        $v = Setting::get($key);
        if ($v === null || $v === '' || $v === []) {
            return $default;
        }
        if (is_string($v)) {
            return $v;
        }
        if (is_array($v)) {
            $locale = app()->getLocale();
            return (string) ($v[$locale] ?? $v['ru'] ?? $default);
        }
        return $default;
    }

    /**
     * Список опций формы (например form.categories):
     * value → локализованный label.
     * Формат в БД: [['value' => 'production', 'label' => ['ru' => …, 'uz' => …, 'en' => …]], …]
     * Возвращает: ['production' => 'Производство мебели', …].
     *
     * Если ключ пуст — вернёт $fallback (карта value => label на RU).
     */
    public static function options(string $key, array $fallback = []): array
    {
        $rows = Setting::get($key);
        if (!is_array($rows) || $rows === []) {
            return $fallback;
        }
        $locale = app()->getLocale();
        $out = [];
        foreach ($rows as $row) {
            if (!isset($row['value'])) continue;
            $label = $row['label'] ?? null;
            $out[$row['value']] = is_array($label)
                ? ($label[$locale] ?? $label['ru'] ?? $row['value'])
                : (string) ($label ?: $row['value']);
        }
        return $out;
    }
}
