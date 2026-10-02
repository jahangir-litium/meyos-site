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
                // Используем те же slug-ы что и в MeyosContentSeeder для партнёров.
                ['slug' => 'production',   'ru' => 'Производство',    'uz' => 'Ishlab chiqarish', 'en' => 'Production'],
                ['slug' => 'design',       'ru' => 'Дизайн',          'uz' => 'Dizayn',           'en' => 'Design'],
                ['slug' => 'materials',    'ru' => 'Материалы',       'uz' => 'Materiallar',      'en' => 'Materials'],
                ['slug' => 'logistics',    'ru' => 'Логистика',       'uz' => 'Logistika',        'en' => 'Logistics'],
                ['slug' => 'gov',          'ru' => 'Государственный', 'uz' => 'Davlat',           'en' => 'Government'],
                ['slug' => 'finance',      'ru' => 'Финансы',         'uz' => 'Moliya',           'en' => 'Finance'],
            ],
            Category::TYPE_LEGISLATION => [
                ['slug' => 'tariffs',       'ru' => 'Пошлины и импорт',    'uz' => 'Bojxona va import',       'en' => 'Tariffs and imports'],
                ['slug' => 'taxes',         'ru' => 'Налоги и льготы',     'uz' => 'Soliqlar va imtiyozlar',   'en' => 'Taxes and benefits'],
                ['slug' => 'certification', 'ru' => 'Сертификация',        'uz' => 'Sertifikatsiya',           'en' => 'Certification'],
                ['slug' => 'export',        'ru' => 'Экспорт',             'uz' => 'Eksport',                  'en' => 'Exports'],
                ['slug' => 'hr',            'ru' => 'Кадры и обучение',    'uz' => 'Kadrlar va taʼlim',        'en' => 'Workforce and training'],
                ['slug' => 'clusters',      'ru' => 'Мебельные кластеры',  'uz' => 'Mebel klasterlari',        'en' => 'Furniture clusters'],
                ['slug' => 'other',         'ru' => 'Другое',              'uz' => 'Boshqa',                   'en' => 'Other'],
            ],
            Category::TYPE_EVENTS => [
                ['slug' => 'exhibition',         'ru' => 'Выставка',          'uz' => 'Koʻrgazma',     'en' => 'Exhibition'],
                ['slug' => 'forum',              'ru' => 'Форум',             'uz' => 'Forum',         'en' => 'Forum'],
                ['slug' => 'workshop',           'ru' => 'Мастер-класс',      'uz' => 'Master-klass',  'en' => 'Workshop'],
                ['slug' => 'conference',         'ru' => 'Конференция',       'uz' => 'Konferensiya',  'en' => 'Conference'],
                ['slug' => 'meeting',            'ru' => 'Встреча',           'uz' => 'Uchrashuv',     'en' => 'Meeting'],
                // Legacy slug-ы
                ['slug' => 'export',             'ru' => 'Экспортная миссия', 'uz' => 'Eksport missiyasi', 'en' => 'Export Mission'],
                ['slug' => 'edujob',             'ru' => 'EduJob',            'uz' => 'EduJob',        'en' => 'EduJob'],
                ['slug' => 'round-table',        'ru' => 'Круглый стол',      'uz' => 'Davra suhbati', 'en' => 'Round Table'],
                ['slug' => 'business-breakfast', 'ru' => 'Деловой завтрак',   'uz' => 'Ishbilarmon nonushtasi', 'en' => 'Business Breakfast'],
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
