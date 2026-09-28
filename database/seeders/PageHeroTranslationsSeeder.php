<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Заполняет hero_h1, hero_lead, hero_tag у всех Page на 3 языках,
 * если поля пустые. Ручные значения не перезаписываем — если админ
 * уже написал свой заголовок, оставляем его.
 *
 * Раньше эти поля были пустыми, поэтому блейд возвращал захардкоженный
 * русский fallback на любой локали (UZ/EN всё равно видели русский).
 */
class PageHeroTranslationsSeeder extends Seeder
{
    private const LOCALES = ['ru', 'uz', 'en'];

    public function run(): void
    {
        $pages = [
            'about' => [
                'hero_tag'  => ['ru' => 'О компании',            'uz' => 'Kompaniya haqida',              'en' => 'About us'],
                'hero_h1'   => [
                    'ru' => 'Создаём инфраструктуру мебельной индустрии Узбекистана',
                    'uz' => 'Oʻzbekiston mebel sanoati infratuzilmasini yaratamiz',
                    'en' => 'Building the infrastructure of Uzbekistan\'s furniture industry',
                ],
                'hero_lead' => [
                    'ru' => 'MEYOS — некоммерческая ассоциация, объединяющая производителей, дизайнеров и поставщиков мебельной отрасли.',
                    'uz' => 'MEYOS — mebel tarmogʻining ishlab chiqaruvchilari, dizaynerlari va yetkazib beruvchilarini birlashtiruvchi notijorat uyushma.',
                    'en' => 'MEYOS is a non-profit association uniting furniture industry manufacturers, designers and suppliers.',
                ],
            ],
            'residency' => [
                'hero_tag'  => ['ru' => 'Особый индустриальный статус', 'uz' => 'Maxsus sanoat maqomi',   'en' => 'Special industrial status'],
                'hero_h1'   => [
                    'ru' => 'Резидентство MEYOS — льготы, защита интересов и доступ к рынку',
                    'uz' => 'MEYOS rezidentligi — imtiyozlar, manfaatlarni himoya qilish va bozorga kirish',
                    'en' => 'MEYOS residency — benefits, advocacy and market access',
                ],
                'hero_lead' => [
                    'ru' => 'Статус резидента даёт измеримую финансовую выгоду уже в первый год.',
                    'uz' => 'Rezident maqomi birinchi yildayoq oʻlchanadigan moliyaviy foyda beradi.',
                    'en' => 'Resident status delivers measurable financial benefits in the first year.',
                ],
            ],
            'programs' => [
                'hero_tag'  => ['ru' => 'Программы ассоциации', 'uz' => 'Assotsiatsiya dasturlari', 'en' => 'Association programs'],
                'hero_h1'   => [
                    'ru' => 'Проекты, которые запускает MEYOS для роста отрасли',
                    'uz' => 'MEYOS tarmoq rivoji uchun ishga tushirayotgan loyihalar',
                    'en' => 'Projects launched by MEYOS to grow the industry',
                ],
                'hero_lead' => [
                    'ru' => 'От кадровой программы EduJob до коллективных экспортных миссий.',
                    'uz' => 'EduJob kadrlar dasturidan tortib, jamoaviy eksport missiyalarigacha.',
                    'en' => 'From the EduJob workforce program to collective export missions.',
                ],
            ],
            'partners' => [
                'hero_tag'  => ['ru' => 'Экосистема MEYOS',      'uz' => 'MEYOS ekotizimi',        'en' => 'MEYOS ecosystem'],
                'hero_h1'   => [
                    'ru' => 'Партнёры и резиденты ассоциации',
                    'uz' => 'Uyushma hamkorlari va rezidentlari',
                    'en' => 'Association partners and residents',
                ],
                'hero_lead' => [
                    'ru' => 'Единая B2B-сеть мебельной отрасли Узбекистана.',
                    'uz' => 'Oʻzbekiston mebel tarmogʻining yagona B2B tarmogʻi.',
                    'en' => 'The unified B2B network of Uzbekistan\'s furniture industry.',
                ],
            ],
            'contacts' => [
                'hero_tag'  => ['ru' => 'Контакты',              'uz' => 'Aloqa',                  'en' => 'Contacts'],
                'hero_h1'   => [
                    'ru' => 'Свяжитесь с ассоциацией MEYOS',
                    'uz' => 'MEYOS uyushmasi bilan bogʻlaning',
                    'en' => 'Get in touch with the MEYOS association',
                ],
                'hero_lead' => [
                    'ru' => 'Менеджеры ассоциации отвечают на вопросы о резидентстве, льготах, программах и партнёрствах.',
                    'uz' => 'Uyushma menejerlari rezidentlik, imtiyozlar, dasturlar va hamkorliklar boʻyicha savollarga javob beradi.',
                    'en' => 'Association managers answer questions about residency, benefits, programs and partnerships.',
                ],
            ],
            // news и events используют @cms('news.hero_h1', …) и @cms('events.hero_h1', …),
            // они уже заполнены через CmsTextsSeeder. Page-запись для них не нужна.
        ];

        foreach ($pages as $slug => $fields) {
            $page = Page::firstOrCreate(['slug' => $slug], ['view' => 'pages.' . $slug, 'is_published' => true]);
            $changed = false;
            foreach ($fields as $field => $values) {
                foreach (self::LOCALES as $loc) {
                    $current = $page->getTranslation($field, $loc, false);
                    if (empty($current) && !empty($values[$loc])) {
                        $page->setTranslation($field, $loc, $values[$loc]);
                        $changed = true;
                    }
                }
            }
            if ($changed) {
                $page->saveQuietly();
                $this->command->info("Page /{$slug} — hero-переводы дозаполнены");
            }
        }
    }
}
