<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $sets = [
            Category::TYPE_NEWS => [
                ['slug' => 'residency',  'ru' => 'Резидентство',  'uz' => 'Rezidentlik',  'en' => 'Residency'],
                ['slug' => 'export',     'ru' => 'Экспорт',       'uz' => 'Eksport',      'en' => 'Export'],
                ['slug' => 'edujob',     'ru' => 'EduJob',        'uz' => 'EduJob',       'en' => 'EduJob'],
                ['slug' => 'regulation', 'ru' => 'Регулирование', 'uz' => 'Tartibga solish', 'en' => 'Regulation'],
                ['slug' => 'programs',   'ru' => 'Программы',     'uz' => 'Dasturlar',    'en' => 'Programs'],
            ],
            Category::TYPE_PARTNERS => [
                ['slug' => 'manufacturer', 'ru' => 'Производитель',   'uz' => 'Ishlab chiqaruvchi', 'en' => 'Manufacturer'],
                ['slug' => 'designer',     'ru' => 'Дизайн-студия',   'uz' => 'Dizayn studiyasi',  'en' => 'Design Studio'],
                ['slug' => 'supplier',     'ru' => 'Поставщик',       'uz' => 'Yetkazib beruvchi', 'en' => 'Supplier'],
                ['slug' => 'logistics',    'ru' => 'Логистика и розница', 'uz' => 'Logistika',  'en' => 'Logistics'],
                ['slug' => 'other',        'ru' => 'Другое',          'uz' => 'Boshqa',           'en' => 'Other'],
            ],
            Category::TYPE_EVENTS => [
                ['slug' => 'exhibition',  'ru' => 'Выставка',     'uz' => 'Koʻrgazma',  'en' => 'Exhibition'],
                ['slug' => 'forum',       'ru' => 'Форум',        'uz' => 'Forum',      'en' => 'Forum'],
                ['slug' => 'workshop',    'ru' => 'Мастер-класс', 'uz' => 'Master-klass', 'en' => 'Workshop'],
                ['slug' => 'conference',  'ru' => 'Конференция',  'uz' => 'Konferensiya', 'en' => 'Conference'],
                ['slug' => 'meeting',     'ru' => 'Встреча',      'uz' => 'Uchrashuv',  'en' => 'Meeting'],
            ],
        ];

        $sort = 0;
        foreach ($sets as $type => $rows) {
            foreach ($rows as $row) {
                Category::updateOrCreate(
                    ['type' => $type, 'slug' => $row['slug']],
                    [
                        'name' => ['ru' => $row['ru'], 'uz' => $row['uz'], 'en' => $row['en']],
                        'sort' => $sort++,
                        'is_published' => true,
                    ]
                );
            }
        }
    }
}
