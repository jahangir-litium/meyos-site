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
     * Если ключ пуст — вернётся $fallback, а если и он не задан — вшитый
     * дефолтный список для form.* (чтобы на свежем проде без seeder-а
     * список не показывался пустым). Это защита от «пустых select»,
     * которые пользователи видят до первого прогона CmsTextsSeeder.
     */
    public static function options(string $key, array $fallback = []): array
    {
        $rows = Setting::get($key);
        if (!is_array($rows) || $rows === []) {
            if ($fallback === []) {
                $fallback = self::builtinOptions($key);
            }
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

    /**
     * Встроенные дефолты для трёх основных списков-форм. Показываются,
     * если в Setting ничего не задано. Локализуются по текущей локали.
     */
    private static function builtinOptions(string $key): array
    {
        static $defaults = null;
        if ($defaults === null) {
            $defaults = [
                'form.categories' => [
                    'production' => ['ru' => 'Производство мебели',            'uz' => 'Mebel ishlab chiqarish',           'en' => 'Furniture manufacturing'],
                    'design'     => ['ru' => 'Дизайн-студия',                  'uz' => 'Dizayn studiyasi',                 'en' => 'Design studio'],
                    'materials'  => ['ru' => 'Поставщик материалов и фурнитуры','uz' => 'Material va furnitura yetkazib beruvchi','en' => 'Materials and hardware supplier'],
                    'logistics'  => ['ru' => 'Логистика и розница',            'uz' => 'Logistika va chakana savdo',        'en' => 'Logistics and retail'],
                    'other'      => ['ru' => 'Другое',                         'uz' => 'Boshqa',                            'en' => 'Other'],
                ],
                'form.volumes' => [
                    'up_5k'    => ['ru' => 'До 5 000 изделий/год', 'uz' => '5 000 gacha mahsulot/yil',  'en' => 'Up to 5,000 items/year'],
                    '5_20k'    => ['ru' => '5 000–20 000',          'uz' => '5 000–20 000',              'en' => '5,000–20,000'],
                    '20_50k'   => ['ru' => '20 000–50 000',         'uz' => '20 000–50 000',             'en' => '20,000–50,000'],
                    'over_50k' => ['ru' => 'Более 50 000',          'uz' => '50 000 dan koʻp',           'en' => 'Over 50,000'],
                    'unknown'  => ['ru' => 'Уточнить',              'uz' => 'Aniqlashtirish kerak',      'en' => 'To be confirmed'],
                ],
                'form.contact_topics' => [
                    'membership'  => ['ru' => 'Вступление в ассоциацию',   'uz' => 'Assotsiatsiyaga aʼzo boʻlish',    'en' => 'Joining the association'],
                    'edujob'      => ['ru' => 'Программа EduJob',           'uz' => 'EduJob dasturi',                  'en' => 'EduJob program'],
                    'partnership' => ['ru' => 'Партнёрство / медиа',        'uz' => 'Hamkorlik / media',               'en' => 'Partnership / media'],
                    'export'      => ['ru' => 'Экспорт и логистика',        'uz' => 'Eksport va logistika',            'en' => 'Export and logistics'],
                    'benefits'    => ['ru' => 'Налоговые льготы',           'uz' => 'Soliq imtiyozlari',               'en' => 'Tax benefits'],
                    'other'       => ['ru' => 'Другое',                     'uz' => 'Boshqa',                          'en' => 'Other'],
                ],
            ];
        }
        if (!isset($defaults[$key])) return [];
        $locale = app()->getLocale();
        $out = [];
        foreach ($defaults[$key] as $value => $labels) {
            $out[$value] = $labels[$locale] ?? $labels['ru'] ?? $value;
        }
        return $out;
    }
}
