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
            // === НАВИГАЦИЯ (header + mobile) =================================
            'nav.about'        => ['group' => 'nav', 'value' => ['ru' => 'О компании',    'uz' => 'Kompaniya haqida', 'en' => 'About']],
            'nav.residency'    => ['group' => 'nav', 'value' => ['ru' => 'Резидентство',  'uz' => 'Rezidentlik',       'en' => 'Residency']],
            'nav.programs'     => ['group' => 'nav', 'value' => ['ru' => 'Программы',     'uz' => 'Dasturlar',         'en' => 'Programs']],
            'nav.partners'     => ['group' => 'nav', 'value' => ['ru' => 'Партнёры',      'uz' => 'Hamkorlar',         'en' => 'Partners']],
            'nav.events'       => ['group' => 'nav', 'value' => ['ru' => 'Мероприятия',   'uz' => 'Tadbirlar',         'en' => 'Events']],
            'nav.news'         => ['group' => 'nav', 'value' => ['ru' => 'Новости',       'uz' => 'Yangiliklar',       'en' => 'News']],
            'nav.contacts'     => ['group' => 'nav', 'value' => ['ru' => 'Контакты',      'uz' => 'Kontaktlar',        'en' => 'Contacts']],
            'nav.history_child'     => ['group' => 'nav', 'value' => ['ru' => 'История ассоциации','uz' => 'Uyushma tarixi',    'en' => 'History']],
            'nav.legislation_child' => ['group' => 'nav', 'value' => ['ru' => 'Законодательство', 'uz' => 'Qonunchilik',       'en' => 'Legislation']],
            'nav.projects_child'    => ['group' => 'nav', 'value' => ['ru' => 'Проекты',           'uz' => 'Loyihalar',          'en' => 'Projects']],
            'nav.listings_child'    => ['group' => 'nav', 'value' => ['ru' => 'Объявления',        'uz' => 'Eʼlonlar',           'en' => 'Listings']],
            'nav.join_cta'          => ['group' => 'nav', 'value' => ['ru' => 'Вступить',          'uz' => 'Aʼzo boʻlish',      'en' => 'Join']],

            // === FOOTER колонки ==============================================
            'footer.col_sections'   => ['group' => 'footer', 'value' => ['ru' => 'Разделы',    'uz' => 'Boʻlimlar',  'en' => 'Sections']],
            'footer.col_activities' => ['group' => 'footer', 'value' => ['ru' => 'Активности', 'uz' => 'Faolliklar', 'en' => 'Activities']],
            'footer.col_contacts'   => ['group' => 'footer', 'value' => ['ru' => 'Контакты',   'uz' => 'Aloqa',       'en' => 'Contacts']],

            // === HERO главной — кнопки и статистика ===========================
            'hero.cta_primary'   => ['group' => 'hero', 'value' => ['ru' => 'Стать резидентом',    'uz' => 'Rezident boʻlish',       'en' => 'Become a resident']],
            'hero.cta_secondary' => ['group' => 'hero', 'value' => ['ru' => 'Узнать преимущества', 'uz' => 'Afzalliklarni bilish',   'en' => 'See the benefits']],
            'hero.stat_companies' => ['group' => 'hero', 'value' => ['ru' => 'компаний-резидентов', 'uz' => 'rezident kompaniya',   'en' => 'resident companies']],
            'hero.stat_growth'    => ['group' => 'hero', 'value' => ['ru' => 'средний рост выручки','uz' => 'oʻrtacha daromad oʻsishi','en' => 'average revenue growth']],
            'hero.stat_countries' => ['group' => 'hero', 'value' => ['ru' => 'стран экспорта',       'uz' => 'eksport mamlakati',    'en' => 'export countries']],
            'hero.stat_years'     => ['group' => 'hero', 'value' => ['ru' => 'лет на рынке',         'uz' => 'yil bozorda',           'en' => 'years on market']],

            // === TAGS над H2 на главной ======================================
            'home.tag_benefits'   => ['group' => 'home', 'value' => ['ru' => 'Преимущества резидентства', 'uz' => 'Rezidentlik imtiyozlari', 'en' => 'Residency advantages']],
            'home.tag_problems'   => ['group' => 'home', 'value' => ['ru' => 'Барьеры отрасли',       'uz' => 'Soha toʻsiqlari',        'en' => 'Industry barriers']],
            'home.tag_cases'      => ['group' => 'home', 'value' => ['ru' => 'Индустриальный рост · кейсы', 'uz' => 'Sanoat oʻsishi · keyslar', 'en' => 'Industrial growth · cases']],
            'home.tag_programs'   => ['group' => 'home', 'value' => ['ru' => 'Программы ассоциации', 'uz' => 'Assotsiatsiya dasturlari', 'en' => 'Programs']],
            'home.tag_taxes'      => ['group' => 'home', 'value' => ['ru' => 'Финансовые преференции','uz' => 'Moliyaviy imtiyozlar',   'en' => 'Financial advantages']],
            'home.tag_join_steps' => ['group' => 'home', 'value' => ['ru' => 'Путь резидента',        'uz' => 'Rezident yoʻli',          'en' => 'Resident path']],
            'home.tag_partners'   => ['group' => 'home', 'value' => ['ru' => 'Партнёры',              'uz' => 'Hamkorlar',               'en' => 'Partners']],
            'home.tag_events'     => ['group' => 'home', 'value' => ['ru' => 'Ближайшие мероприятия','uz' => 'Yaqinlashayotgan tadbirlar','en' => 'Upcoming events']],
            'home.tag_news'       => ['group' => 'home', 'value' => ['ru' => 'Новости ассоциации',   'uz' => 'Uyushma yangiliklari',   'en' => 'Association news']],

            // === Hero FALLBACK ================================================
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
            // === Кнопки «Все X» на главной ====================================
            'home.btn_all_partners' => ['group' => 'home', 'value' => ['ru' => 'Смотреть всех партнёров', 'uz' => 'Barcha hamkorlarni koʻrish', 'en' => 'See all partners']],
            'home.btn_all_events'   => ['group' => 'home', 'value' => ['ru' => 'Все мероприятия',          'uz' => 'Barcha tadbirlar',          'en' => 'All events']],
            'home.btn_all_news'     => ['group' => 'home', 'value' => ['ru' => 'Все новости',              'uz' => 'Barcha yangiliklar',        'en' => 'All news']],
            'home.btn_read_more'    => ['group' => 'home', 'value' => ['ru' => 'Читать →',                 'uz' => 'Oʻqish →',                   'en' => 'Read →']],
            'home.btn_register'     => ['group' => 'home', 'value' => ['ru' => 'Зарегистрироваться',      'uz' => 'Roʻyxatdan oʻtish',          'en' => 'Register']],

            // Блок «Объявления SAVDEX» на главной
            'home.h2_listings' => ['group' => 'home', 'value' => [
                'ru' => 'Актуальный спрос с рынка',
                'uz' => 'Bozordan dolzarb talab',
                'en' => 'Live demand from the market',
            ]],
            'home.listings_tag' => ['group' => 'home', 'value' => [
                'ru' => 'Объявления', 'uz' => 'Eʼlonlar', 'en' => 'Listings',
            ]],
            'home.listings_cta' => ['group' => 'home', 'value' => [
                'ru' => 'Все объявления', 'uz' => 'Barcha eʼlonlar', 'en' => 'All listings',
            ]],

            // Страница /listings
            'listings.crumb' => ['group' => 'listings', 'value' => [
                'ru' => 'Объявления', 'uz' => 'Eʼlonlar', 'en' => 'Listings',
            ]],
            'listings.hero_tag' => ['group' => 'listings', 'value' => [
                'ru' => 'Актуальный спрос', 'uz' => 'Dolzarb talab', 'en' => 'Live demand',
            ]],
            'listings.hero_h1' => ['group' => 'listings', 'value' => [
                'ru' => 'Объявления по мебели — SAVDEX',
                'uz' => 'Mebel boʻyicha eʼlonlar — SAVDEX',
                'en' => 'Furniture listings — SAVDEX',
            ]],
            'listings.hero_lead' => ['group' => 'listings', 'value' => [
                'ru' => 'Собрано с B2B-платформы savdex.uz: запросы, предложения и тендеры мебельной категории. Обновляется ежедневно.',
                'uz' => 'B2B platforma savdex.uz dan toʻplandi: mebel toifasining soʻrovlari, takliflari va tenderlari. Har kuni yangilanadi.',
                'en' => 'Aggregated from the B2B platform savdex.uz: furniture-category requests, offers and tenders. Updated daily.',
            ]],
            'listings.filter_all' => ['group' => 'listings', 'value' => ['ru' => 'Все', 'uz' => 'Barchasi', 'en' => 'All']],
            'listings.search_placeholder' => ['group' => 'listings', 'value' => [
                'ru' => 'Поиск по заголовку, городу…', 'uz' => 'Sarlavha, shahar boʻyicha qidirish…', 'en' => 'Search by title or city…',
            ]],
            'listings.empty' => ['group' => 'listings', 'value' => [
                'ru' => 'Пока нет подходящих объявлений', 'uz' => 'Hozircha mos eʼlonlar yoʻq', 'en' => 'No matching listings yet',
            ]],
            'listings.btn_open' => ['group' => 'listings', 'value' => [
                'ru' => 'Открыть на SAVDEX', 'uz' => 'SAVDEX da ochish', 'en' => 'Open on SAVDEX',
            ]],
            'listings.source_label' => ['group' => 'listings', 'value' => [
                'ru' => 'Источник: savdex.uz · обновлено',
                'uz' => 'Manba: savdex.uz · yangilangan',
                'en' => 'Source: savdex.uz · updated',
            ]],

            'news.hero_tag' => ['group' => 'listings', 'value' => ['ru' => 'Новости', 'uz' => 'Yangiliklar', 'en' => 'News']],
            'news.hero_h1'  => ['group' => 'listings', 'value' => [
                'ru' => 'Что происходит в мебельной индустрии Узбекистана',
                'uz' => 'Oʻzbekiston mebel sanoatida nima boʻlmoqda',
                'en' => 'What\'s happening in Uzbekistan\'s furniture industry',
            ]],
            'news.search_placeholder' => ['group' => 'listings', 'value' => [
                'ru' => 'Поиск по новостям…', 'uz' => 'Yangiliklar boʻyicha qidiruv…', 'en' => 'Search news…',
            ]],
            'news.crumb'      => ['group' => 'listings', 'value' => ['ru' => 'Новости', 'uz' => 'Yangiliklar', 'en' => 'News']],
            'news.filter_all' => ['group' => 'listings', 'value' => ['ru' => 'Все', 'uz' => 'Barchasi', 'en' => 'All']],
            'news.empty'      => ['group' => 'listings', 'value' => [
                'ru' => 'По этим фильтрам ничего не найдено',
                'uz' => 'Bu filtrlarga mos hech narsa yoʻq',
                'en' => 'Nothing matches these filters',
            ]],
            'news.reset'      => ['group' => 'listings', 'value' => ['ru' => 'Сбросить фильтры', 'uz' => 'Filtrlarni tozalash', 'en' => 'Reset filters']],
            'news.shown'      => ['group' => 'listings', 'value' => ['ru' => 'Показано', 'uz' => 'Koʻrsatildi', 'en' => 'Showing']],
            'news.of'         => ['group' => 'listings', 'value' => ['ru' => 'из', 'uz' => 'jami', 'en' => 'of']],

            // === /listings — доп. ключи ======================================
            'listings.reset' => ['group' => 'listings', 'value' => ['ru' => 'Сбросить фильтры', 'uz' => 'Filtrlarni tozalash', 'en' => 'Reset filters']],
            'listings.shown' => ['group' => 'listings', 'value' => ['ru' => 'Показано', 'uz' => 'Koʻrsatildi', 'en' => 'Showing']],
            'listings.of'    => ['group' => 'listings', 'value' => ['ru' => 'из', 'uz' => 'jami', 'en' => 'of']],
            'listings.seo_title' => ['group' => 'listings', 'value' => [
                'ru' => 'Актуальные объявления по мебели (SAVDEX) — MEYOS',
                'uz' => 'Mebel sanoati eʼlonlari — MEYOS',
                'en' => 'Furniture industry listings — MEYOS',
            ]],
            'listings.seo_description' => ['group' => 'listings', 'value' => [
                'ru' => 'Запросы, предложения и тендеры мебельной категории с B2B-платформы savdex.uz — обновляется ежедневно.',
                'uz' => 'B2B platforma savdex.uz dan mebel kategoriyasining soʻrov, taklif va tenderlari — har kuni yangilanadi.',
                'en' => 'Furniture category requests, offers and tenders from the B2B platform savdex.uz — refreshed daily.',
            ]],

            // === /legislation — всё, что осталось вшитым в views ==============
            'legislation.tag' => ['group' => 'legislation', 'value' => [
                'ru' => 'Нормативная база', 'uz' => 'Normativ baza', 'en' => 'Regulatory base',
            ]],
            'legislation.h1' => ['group' => 'legislation', 'value' => [
                'ru' => 'Законодательство мебельной индустрии Узбекистана',
                'uz' => 'Mebel sanoati qonunchiligi',
                'en' => 'Furniture industry legislation',
            ]],
            'legislation.lead' => ['group' => 'legislation', 'value' => [
                'ru' => 'Собрание постановлений и законопроектов: ПП-193, ПП-5155, ПП-2973 и другие — текст, PDF, источник.',
                'uz' => 'Postanovleniyalar va qonun loyihalari toʻplami: PQ-193, PQ-5155, PQ-2973 va boshqalar — matn, PDF va manba.',
                'en' => 'Collected decrees and draft bills: PP-193, PP-5155, PP-2973 and more — text, PDF, source.',
            ]],
            'legislation.crumb_home' => ['group' => 'legislation', 'value' => ['ru' => 'Главная', 'uz' => 'Bosh sahifa', 'en' => 'Home']],
            'legislation.crumb_this' => ['group' => 'legislation', 'value' => ['ru' => 'Законодательство', 'uz' => 'Qonunchilik', 'en' => 'Legislation']],
            'legislation.chip_all'   => ['group' => 'legislation', 'value' => ['ru' => 'Все', 'uz' => 'Barchasi', 'en' => 'All']],
            'legislation.search_ph'  => ['group' => 'legislation', 'value' => [
                'ru' => 'Поиск по номеру и тексту…',
                'uz' => 'Raqam va matn boʻyicha qidirish…',
                'en' => 'Search by number or text…',
            ]],
            'legislation.empty' => ['group' => 'legislation', 'value' => [
                'ru' => 'По этим фильтрам ничего не найдено',
                'uz' => 'Bu filtrlarga mos hech narsa yoʻq',
                'en' => 'Nothing matches these filters',
            ]],
            'legislation.open'  => ['group' => 'legislation', 'value' => ['ru' => 'Открыть', 'uz' => 'Ochish', 'en' => 'Open']],
            'legislation.reset' => ['group' => 'legislation', 'value' => ['ru' => 'Сбросить фильтры', 'uz' => 'Filtrlarni tozalash', 'en' => 'Reset filters']],
            'legislation.shown' => ['group' => 'legislation', 'value' => ['ru' => 'Показано', 'uz' => 'Koʻrsatildi', 'en' => 'Showing']],
            'legislation.of'    => ['group' => 'legislation', 'value' => ['ru' => 'из', 'uz' => 'jami', 'en' => 'of']],
            'legislation.seo_title' => ['group' => 'legislation', 'value' => [
                'ru' => 'Законодательство мебельной индустрии Узбекистана — MEYOS',
                'uz' => 'Mebel sanoati qonunchiligi — MEYOS',
                'en' => 'Furniture industry legislation of Uzbekistan — MEYOS',
            ]],
            'legislation.seo_description' => ['group' => 'legislation', 'value' => [
                'ru' => 'Собрание постановлений ПП-193, ПП-5155 и других нормативных актов: текст, PDF и официальный источник.',
                'uz' => 'PQ-193, PQ-5155 va boshqa postanovleniyalar: matn, PDF va rasmiy manba.',
                'en' => 'Collected decrees PP-193, PP-5155 and more — text, PDF and official source.',
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
