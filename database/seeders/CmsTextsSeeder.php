<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Дефолты для всех текстовых настроек, которые нужны публичным блейдам.
 * Идемпотентно: пропускает ключи, которые уже созданы (клиент их менял).
 *
 * Всё, что тут — это ответы на «Топ-10 дыр» из аудита:
 * заголовки секций главной, опции форм, футер, hero fallback,
 * H1 листингов, fab-ask, SEO fallback.
 */
class CmsTextsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // === HERO fallback (используется когда Page.hero_* пусто) ==========
            'hero.default_tag'  => ['group' => 'hero', 'value' => ['ru' => 'MEYOS', 'uz' => 'MEYOS', 'en' => 'MEYOS']],
            'hero.default_h1'   => ['group' => 'hero', 'value' => [
                'ru' => 'Ассоциация мебельщиков Узбекистана',
                'uz' => 'Oʻzbekiston mebel assotsiatsiyasi',
                'en' => 'Uzbekistan Furniture Association',
            ]],
            'hero.default_lead' => ['group' => 'hero', 'value' => [
                'ru' => 'Отраслевое B2B-объединение производителей и поставщиков.',
                'uz' => 'Ishlab chiqaruvchilar va yetkazib beruvchilarning tarmoq boʻyicha B2B birlashmasi.',
                'en' => 'Industry B2B alliance of manufacturers and suppliers.',
            ]],

            // === HOME section headings ========================================
            'home.h2_benefits' => ['group' => 'home', 'value' => [
                'ru' => 'Преимущества членства',
                'uz' => 'Aʼzolik afzalliklari',
                'en' => 'Benefits of membership',
            ]],
            'home.h2_problems' => ['group' => 'home', 'value' => [
                'ru' => 'Проблемы отрасли и наши решения',
                'uz' => 'Tarmoq muammolari va yechimlarimiz',
                'en' => 'Industry challenges — and our answers',
            ]],
            'home.h2_cases' => ['group' => 'home', 'value' => [
                'ru' => 'Кейсы наших резидентов',
                'uz' => 'Rezidentlarimizning muvaffaqiyat hikoyalari',
                'en' => 'Success stories from our residents',
            ]],
            'home.h2_programs' => ['group' => 'home', 'value' => [
                'ru' => 'Программы ассоциации',
                'uz' => 'Assotsiatsiya dasturlari',
                'en' => 'Association programs',
            ]],
            'home.h2_taxes' => ['group' => 'home', 'value' => [
                'ru' => 'Налоговые льготы резидента',
                'uz' => 'Rezidentlar uchun soliq imtiyozlari',
                'en' => 'Resident tax benefits',
            ]],
            'home.h2_join_steps' => ['group' => 'home', 'value' => [
                'ru' => 'Как стать резидентом',
                'uz' => 'Rezident boʻlish qadamlari',
                'en' => 'How to become a resident',
            ]],
            'home.h2_partners' => ['group' => 'home', 'value' => [
                'ru' => 'Наши партнёры и резиденты',
                'uz' => 'Hamkorlarimiz va rezidentlarimiz',
                'en' => 'Our partners and residents',
            ]],
            'home.h2_events' => ['group' => 'home', 'value' => [
                'ru' => 'Ближайшие мероприятия',
                'uz' => 'Yaqin kunlardagi tadbirlar',
                'en' => 'Upcoming events',
            ]],
            'home.h2_news' => ['group' => 'home', 'value' => [
                'ru' => 'Новости отрасли',
                'uz' => 'Tarmoq yangiliklari',
                'en' => 'Industry news',
            ]],
            'home.h2_faq' => ['group' => 'home', 'value' => [
                'ru' => 'Часто задаваемые вопросы',
                'uz' => 'Koʻp beriladigan savollar',
                'en' => 'Frequently asked questions',
            ]],

            // === TAX table headers ============================================
            'home.tax_th_param'    => ['group' => 'home', 'value' => ['ru' => 'Параметр',            'uz' => 'Parametr',           'en' => 'Parameter']],
            'home.tax_th_standard' => ['group' => 'home', 'value' => ['ru' => 'Стандартная ставка',   'uz' => 'Standart stavka',    'en' => 'Standard rate']],
            'home.tax_th_resident' => ['group' => 'home', 'value' => ['ru' => 'Для резидента MEYOS',  'uz' => 'MEYOS rezidenti uchun','en' => 'MEYOS resident']],
            'home.tax_th_saving'   => ['group' => 'home', 'value' => ['ru' => 'Экономия',             'uz' => 'Tejamkorlik',         'en' => 'Savings']],

            // === CTA-баннеры ===================================================
            'cta.home_h2' => ['group' => 'cta', 'value' => [
                'ru' => 'Если вы в мебельном бизнесе — ваше место в MEYOS',
                'uz' => 'Mebel biznesida boʻlsangiz — sizning oʻrningiz MEYOSda',
                'en' => 'In the furniture business? Your place is in MEYOS',
            ]],
            'cta.home_lead' => ['group' => 'cta', 'value' => [
                'ru' => 'Оставьте заявку — менеджер свяжется в течение одного рабочего дня.',
                'uz' => 'Ariza qoldiring — menejer bir ish kuni ichida bogʻlanadi.',
                'en' => 'Apply — a manager will reach out within one business day.',
            ]],
            'cta.home_button' => ['group' => 'cta', 'value' => [
                'ru' => 'Оставить заявку', 'uz' => 'Ariza qoldirish', 'en' => 'Apply',
            ]],
            'cta.partner_h2' => ['group' => 'cta', 'value' => [
                'ru' => 'Хотите стать резидентом MEYOS?',
                'uz' => 'MEYOS aʼzosi boʻlishni istaysizmi?',
                'en' => 'Want to become a MEYOS member?',
            ]],
            'cta.programs_h2' => ['group' => 'cta', 'value' => [
                'ru' => 'Получите доступ ко всем программам MEYOS',
                'uz' => 'MEYOS ning barcha dasturlariga kirish oling',
                'en' => 'Get access to all MEYOS programs',
            ]],

            // === FOOTER =======================================================
            'footer.tagline' => ['group' => 'footer', 'value' => [
                'ru' => 'Ассоциация мебельщиков Узбекистана. IT и стратегический партнёр мебельного бизнеса.',
                'uz' => 'Oʻzbekiston mebel assotsiatsiyasi. Mebel biznesining IT va strategik hamkori.',
                'en' => 'Uzbekistan Furniture Association. IT and strategic partner of the furniture industry.',
            ]],
            'footer.copyright' => ['group' => 'footer', 'value' => [
                'ru' => '© {year} MEYOS · Ассоциация мебельщиков Узбекистана',
                'uz' => '© {year} MEYOS · Oʻzbekiston mebel assotsiatsiyasi',
                'en' => '© {year} MEYOS · Uzbekistan Furniture Association',
            ]],
            'footer.privacy_label' => ['group' => 'footer', 'value' => [
                'ru' => 'Политика конфиденциальности', 'uz' => 'Maxfiylik siyosati', 'en' => 'Privacy policy',
            ]],

            // === NEWS / EVENTS listing hero ===================================
            'news.hero_tag' => ['group' => 'listings', 'value' => ['ru' => 'Новости', 'uz' => 'Yangiliklar', 'en' => 'News']],
            'news.hero_h1'  => ['group' => 'listings', 'value' => [
                'ru' => 'Что происходит в мебельной индустрии Узбекистана',
                'uz' => 'Oʻzbekiston mebel sanoatida nima boʻlmoqda',
                'en' => 'What\'s happening in Uzbekistan\'s furniture industry',
            ]],
            'news.search_placeholder' => ['group' => 'listings', 'value' => [
                'ru' => 'Поиск по новостям…', 'uz' => 'Yangiliklar boʻyicha qidiruv…', 'en' => 'Search news…',
            ]],
            'events.hero_tag' => ['group' => 'listings', 'value' => ['ru' => 'Мероприятия', 'uz' => 'Tadbirlar', 'en' => 'Events']],
            'events.hero_h1'  => ['group' => 'listings', 'value' => [
                'ru' => 'Форумы, выставки и деловые встречи',
                'uz' => 'Forumlar, koʻrgazmalar va biznes uchrashuvlari',
                'en' => 'Forums, exhibitions and business meetings',
            ]],

            // === FAB (плавающая кнопка «Задайте вопрос») ======================
            'fab.title' => ['group' => 'fab', 'value' => [
                'ru' => 'Задайте вопрос', 'uz' => 'Savol bering', 'en' => 'Ask a question',
            ]],
            'fab.subtitle' => ['group' => 'fab', 'value' => [
                'ru' => 'Ответим в течение рабочего дня.',
                'uz' => 'Bir ish kuni ichida javob beramiz.',
                'en' => 'We reply within one business day.',
            ]],
            'fab.placeholder_name' => ['group' => 'fab', 'value' => ['ru' => 'Ваше имя', 'uz' => 'Ismingiz', 'en' => 'Your name']],
            'fab.placeholder_phone'=> ['group' => 'fab', 'value' => ['ru' => 'Телефон', 'uz' => 'Telefon', 'en' => 'Phone']],
            'fab.placeholder_message' => ['group' => 'fab', 'value' => ['ru' => 'Ваш вопрос', 'uz' => 'Savolingiz', 'en' => 'Your question']],
            'fab.submit' => ['group' => 'fab', 'value' => ['ru' => 'Отправить', 'uz' => 'Yuborish', 'en' => 'Send']],

            // === SEO fallback ==================================================
            'seo.default_title' => ['group' => 'seo', 'value' => [
                'ru' => 'MEYOS — Ассоциация мебельщиков Узбекистана',
                'uz' => 'MEYOS — Oʻzbekiston mebel assotsiatsiyasi',
                'en' => 'MEYOS — Uzbekistan Furniture Association',
            ]],
            'seo.default_description' => ['group' => 'seo', 'value' => [
                'ru' => 'B2B-объединение мебельной отрасли Узбекистана: льготы, экспорт, образование, партнёрства.',
                'uz' => 'Oʻzbekiston mebel sanoatining B2B birlashmasi: imtiyozlar, eksport, taʼlim, hamkorliklar.',
                'en' => 'Uzbekistan\'s furniture industry B2B alliance: benefits, exports, education, partnerships.',
            ]],

            // === Form options (translatable) ==================================
            'form.categories' => ['group' => 'forms', 'value' => [
                ['value' => 'production',  'label' => ['ru' => 'Производство мебели',            'uz' => 'Mebel ishlab chiqarish',           'en' => 'Furniture manufacturing']],
                ['value' => 'design',      'label' => ['ru' => 'Дизайн-студия',                  'uz' => 'Dizayn studiyasi',                 'en' => 'Design studio']],
                ['value' => 'materials',   'label' => ['ru' => 'Поставщик материалов и фурнитуры','uz' => 'Material va furnitura yetkazib beruvchi','en' => 'Materials and hardware supplier']],
                ['value' => 'logistics',   'label' => ['ru' => 'Логистика и розница',            'uz' => 'Logistika va chakana savdo',        'en' => 'Logistics and retail']],
                ['value' => 'other',       'label' => ['ru' => 'Другое',                         'uz' => 'Boshqa',                            'en' => 'Other']],
            ]],
            'form.volumes' => ['group' => 'forms', 'value' => [
                ['value' => 'up_5k',    'label' => ['ru' => 'До 5 000 изделий/год', 'uz' => '5 000 gacha mahsulot/yil',  'en' => 'Up to 5,000 items/year']],
                ['value' => '5_20k',    'label' => ['ru' => '5 000–20 000',          'uz' => '5 000–20 000',              'en' => '5,000–20,000']],
                ['value' => '20_50k',   'label' => ['ru' => '20 000–50 000',         'uz' => '20 000–50 000',             'en' => '20,000–50,000']],
                ['value' => 'over_50k', 'label' => ['ru' => 'Более 50 000',          'uz' => '50 000 dan koʻp',           'en' => 'Over 50,000']],
                ['value' => 'unknown',  'label' => ['ru' => 'Уточнить',              'uz' => 'Aniqlashtirish kerak',      'en' => 'To be confirmed']],
            ]],
            'form.contact_topics' => ['group' => 'forms', 'value' => [
                ['value' => 'membership',   'label' => ['ru' => 'Вступление в ассоциацию',   'uz' => 'Assotsiatsiyaga aʼzo boʻlish',    'en' => 'Joining the association']],
                ['value' => 'edujob',       'label' => ['ru' => 'Программа EduJob',           'uz' => 'EduJob dasturi',                  'en' => 'EduJob program']],
                ['value' => 'partnership',  'label' => ['ru' => 'Партнёрство / медиа',        'uz' => 'Hamkorlik / media',               'en' => 'Partnership / media']],
                ['value' => 'export',       'label' => ['ru' => 'Экспорт и логистика',        'uz' => 'Eksport va logistika',            'en' => 'Export and logistics']],
                ['value' => 'benefits',     'label' => ['ru' => 'Налоговые льготы',           'uz' => 'Soliq imtiyozlari',               'en' => 'Tax benefits']],
                ['value' => 'other',        'label' => ['ru' => 'Другое',                     'uz' => 'Boshqa',                          'en' => 'Other']],
            ]],
        ];

        foreach ($defaults as $key => $meta) {
            // Пропускаем, если ключ уже настроен админом
            if (Setting::where('key', $key)->exists()) {
                continue;
            }
            Setting::put($key, $meta['value'], $meta['group']);
        }
    }
}
