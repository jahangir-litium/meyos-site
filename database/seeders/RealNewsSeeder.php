<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

/**
 * Реальные новости об отрасли и MEYOS, собранные из открытых источников:
 * gazeta.uz, spot.uz, lex.uz, meyos.uz.
 *
 * Категории: regulation | export | edujob | residency | programs.
 * Все тексты — на 3 языках (RU/UZ/EN).
 */
class RealNewsSeeder extends Seeder
{
    public function run(): void
    {
        News::query()->forceDelete();

        $items = [
            // === 2026-09-26 — саммит Silk Road ===
            [
                'slug' => 'silk-road-summit-2025',
                'category' => 'programs',
                'published_at' => '2025-09-10',
                'is_published' => true,
                'is_featured' => true,
                'sort' => 10,
                'title' => [
                    'ru' => 'Ташкент готовится принять международный саммит мебельной и строительной индустрии',
                    'uz' => 'Toshkent mebel va qurilish sanoati xalqaro sammitini oʻtkazishga tayyorlanmoqda',
                    'en' => 'Tashkent to host international furniture and construction industry summit',
                ],
                'preview' => [
                    'ru' => 'Рекомендации по ключевым вызовам рынка направят в профильные ведомства при содействии Торгово-промышленной палаты и Ассоциации MEYOS.',
                    'uz' => 'Bozorning asosiy chaqiriqlari boʻyicha tavsiyalar Savdo-sanoat palatasi va MEYOS assotsiatsiyasi yordamida tegishli idoralarga yuboriladi.',
                    'en' => 'Recommendations on key market challenges will be sent to authorities with the support of the Chamber of Commerce and Industry and the MEYOS Association.',
                ],
                'content' => [
                    'ru' => "<p>В сентябре 2025 года в Ташкенте прошёл международный саммит мебельной и строительной индустрии. Мероприятие собрало производителей, дизайнеров, поставщиков материалов и представителей госорганов.</p>"
                        . "<p>По итогам обсуждений будут выработаны рекомендации по ключевым вызовам рынка: локализация сырья, экспортная логистика, стандарты качества и подготовка кадров. Документ направят в профильные ведомства при содействии <strong>Торгово-промышленной палаты РУз и Ассоциации MEYOS</strong>.</p>"
                        . "<p><em>Источник: gazeta.uz</em></p>",
                    'uz' => "<p>2025-yil sentabr oyida Toshkentda mebel va qurilish sanoatining xalqaro sammiti oʻtkazildi. Tadbir ishlab chiqaruvchilar, dizaynerlar, material yetkazib beruvchilar va davlat organlari vakillarini birlashtirdi.</p>"
                        . "<p>Muhokamalar yakuniga koʻra, bozorning asosiy muammolari boʻyicha tavsiyalar ishlab chiqiladi: xomashyoning mahalliylashtirilishi, eksport logistikasi, sifat standartlari va kadrlar tayyorlash. Hujjat <strong>Savdo-sanoat palatasi va MEYOS assotsiatsiyasi yordamida</strong> tegishli idoralarga yuboriladi.</p>",
                    'en' => "<p>In September 2025, Tashkent hosted an international furniture and construction industry summit that brought together manufacturers, designers, material suppliers and government representatives.</p>"
                        . "<p>The outcomes will be shaped into recommendations on key market issues: raw materials localization, export logistics, quality standards and workforce training. The document will be sent to relevant agencies with the support of the <strong>Chamber of Commerce and Industry of Uzbekistan and the MEYOS Association</strong>.</p>",
                ],
                'image_alt' => ['ru' => 'Саммит мебельной индустрии в Ташкенте', 'uz' => 'Toshkentda mebel sanoati sammiti', 'en' => 'Furniture industry summit in Tashkent'],
            ],

            // === 2025-05-26 — снижение пошлин ===
            [
                'slug' => 'furniture-tariffs-cut-2025',
                'category' => 'regulation',
                'published_at' => '2025-05-26',
                'is_published' => true,
                'is_featured' => true,
                'sort' => 20,
                'title' => [
                    'ru' => 'Узбекистан снизил пошлины на 14 видов импортного сырья для мебельной промышленности',
                    'uz' => 'Oʻzbekiston mebel sanoati uchun import xomashyoning 14 turi uchun bojlarni pasaytirdi',
                    'en' => 'Uzbekistan cuts tariffs on 14 imported raw materials for furniture industry',
                ],
                'preview' => [
                    'ru' => 'До 1 января 2029 года применяется ставка 1% при ввозе части сырья, фурнитуры и аксессуаров, используемых в производстве мебели.',
                    'uz' => '2029-yil 1-yanvargacha mebel ishlab chiqarishda ishlatiladigan xomashyo, furnitura va aksessuarlarning bir qismi uchun 1% stavka qoʻllaniladi.',
                    'en' => 'Until January 1, 2029, a 1% rate applies to a range of raw materials, hardware and accessories used in furniture production.',
                ],
                'content' => [
                    'ru' => "<p>Постановление Президента ПП-193 от 27 мая 2025 года ввело льготный таможенный режим для мебельной отрасли. До <strong>1 января 2029 года</strong> ставка таможенной пошлины составит <strong>1%</strong> при импорте 14 видов сырья, фурнитуры и аксессуаров.</p>"
                        . "<p>Дополнительно организован централизованный импорт древесины и деревоматериалов для мебельной отрасли. Индивидуальным предпринимателям разрешено нанимать до 5 работников для производства мебели.</p>"
                        . "<p>Мера направлена на снижение себестоимости и повышение конкурентоспособности узбекских производителей на внутреннем и экспортных рынках.</p>",
                    'uz' => "<p>Prezidentning 2025-yil 27-may kunidagi PQ-193-son qarori mebel sanoati uchun imtiyozli bojxona rejimini joriy qildi. <strong>2029-yil 1-yanvargacha</strong> xomashyo, furnitura va aksessuarlarning 14 turini import qilishda bojxona bojining stavkasi <strong>1%</strong> boʻladi.</p>"
                        . "<p>Bundan tashqari, mebel sanoati uchun yogʻoch va yogʻoch materiallarining markazlashtirilgan importi tashkil etildi. Yakka tartibdagi tadbirkorlarga mebel ishlab chiqarish uchun 5 tagacha ishchi yollashga ruxsat berildi.</p>",
                    'en' => "<p>Presidential Decree PP-193 dated May 27, 2025 introduced a preferential customs regime for the furniture industry. Until <strong>January 1, 2029</strong>, the customs duty rate is <strong>1%</strong> for imports of 14 types of raw materials, hardware and accessories.</p>"
                        . "<p>Additionally, centralized import of wood and wood materials for the furniture industry was arranged. Individual entrepreneurs are now allowed to hire up to 5 workers for furniture production.</p>"
                        . "<p>The measure aims to reduce production costs and strengthen the competitiveness of Uzbek manufacturers in both domestic and export markets.</p>",
                ],
                'image_alt' => ['ru' => 'Мебельная фабрика Узбекистан', 'uz' => 'Oʻzbekiston mebel fabrikasi', 'en' => 'Uzbekistan furniture factory'],
            ],

            // === 2025-10-01 — сертификация для госзаказа ===
            [
                'slug' => 'domestic-cert-goszakaz-2025',
                'category' => 'regulation',
                'published_at' => '2025-10-01',
                'is_published' => true,
                'sort' => 30,
                'title' => [
                    'ru' => 'С 1 октября — только с сертификатом отечественного производства к госзакупкам',
                    'uz' => '1-oktabrdan davlat xaridlariga faqat mahalliy ishlab chiqarish sertifikati bilan',
                    'en' => 'From October 1 — only with domestic production certificate to government tenders',
                ],
                'preview' => [
                    'ru' => 'Мебельные компании без сертификата отечественного производства теряют доступ к электронному кооперационному порталу и «Национальному магазину».',
                    'uz' => 'Mahalliy ishlab chiqarish sertifikatiga ega boʻlmagan mebel kompaniyalari elektron kooperatsiya portali va “Milliy doʻkon”ga kirishni yoʻqotadi.',
                    'en' => 'Furniture companies without domestic production certificate lose access to the electronic cooperation portal and «National Store».',
                ],
                'content' => [
                    'ru' => "<p>С <strong>1 октября 2025 года</strong> размещение мебельной продукции на электронном кооперационном портале и страницах «Национального магазина» операторов госзакупок доступно только компаниям с <strong>сертификатом отечественного производства</strong>.</p>"
                        . "<p>Только такие субъекты бизнеса могут участвовать в тендерах. MEYOS помогает членам ассоциации с оформлением сертификата и подготовкой документации.</p>",
                    'uz' => "<p>2025-yil <strong>1-oktabrdan</strong> davlat xaridlari operatorlarining elektron kooperatsiya portali va “Milliy doʻkon” sahifalarida mebel mahsulotlarini joylashtirish faqat <strong>mahalliy ishlab chiqarish sertifikati</strong> boʻlgan kompaniyalar uchun mavjud.</p>"
                        . "<p>Faqat shunday subʼektlar tenderlarda ishtirok etishi mumkin. MEYOS aʼzolarga sertifikatni rasmiylashtirish va hujjatlarni tayyorlashda yordam beradi.</p>",
                    'en' => "<p>From <strong>October 1, 2025</strong>, placing furniture products on the electronic cooperation portal and «National Store» pages of government procurement operators is only available to companies with a <strong>domestic production certificate</strong>.</p>"
                        . "<p>Only such businesses may participate in tenders. MEYOS assists members with certificate registration and documentation.</p>",
                ],
                'image_alt' => ['ru' => 'Сертификат отечественного производства', 'uz' => 'Mahalliy ishlab chiqarish sertifikati', 'en' => 'Domestic production certificate'],
            ],

            // === 2026-04-28 — WoodTech & MebelExpo ===
            [
                'slug' => 'woodtech-mebelexpo-2026',
                'category' => 'programs',
                'published_at' => '2026-04-28',
                'is_published' => true,
                'is_featured' => true,
                'sort' => 40,
                'title' => [
                    'ru' => 'WoodTech & MebelExpo Uzbekistan 2026: усиление позиций Центральной Азии',
                    'uz' => 'WoodTech & MebelExpo Uzbekistan 2026: Markaziy Osiyoning mavqeyini mustahkamlash',
                    'en' => 'WoodTech & MebelExpo Uzbekistan 2026 strengthens Central Asia\'s position',
                ],
                'preview' => [
                    'ru' => '22-я выставка WoodTech & MebelExpo с 28 по 30 апреля 2026 года — ведущая международная площадка мебельной и деревообрабатывающей отраслей региона.',
                    'uz' => '22-koʻrgazma WoodTech & MebelExpo 2026-yil 28–30-aprelida — mintaqadagi mebel va yogʻochsozlik tarmoqlarining yetakchi xalqaro maydoni.',
                    'en' => 'The 22nd WoodTech & MebelExpo, April 28-30, 2026 — leading international platform for the region\'s furniture and woodworking industries.',
                ],
                'content' => [
                    'ru' => "<p>22-я выставка <strong>WoodTech & MebelExpo Uzbekistan</strong> прошла 28–30 апреля 2026 года в Ташкенте. Мероприятие подтвердило статус ведущей международной площадки мебельного и деревообрабатывающего сектора Центральной Азии.</p>"
                        . "<p>Отрасль уверенно движется к технологической модернизации и углублению локализации, отвечая на растущий спрос рынка на качество, материалы и дизайн.</p>"
                        . "<p>Резиденты MEYOS участвовали в выставке под единым брендом «Made in Uzbekistan».</p>",
                    'uz' => "<p>22-koʻrgazma <strong>WoodTech & MebelExpo Uzbekistan</strong> 2026-yil 28–30-aprelida Toshkentda oʻtkazildi. Tadbir Markaziy Osiyoning mebel va yogʻochsozlik sektorining yetakchi xalqaro maydoni maqomini tasdiqladi.</p>"
                        . "<p>Tarmoq texnologik modernizatsiya va lokalizatsiyani chuqurlashtirish sari ishonchli qadam tashlamoqda.</p>"
                        . "<p>MEYOS rezidentlari koʻrgazmada “Made in Uzbekistan” yagona brendi ostida ishtirok etdi.</p>",
                    'en' => "<p>The 22nd <strong>WoodTech & MebelExpo Uzbekistan</strong> took place on April 28-30, 2026 in Tashkent. The event confirmed its status as Central Asia's leading international platform for furniture and woodworking.</p>"
                        . "<p>The industry is confidently moving toward technological modernization and deeper localization, responding to growing market demand for quality, materials and design.</p>"
                        . "<p>MEYOS residents participated under the unified «Made in Uzbekistan» brand.</p>",
                ],
                'image_alt' => ['ru' => 'WoodTech MebelExpo Uzbekistan 2026', 'uz' => 'WoodTech MebelExpo Uzbekistan 2026', 'en' => 'WoodTech MebelExpo Uzbekistan 2026'],
            ],

            // === 2026-04-02 — Mebel & Décor ===
            [
                'slug' => 'mebel-decor-2026',
                'category' => 'programs',
                'published_at' => '2026-04-02',
                'is_published' => true,
                'sort' => 50,
                'title' => [
                    'ru' => 'Mebel & Décor 2026: 4-я международная выставка мебели и интерьера',
                    'uz' => 'Mebel & Décor 2026: mebel va interyer boʻyicha 4-xalqaro koʻrgazma',
                    'en' => 'Mebel & Décor 2026: 4th international furniture and interior exhibition',
                ],
                'preview' => [
                    'ru' => 'С 2 по 4 апреля 2026 года в Ташкенте прошла четвёртая международная выставка Mebel & Décor — одно из ключевых событий отрасли региона.',
                    'uz' => '2026-yil 2–4-aprelida Toshkentda 4-xalqaro Mebel & Décor koʻrgazmasi oʻtkazildi — mintaqadagi tarmoq uchun asosiy tadbirlardan biri.',
                    'en' => 'On April 2-4, 2026 Tashkent hosted the 4th international Mebel & Décor exhibition — a key event for the regional furniture industry.',
                ],
                'content' => [
                    'ru' => "<p>Международная выставка <strong>Mebel & Décor 2026</strong> прошла в Ташкенте со 2 по 4 апреля. Мероприятие стало одним из ключевых событий мебельной и интерьерной индустрии региона.</p>"
                        . "<p>Резиденты MEYOS представили новые коллекции и договорились о поставках в Казахстан, Россию и страны Персидского залива.</p>",
                    'uz' => "<p>Xalqaro <strong>Mebel & Décor 2026</strong> koʻrgazmasi Toshkentda 2–4-aprelida boʻlib oʻtdi. Tadbir mintaqadagi mebel va interyer sanoatining asosiy voqealaridan biriga aylandi.</p>"
                        . "<p>MEYOS rezidentlari yangi kolleksiyalarni taqdim etdi va Qozogʻiston, Rossiya va Fors koʻrfazi mamlakatlariga yetkazib berish boʻyicha kelishuvlarga erishdi.</p>",
                    'en' => "<p>The international <strong>Mebel & Décor 2026</strong> exhibition took place in Tashkent on April 2-4. It became one of the key events for the region's furniture and interior industry.</p>"
                        . "<p>MEYOS residents presented new collections and secured supply agreements with buyers in Kazakhstan, Russia and the Persian Gulf.</p>",
                ],
                'image_alt' => ['ru' => 'Mebel & Décor 2026', 'uz' => 'Mebel & Décor 2026', 'en' => 'Mebel & Décor 2026'],
            ],

            // === 2026-08-26 — Central Asia Furniture Exhibition ===
            [
                'slug' => 'central-asia-furniture-2026',
                'category' => 'programs',
                'published_at' => '2026-08-26',
                'is_published' => true,
                'sort' => 60,
                'title' => [
                    'ru' => 'Central Asia International Furniture Exhibition — 26–28 августа 2026, Ташкент',
                    'uz' => 'Markaziy Osiyo xalqaro mebel koʻrgazmasi — 2026-yil 26–28-avgust, Toshkent',
                    'en' => 'Central Asia International Furniture Exhibition — August 26-28, 2026, Tashkent',
                ],
                'preview' => [
                    'ru' => 'Международная выставка мебели пройдёт с 26 по 28 августа 2026 года в Ташкентском национальном выставочном центре.',
                    'uz' => 'Xalqaro mebel koʻrgazmasi 2026-yil 26–28-avgustda Toshkent milliy koʻrgazma markazida oʻtadi.',
                    'en' => 'The international furniture exhibition will be held August 26-28, 2026 at the Tashkent National Exhibition Center.',
                ],
                'content' => [
                    'ru' => "<p>С 26 по 28 августа 2026 года в Ташкентском национальном выставочном центре пройдёт <strong>Central Asia International Furniture Exhibition</strong>. Ожидается участие производителей и байеров из Казахстана, Кыргызстана, Таджикистана, Туркменистана, России, Турции, ОАЭ и Китая.</p>"
                        . "<p>MEYOS формирует общий стенд «Made in Uzbekistan», в рамках которого резиденты ассоциации представляют своё производство и заключают экспортные контракты.</p>",
                    'uz' => "<p>2026-yil 26–28-avgustda Toshkent milliy koʻrgazma markazida <strong>Central Asia International Furniture Exhibition</strong> oʻtkaziladi. Qozogʻiston, Qirgʻiziston, Tojikiston, Turkmaniston, Rossiya, Turkiya, BAA va Xitoydan ishlab chiqaruvchilar va xaridorlar ishtiroki kutilmoqda.</p>"
                        . "<p>MEYOS “Made in Uzbekistan” umumiy stendini shakllantiradi, unda assotsiatsiya rezidentlari oʻz ishlab chiqarishini taqdim etib, eksport shartnomalari tuzadi.</p>",
                    'en' => "<p>From August 26-28, 2026, the Tashkent National Exhibition Center will host the <strong>Central Asia International Furniture Exhibition</strong>. Manufacturers and buyers from Kazakhstan, Kyrgyzstan, Tajikistan, Turkmenistan, Russia, Turkey, UAE and China are expected to attend.</p>"
                        . "<p>MEYOS is organizing a common «Made in Uzbekistan» stand where association residents showcase their production and sign export contracts.</p>",
                ],
                'image_alt' => ['ru' => 'Central Asia Furniture Exhibition Tashkent', 'uz' => 'Markaziy Osiyo mebel koʻrgazmasi', 'en' => 'Central Asia Furniture Exhibition'],
            ],

            // === 2026-07-28 — Business mission Tajikistan ===
            [
                'slug' => 'business-mission-tajikistan-2026',
                'category' => 'export',
                'published_at' => '2026-07-28',
                'is_published' => true,
                'sort' => 5,
                'title' => [
                    'ru' => 'MEYOS проводит бизнес-миссию в Таджикистан: Худжанд и Душанбе',
                    'uz' => 'MEYOS Tojikistonga biznes-missiyani oʻtkazadi: Xoʻjand va Dushanbe',
                    'en' => 'MEYOS runs business mission to Tajikistan: Khujand and Dushanbe',
                ],
                'preview' => [
                    'ru' => 'С 28 по 31 июля 2026 года участники бизнес-миссии MEYOS проведут B2B-встречи с крупными закупщиками мебели в Худжанде и Душанбе.',
                    'uz' => '2026-yil 28–31-iyulda MEYOS biznes-missiya ishtirokchilari Xoʻjand va Dushanbeda yirik mebel xaridorlari bilan B2B uchrashuvlar oʻtkazadi.',
                    'en' => 'From July 28-31, 2026, MEYOS business mission participants will hold B2B meetings with major furniture buyers in Khujand and Dushanbe.',
                ],
                'content' => [
                    'ru' => "<p>MEYOS организует бизнес-миссию узбекских мебельных производителей в Таджикистан. С 28 по 31 июля 2026 года участники проведут B2B-встречи с ключевыми закупщиками в Худжанде и Душанбе.</p>"
                        . "<p>Программа поддерживается Торгово-промышленной палатой РУз и Ассоциацией малого бизнеса. Резиденты MEYOS получают приоритетное включение и подготовку каталогов на английском/русском.</p>",
                    'uz' => "<p>MEYOS Oʻzbekiston mebel ishlab chiqaruvchilarining Tojikistonga biznes-missiyasini tashkil qiladi. 2026-yil 28–31-iyulda ishtirokchilar Xoʻjand va Dushanbeda asosiy xaridorlar bilan B2B uchrashuvlar oʻtkazadi.</p>"
                        . "<p>Dastur Oʻzbekiston SSP va Kichik biznes uyushmasi tomonidan qoʻllab-quvvatlanadi.</p>",
                    'en' => "<p>MEYOS is running a business mission for Uzbek furniture manufacturers to Tajikistan. From July 28-31, 2026 participants will hold B2B meetings with key buyers in Khujand and Dushanbe.</p>"
                        . "<p>The program is supported by the Chamber of Commerce and Industry of Uzbekistan and the Association of Small Business.</p>",
                ],
                'image_alt' => ['ru' => 'Бизнес-миссия в Таджикистан', 'uz' => 'Tojikistonga biznes-missiya', 'en' => 'Business mission to Tajikistan'],
            ],

            // === 2026-08-02 — Russia business delegation ===
            [
                'slug' => 'business-delegation-russia-2026',
                'category' => 'export',
                'published_at' => '2026-08-02',
                'is_published' => true,
                'is_featured' => true,
                'sort' => 6,
                'title' => [
                    'ru' => 'Делегация MEYOS в России: Казань, Уфа, Самара — с 2 по 7 августа',
                    'uz' => 'MEYOS delegatsiyasi Rossiyada: Qozon, Ufa, Samara — 2–7 avgust',
                    'en' => 'MEYOS delegation in Russia: Kazan, Ufa, Samara — August 2-7',
                ],
                'preview' => [
                    'ru' => '6-дневная поездка в три российских города: B2B-встречи, посещения фабрик, знакомство с сетью дистрибьюторов. Вылет 6:00 через Centrum Air.',
                    'uz' => 'Rossiyaning uch shahriga 6 kunlik safar: B2B uchrashuvlar, fabrikalarga tashrif, distribyutorlar bilan tanishuv. 6:00 da Centrum Air orqali.',
                    'en' => '6-day trip to three Russian cities: B2B meetings, factory tours and distributor networking. 6:00 AM departure via Centrum Air.',
                ],
                'content' => [
                    'ru' => "<p>MEYOS формирует деловую делегацию в Россию с 2 по 7 августа 2026 года. Программа охватывает Казань, Уфу и Самару — три ключевых промышленных центра Приволжского федерального округа.</p>"
                        . "<p><strong>В программе:</strong> B2B-встречи с закупщиками, посещение мебельных фабрик, знакомство с сетью дистрибьюторов, участие в отраслевых круглых столах.</p>"
                        . "<p><strong>Стоимость участия:</strong> $344 без багажа или $364 с багажом (Centrum Air, вылет 6:00). Регистрация через ассоциацию.</p>",
                    'uz' => "<p>MEYOS 2026-yil 2–7-avgustda Rossiyaga biznes delegatsiyasini shakllantirmoqda. Dastur Qozon, Ufa va Samarani — Volga bo'yi federal okrugining uchta asosiy sanoat markazini qamrab oladi.</p>"
                        . "<p><strong>Dasturda:</strong> xaridorlar bilan B2B uchrashuvlar, mebel fabrikalariga tashrif, distribyutorlar bilan tanishuv, tarmoq davra suhbatlari.</p>"
                        . "<p><strong>Ishtirok narxi:</strong> yuk bagajsiz $344 yoki bagaj bilan $364 (Centrum Air, uchish 6:00). Uyushma orqali roʻyxatga olish.</p>",
                    'en' => "<p>MEYOS is organizing a business delegation to Russia from August 2-7, 2026. The program covers Kazan, Ufa and Samara — three key industrial centers of the Volga Federal District.</p>"
                        . "<p><strong>On the agenda:</strong> B2B meetings with buyers, factory tours, distributor networking, industry round tables.</p>"
                        . "<p><strong>Participation cost:</strong> $344 without checked baggage or $364 with baggage (Centrum Air, 6:00 AM departure). Registration via the association.</p>",
                ],
                'image_alt' => ['ru' => 'Бизнес-делегация в Россию', 'uz' => 'Rossiyaga biznes delegatsiyasi', 'en' => 'Business delegation to Russia'],
            ],

            // === 2026-08-15 — 9 пунктов в КабМин ===
            [
                'slug' => 'meyos-9-points-cabmin-2026',
                'category' => 'regulation',
                'published_at' => '2026-08-15',
                'is_published' => true,
                'sort' => 7,
                'title' => [
                    'ru' => 'MEYOS направила в Кабмин 9 предложений по поддержке отрасли',
                    'uz' => 'MEYOS Vazirlar Mahkamasiga tarmoqni qoʻllab-quvvatlash boʻyicha 9 taklif yubordi',
                    'en' => 'MEYOS submits 9 industry support proposals to the Cabinet of Ministers',
                ],
                'preview' => [
                    'ru' => 'Освобождение экспортёров от налога на прибыль, увеличение срока дебиторки со 180 до 360 дней, субсидии на транспорт, пошлины на китайскую мебель.',
                    'uz' => 'Eksportchilarni foyda soligʻidan ozod qilish, debitorlik muddatini 180 dan 360 kunga oshirish, transport subsidiyalari, xitoy mebeliga bojlar.',
                    'en' => 'Profit-tax exemption for exporters, extending receivables from 180 to 360 days, transport subsidies, tariffs on Chinese furniture.',
                ],
                'content' => [
                    'ru' => "<p>Ассоциация MEYOS направила в Кабинет Министров РУз документ из 9 пунктов с предложениями по стимулированию мебельной отрасли:</p>"
                        . "<ul><li>Освобождение экспортёров мебели от налога на прибыль</li>"
                        . "<li>Продление срока валютного контроля по дебиторской задолженности со 180 до 360 дней</li>"
                        . "<li>Субсидии на транспортные расходы при экспорте</li>"
                        . "<li>Повышение таможенных пошлин на импортную китайскую мебель</li>"
                        . "<li>Расширение перечня льготируемого сырья</li>"
                        . "<li>Оплата зарплат приглашённых экспертов</li>"
                        . "<li>Поддержка рекламы узбекских брендов за рубежом</li>"
                        . "<li>Финансирование систематизации производства</li>"
                        . "<li>Ускорение процедур сертификации</li></ul>"
                        . "<p>Ответ ведомств ожидается до конца сентября 2026 года.</p>",
                    'uz' => "<p>MEYOS uyushmasi Oʻzbekiston Vazirlar Mahkamasiga mebel tarmog'ini rag'batlantirish bo'yicha 9 banddan iborat hujjat yubordi:</p>"
                        . "<ul><li>Mebel eksportchilarini foyda soligʻidan ozod qilish</li>"
                        . "<li>Debitorlik boʻyicha valyuta nazorati muddatini 180 dan 360 kunga uzaytirish</li>"
                        . "<li>Eksportda transport xarajatlariga subsidiya</li>"
                        . "<li>Xitoy mebeliga bojxona tariflarini oshirish</li>"
                        . "<li>Imtiyozli xomashyo roʻyxatini kengaytirish</li>"
                        . "<li>Taklif etilgan mutaxassislar ish haqini toʻlash</li>"
                        . "<li>Chet elda oʻzbek brendlari reklamasini qoʻllab-quvvatlash</li>"
                        . "<li>Ishlab chiqarish tizimlashtiruvchi moliyalash</li>"
                        . "<li>Sertifikatsiya jarayonlarini tezlashtirish</li></ul>",
                    'en' => "<p>The MEYOS Association has submitted a 9-point document to the Cabinet of Ministers of Uzbekistan with proposals to boost the furniture industry:</p>"
                        . "<ul><li>Profit tax exemption for furniture exporters</li>"
                        . "<li>Extending currency control period on receivables from 180 to 360 days</li>"
                        . "<li>Subsidies on transport costs for exports</li>"
                        . "<li>Higher customs duties on Chinese imported furniture</li>"
                        . "<li>Expanded list of preferential raw materials</li>"
                        . "<li>Salary coverage for invited experts</li>"
                        . "<li>Support for advertising Uzbek brands abroad</li>"
                        . "<li>Financing for production systematization</li>"
                        . "<li>Faster certification procedures</li></ul>",
                ],
                'image_alt' => ['ru' => 'Предложения MEYOS в Кабмин', 'uz' => 'MEYOSning Vazirlar Mahkamasidagi takliflari', 'en' => 'MEYOS proposals to Cabinet of Ministers'],
            ],

            // === 2026-07-15 — IHLOS + Русский Лес → Yashnobod Technopark ===
            [
                'slug' => 'ihlos-technopark-yashnobod',
                'category' => 'residency',
                'published_at' => '2026-07-15',
                'is_published' => true,
                'sort' => 8,
                'title' => [
                    'ru' => 'IHLOS Furniture и «Русский Лес» стали резидентами Yashnobod Innovation Technopark',
                    'uz' => 'IHLOS Furniture va “Rus O‘rmoni” Yashnobod Innovatsiya Texnoparkining rezidentiga aylandi',
                    'en' => 'IHLOS Furniture and «Russian Forest» became residents of Yashnobod Innovation Technopark',
                ],
                'preview' => [
                    'ru' => 'Резиденты MEYOS вошли в Yashnobod Innovation Technopark: налоговые льготы, современная инфраструктура и поддержка экспорта.',
                    'uz' => 'MEYOS rezidentlari Yashnobod Innovatsiya Texnoparkiga kirdi: soliq imtiyozlari, zamonaviy infratuzilma va eksportni qoʻllab-quvvatlash.',
                    'en' => 'MEYOS residents joined Yashnobod Innovation Technopark: tax benefits, modern infrastructure and export support.',
                ],
                'content' => [
                    'ru' => "<p>Резиденты MEYOS — IHLOS Furniture и «Русский Лес» — получили статус резидентов Yashnobod Innovation Technopark. Партнёрство даёт компаниям доступ к налоговым льготам МПЗ, современной инфраструктуре и поддержке экспортного развития.</p>"
                        . "<p>Соглашение выработано при участии MEYOS в рамках инициативы по поддержке производителей мебели, работающих на экспортный рынок.</p>",
                    'uz' => "<p>MEYOS rezidentlari — IHLOS Furniture va “Rus Oʻrmoni” — Yashnobod Innovatsiya Texnoparkining rezidenti maqomini oldi. Hamkorlik kompaniyalarga KSZ soliq imtiyozlariga, zamonaviy infratuzilmaga va eksport rivojlanishini qoʻllab-quvvatlashga kirish beradi.</p>",
                    'en' => "<p>MEYOS residents IHLOS Furniture and «Russian Forest» have obtained resident status at Yashnobod Innovation Technopark. The partnership grants access to SIZ tax benefits, modern infrastructure and export development support.</p>",
                ],
                'image_alt' => ['ru' => 'Yashnobod Innovation Technopark', 'uz' => 'Yashnobod Innovatsiya Texnoparki', 'en' => 'Yashnobod Innovation Technopark'],
            ],

            // === 2026-08-10 — вебинар по поддержке экспорта ===
            [
                'slug' => 'export-support-webinar-2026',
                'category' => 'export',
                'published_at' => '2026-08-10',
                'is_published' => true,
                'sort' => 9,
                'title' => [
                    'ru' => 'Открытая дискуссия по поддержке экспорта: вебинар 10 августа',
                    'uz' => 'Eksportni qoʻllab-quvvatlash boʻyicha ochiq muhokama: 10-avgust vebinari',
                    'en' => 'Open discussion on export support: webinar on August 10',
                ],
                'preview' => [
                    'ru' => '17:00 Zoom-вебинар о новых возможностях: 50% компенсация за системy производства, зарплаты приглашённых экспертов, рекламная поддержка.',
                    'uz' => '17:00 Zoom-vebinar yangi imkoniyatlar haqida: ishlab chiqarish tizimini 50% kompensatsiya, taklif etilgan mutaxassislarning ish haqi, reklama qoʻllovi.',
                    'en' => '17:00 Zoom webinar on new opportunities: 50% cost coverage for production systematization, expert salary coverage, advertising support.',
                ],
                'content' => [
                    'ru' => "<p>10 августа в 17:00 MEYOS проводит открытый Zoom-вебинар для мебельных производителей и поставщиков. В программе — новые меры поддержки экспорта:</p>"
                        . "<ul><li>50% компенсация расходов на систематизацию производства от Trade Development Company</li>"
                        . "<li>Оплата зарплаты приглашённых иностранных инженеров-технологов и дизайнеров</li>"
                        . "<li>Реклама узбекских брендов за рубежом</li>"
                        . "<li>Онлайн-портал для упрощения операций</li></ul>"
                        . "<p>Участие бесплатное, необходима регистрация через сайт или Telegram-канал MEYOS.</p>",
                    'uz' => "<p>10-avgust soat 17:00 da MEYOS mebel ishlab chiqaruvchilari va yetkazib beruvchilari uchun ochiq Zoom vebinar oʻtkazadi. Dasturda — eksportni qoʻllab-quvvatlashning yangi choralar:</p>"
                        . "<ul><li>Trade Development Company tomonidan ishlab chiqarishni tizimlashtirish xarajatlarining 50% kompensatsiyasi</li>"
                        . "<li>Taklif etilgan xorijiy muhandis-texnologlar va dizaynerlarning ish haqini toʻlash</li>"
                        . "<li>Chet elda oʻzbek brendlarini reklama qilish</li>"
                        . "<li>Operatsiyalarni soddalashtirish uchun onlayn-portal</li></ul>",
                    'en' => "<p>On August 10 at 17:00, MEYOS holds an open Zoom webinar for furniture manufacturers and suppliers. On the agenda — new export support measures:</p>"
                        . "<ul><li>50% cost coverage for production systematization by Trade Development Company</li>"
                        . "<li>Salary coverage for invited foreign technologist engineers and designers</li>"
                        . "<li>Advertising support for Uzbek brands abroad</li>"
                        . "<li>Online portal for streamlined operations</li></ul>",
                ],
                'image_alt' => ['ru' => 'Вебинар поддержки экспорта', 'uz' => 'Eksport qoʻllovi vebinari', 'en' => 'Export support webinar'],
            ],

            // === 2020-12-17 — конференция MEYOS в Hyatt Regency ===
            [
                'slug' => 'meyos-conference-2020-hyatt',
                'category' => 'residency',
                'published_at' => '2020-12-17',
                'is_published' => true,
                'sort' => 70,
                'title' => [
                    'ru' => 'Итоги года: конференция MEYOS в Hyatt Regency Tashkent',
                    'uz' => 'Yil yakuni: Hyatt Regency Tashkentda MEYOS konferensiyasi',
                    'en' => 'Year in review: MEYOS conference at Hyatt Regency Tashkent',
                ],
                'preview' => [
                    'ru' => '17 декабря 2020 года в отеле Hyatt Regency Tashkent прошла ежегодная конференция Ассоциации MEYOS с представителями крупнейших мебельных компаний всех регионов Узбекистана.',
                    'uz' => '2020-yil 17-dekabrda Hyatt Regency Tashkent mehmonxonasida Oʻzbekiston barcha viloyatlaridagi eng yirik mebel kompaniyalari vakillari bilan MEYOS yillik konferensiyasi boʻlib oʻtdi.',
                    'en' => 'On December 17, 2020, Hyatt Regency Tashkent hosted the annual MEYOS Association conference with representatives of major furniture companies from all regions of Uzbekistan.',
                ],
                'content' => [
                    'ru' => "<p>Ежегодная конференция ассоциации MEYOS собрала руководителей крупнейших мебельных и деревообрабатывающих компаний со всех регионов Узбекистана. Участники подвели итоги года и обсудили планы развития отрасли.</p>"
                        . "<p>Ассоциация основана весной 2018 года и объединяет производителей и поставщиков мебельной отрасли. MEYOS представляет их интересы перед государственными институтами и организует участие в международных выставках под единым брендом «Made in Uzbekistan».</p>"
                        . "<p><em>Источник: spot.uz</em></p>",
                    'uz' => "<p>MEYOS assotsiatsiyasining yillik konferensiyasi Oʻzbekistonning barcha viloyatlaridan eng yirik mebel va yogʻochsozlik kompaniyalari rahbarlarini yigʻdi. Ishtirokchilar yil yakunlarini sarhisob qildilar va tarmoqni rivojlantirish rejalarini muhokama qildilar.</p>"
                        . "<p>Assotsiatsiya 2018-yil bahorda tashkil etilgan va mebel tarmogʻining ishlab chiqaruvchilari va yetkazib beruvchilarini birlashtiradi. MEYOS ularning manfaatlarini davlat institutlari oldida ifodalaydi va “Made in Uzbekistan” yagona brendi ostida xalqaro koʻrgazmalarga ishtirokni tashkil qiladi.</p>",
                    'en' => "<p>MEYOS Association's annual conference brought together leaders of the largest furniture and woodworking companies from all regions of Uzbekistan. Participants reviewed the year and discussed industry development plans.</p>"
                        . "<p>Founded in spring 2018, the association unites furniture manufacturers and suppliers. MEYOS represents their interests before state institutions and organizes participation in international exhibitions under the unified «Made in Uzbekistan» brand.</p>",
                ],
                'image_alt' => ['ru' => 'MEYOS конференция Hyatt Regency Tashkent', 'uz' => 'MEYOS Hyatt Regency konferensiyasi', 'en' => 'MEYOS conference at Hyatt Regency Tashkent'],
            ],
        ];

        foreach ($items as $data) {
            News::create($data);
        }
    }
}
