<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

/**
 * Реальные партнёры MEYOS на основе анкет из
 * E:/Проекты/Coming soon/Клиенты/МИЕЗ/парнеры/
 *
 * Логотипы AIKO/DAKOTA/IHLOS/WODEX уже в storage/app/public/partners/.
 * Остальным логотипы придут отдельно от клиента — поле logo_image=null,
 * страница нарисует инициал.
 */
class RealPartnersSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Копируем логотипы из репозитория в storage/app/public/partners.
        //    Пропускаем те, что уже лежат — идемпотентно.
        $srcDir = database_path('seeders/data/partners');
        $dstDir = storage_path('app/public/partners');
        if (is_dir($srcDir)) {
            if (!is_dir($dstDir)) {
                mkdir($dstDir, 0755, true);
            }
            foreach (glob($srcDir . '/*.{png,jpg,jpeg,webp,svg}', GLOB_BRACE) as $src) {
                $dst = $dstDir . '/' . basename($src);
                if (!file_exists($dst) || filesize($dst) !== filesize($src)) {
                    copy($src, $dst);
                }
            }
        }

        // 2) Удаляем стоковых партнёров (форсированно, чтобы освободить slugs)
        Partner::query()->forceDelete();

        $partners = [
            // 1. AIKO — большая анкета, логотип есть
            [
                'slug'          => 'aiko',
                'category'      => 'production',
                'region'        => 'tashkent_city',
                'is_published'  => true,
                'show_on_home'  => true,
                'sort'          => 10,
                'founded_year'  => 2008,
                'logo_text'     => 'AIKO',
                'logo_image'    => 'partners/aiko.png',
                'name' => [
                    'ru' => 'AIKO RATTAN',
                    'uz' => 'AIKO RATTAN',
                    'en' => 'AIKO RATTAN',
                ],
                'description' => [
                    'ru' => 'С 2008 года — узбекский производитель современной мебели для дома, офиса, HoReCa и открытых пространств. 250+ сотрудников, экспорт в 11 стран.',
                    'uz' => '2008-yildan beri uy, ofis, HoReCa va ochiq maydonlar uchun zamonaviy mebel ishlab chiqaruvchi Oʻzbekiston korxonasi. 250+ xodim, 11 davlatga eksport.',
                    'en' => 'Since 2008 — an Uzbek manufacturer of modern furniture for home, office, HoReCa and outdoor spaces. 250+ employees, exports to 11 countries.',
                ],
                'about' => [
                    'ru' => "<p><strong>AIKO RATTAN LLC</strong> — производитель современной мебели с 2008 года. Собственная база, 250+ сотрудников, производство 3757 м² + склад 3837 м² + шоурум 140 м².</p>"
                        . "<p><strong>Продукция:</strong> мебель из искусственного ротанга; садовая, балконная и террасная; для HoReCa; столы, стулья, кресла, диваны; шкафы, комоды, стеллажи, офисная и спальная; металлическая и деревянная; освещение и предметы интерьера.</p>"
                        . "<p><strong>Материалы:</strong> российская сталь, турецкая порошковая краска Micropul, сибирский тополь, итальянские лаки Sirca, австрийские плиты Kronospan и Egger, стекло 6 мм, велюр, полиэстер, водоотталкивающие ткани, поролон.</p>"
                        . "<p><strong>Мощности:</strong> 7 900 изделий/месяц (94 990 в год).</p>"
                        . "<p><strong>Экспорт:</strong> Россия, Беларусь, Азербайджан, Армения, Казахстан, Киргизия, Таджикистан, Туркменистан, ОАЭ, Саудовская Аравия, США.</p>"
                        . "<p><strong>Клиенты:</strong> AMIRSOY, Yandex Uzbekistan, Myata Lounge, BOLO HOUZ, ATLANTIS Aqua Park, Hammersmith, TOKU, GAO GAO Rooftop, Yakamoz и др.</p>"
                        . "<p><strong>Награды:</strong> «Бренд года 2021» и «2022», Brand Awards International 2024.</p>"
                        . "<p><strong>Гарантия:</strong> помещения — 12 месяцев, наружная мебель из ротанга — 24 месяца.</p>",
                    'uz' => "<p><strong>“AIKO RATTAN” MChJ</strong> — 2008-yildan beri zamonaviy mebel ishlab chiqaruvchi. Oʻz bazasi, 250+ xodim, ishlab chiqarish 3757 m² + ombor 3837 m² + shou-rum 140 m².</p>"
                        . "<p><strong>Mahsulotlar:</strong> suniy rotangdan mebel; bogʻ, balkon va terassa; HoReCa; stol, stul, kreslo, divan; shkaf, komod, stellaj, ofis va yotoqxona; metall va yogʻoch; yoritish va interyer buyumlari.</p>"
                        . "<p><strong>Materiallar:</strong> Rossiya poʻlati, Turkiya Micropul kukunli boʻyoqlar, Sibir tilogʻochi, Italiya Sirca laklari, Avstriya Kronospan va Egger plitalari, 6 mm shisha, velur, poliester, suv qaytaruvchi matolar.</p>"
                        . "<p><strong>Quvvat:</strong> oyiga 7 900 dona (yiliga 94 990).</p>"
                        . "<p><strong>Eksport:</strong> Rossiya, Belarus, Ozarbayjon, Armaniston, Qozogʻiston, Qirgʻiziston, Tojikiston, Turkmaniston, BAA, Saudiya Arabistoni, AQSh.</p>"
                        . "<p><strong>Yirik mijozlar:</strong> AMIRSOY, Yandex Uzbekistan, Myata Lounge, BOLO HOUZ, ATLANTIS Aqua Park, Hammersmith, TOKU, GAO GAO Rooftop, Yakamoz.</p>"
                        . "<p><strong>Mukofotlar:</strong> “Brend yili 2021” va “2022”, Brand Awards International 2024.</p>"
                        . "<p><strong>Kafolat:</strong> ichki mebel — 12 oy, tashqi rotang — 24 oy.</p>",
                    'en' => "<p><strong>AIKO RATTAN LLC</strong> — a modern furniture manufacturer since 2008. Own base, 250+ employees, 3,757 m² production + 3,837 m² warehouse + 140 m² showroom.</p>"
                        . "<p><strong>Products:</strong> synthetic rattan furniture; garden, balcony, terrace; HoReCa; tables, chairs, armchairs, sofas; cabinets, chests, shelving, office and bedroom; metal and wood; lighting and interior items.</p>"
                        . "<p><strong>Materials:</strong> Russian steel, Turkish Micropul powder paints, Siberian larch, Italian Sirca lacquers, Austrian Kronospan and Egger boards, 6mm tempered glass, velour, polyester, water-repellent fabrics, foam.</p>"
                        . "<p><strong>Capacity:</strong> 7,900 items/month (94,990/year).</p>"
                        . "<p><strong>Exports:</strong> Russia, Belarus, Azerbaijan, Armenia, Kazakhstan, Kyrgyzstan, Tajikistan, Turkmenistan, UAE, Saudi Arabia, USA.</p>"
                        . "<p><strong>Clients:</strong> AMIRSOY, Yandex Uzbekistan, Myata Lounge, BOLO HOUZ, ATLANTIS Aqua Park, Hammersmith, TOKU, GAO GAO Rooftop, Yakamoz and others.</p>"
                        . "<p><strong>Awards:</strong> Brand of the Year 2021 & 2022, Brand Awards International 2024.</p>"
                        . "<p><strong>Warranty:</strong> indoor — 12 months, outdoor rattan — 24 months.</p>",
                ],
                'website_url'   => 'https://aiko.uz/',
                'contact_email' => 'sales@aiko.uz',
                'contact_phone' => '+998 55 508 11 88',
                'socials' => [
                    'instagram' => 'aiko.uz',
                    'telegram'  => 'aiko_uz',
                    'facebook'  => 'https://www.facebook.com/Aiko.uz',
                ],
            ],

            // 2. ERGO OFFICE
            [
                'slug' => 'ergo-office',
                'category' => 'production',
                'region' => 'tashkent_city',
                'is_published' => true,
                'show_on_home' => true,
                'sort' => 20,
                'founded_year' => 2022,
                'logo_text' => 'ERGO',
                'name' => ['ru' => 'ERGO OFFICE', 'uz' => 'ERGO OFFICE', 'en' => 'ERGO OFFICE'],
                'description' => [
                    'ru' => 'Комплексные решения для офиса — от проекта до монтажа. Официальный представитель LAS, Okamura, Herman Miller, Haworth в Узбекистане. 86 сотрудников, 10 000+ проектов.',
                    'uz' => 'Ofis uchun kompleks yechimlar — loyihadan montajgacha. LAS, Okamura, Herman Miller, Haworth ning Oʻzbekistondagi rasmiy vakili. 86 xodim, 10 000+ loyiha.',
                    'en' => 'Complete office solutions — from concept to installation. Official representative of LAS, Okamura, Herman Miller and Haworth in Uzbekistan. 86 employees, 10,000+ projects.',
                ],
                'about' => [
                    'ru' => "<p><strong>ERGO OFFICE</strong> — производство офисной мебели, проектирование и полное оснащение офисов. Официальный представитель LAS, Okamura, KANO, Haworth, Herman Miller, BOS Barcelona, LiberNovo в Узбекистане.</p>"
                        . "<p><strong>Продукция:</strong> корпусная и мягкая офисная мебель, кресла с сеткой и кожей, акустические панели, ковровая плитка, LVT, стеклянные перегородки.</p>"
                        . "<p><strong>Собственное производство:</strong> корпусная мебель и отдельные модели сетчатых кресел.</p>"
                        . "<p><strong>Площади:</strong> шоурум 3 000 м², производство 1 000–15 000 м².</p>"
                        . "<p><strong>Клиенты:</strong> Uzbekistan Airways, Centrum Air, Qanot Sharq, My Freighter, UNICEF, NBU, Uzum, Uzcard, Agrobank, Aloqabank, Xalq Banki, Humo, Tenge Bank, SQB, Яндекс, mobiUz, BI Group, KOC Construction.</p>"
                        . "<p><strong>Награды:</strong> четырёхкратный лауреат «Brand of the Year», номинант Best Office Awards, член American Chamber of Commerce.</p>"
                        . "<p><strong>Гарантия:</strong> 3–5 лет. География: Узбекистан, Казахстан, Таджикистан.</p>",
                    'uz' => "<p><strong>ERGO OFFICE</strong> — ofis mebellari ishlab chiqarish, ofislarni loyihalash va toʻliq jihozlash. LAS, Okamura, KANO, Haworth, Herman Miller, BOS Barcelona, LiberNovo ning Oʻzbekistondagi rasmiy vakili.</p>"
                        . "<p><strong>Mahsulotlar:</strong> korpus va yumshoq ofis mebellari, toʻr va teri ofis kreslolari, akustik panellar, gilam plitkalari, LVT, shisha peregorodkalar.</p>"
                        . "<p><strong>Oʻz ishlab chiqarish:</strong> korpus mebellari va ayrim toʻrli kreslolar.</p>"
                        . "<p><strong>Maydonlar:</strong> shou-rum 3 000 m², ishlab chiqarish 1 000–15 000 m².</p>"
                        . "<p><strong>Mijozlar:</strong> Uzbekistan Airways, Centrum Air, Qanot Sharq, My Freighter, UNICEF, NBU, Uzum, Uzcard, Agrobank, Aloqabank, Xalq Banki, Humo, Tenge Bank, SQB, Yandex, mobiUz, BI Group, KOC Construction.</p>"
                        . "<p><strong>Mukofotlar:</strong> 4 marta “Brand of the Year”, Best Office Awards nomzodi, American Chamber of Commerce aʼzosi.</p>"
                        . "<p><strong>Kafolat:</strong> 3–5 yil. Geografiya: Oʻzbekiston, Qozogʻiston, Tojikiston.</p>",
                    'en' => "<p><strong>ERGO OFFICE</strong> — manufacturer of office furniture, office design and turnkey fit-out. Official representative of LAS, Okamura, KANO, Haworth, Herman Miller, BOS Barcelona and LiberNovo in Uzbekistan.</p>"
                        . "<p><strong>Products:</strong> case and upholstered office furniture, mesh and leather chairs, acoustic panels, carpet tiles, LVT, glass partitions.</p>"
                        . "<p><strong>Own production:</strong> case furniture and select mesh chair models.</p>"
                        . "<p><strong>Space:</strong> 3,000 m² showroom, 1,000–15,000 m² production.</p>"
                        . "<p><strong>Clients:</strong> Uzbekistan Airways, Centrum Air, Qanot Sharq, My Freighter, UNICEF, NBU, Uzum, Uzcard, Agrobank, Aloqabank, Xalq Banki, Humo, Tenge Bank, SQB, Yandex, mobiUz, BI Group, KOC Construction.</p>"
                        . "<p><strong>Awards:</strong> 4× Brand of the Year, Best Office Awards nominee, American Chamber of Commerce member.</p>"
                        . "<p><strong>Warranty:</strong> 3–5 years. Coverage: Uzbekistan, Kazakhstan, Tajikistan.</p>",
                ],
                'website_url' => 'https://ergo.uz',
                'contact_email' => 'sales@ergo.uz',
                'contact_phone' => '+998 55 508 55 55',
                'socials' => ['instagram' => 'ergo.uz'],
            ],

            // 3. IHLOS
            [
                'slug' => 'ihlos-furniture',
                'category' => 'production',
                'region' => 'tashkent_city',
                'is_published' => true,
                'show_on_home' => true,
                'sort' => 30,
                'founded_year' => 2005,
                'logo_text' => 'IHLOS',
                'logo_image' => 'partners/ihlos.png',
                'name' => ['ru' => 'IHLOS Furniture', 'uz' => 'IHLOS Furniture', 'en' => 'IHLOS Furniture'],
                'description' => [
                    'ru' => 'Один из лидеров рынка современной мебели для дома, офиса и HoReCa. С 2005 года. Экспорт в ОАЭ, Германию, Саудовскую Аравию, США.',
                    'uz' => 'Uy, ofis va HoReCa uchun zamonaviy mebel bozori yetakchilaridan biri. 2005-yildan beri. BAA, Germaniya, Saudiya Arabistoni, AQShga eksport.',
                    'en' => 'One of the market leaders in modern furniture for home, office and HoReCa. Since 2005. Exports to UAE, Germany, Saudi Arabia and USA.',
                ],
                'about' => [
                    'ru' => "<p><strong>IHLOS Furniture</strong> — мебельная фабрика с 2005 года. 150 сотрудников, 30 000 изделий в год, производство и склад более 6 000 м² с европейским оборудованием.</p>"
                        . "<p><strong>Продукция:</strong> современные, классические, лофт-столы и стулья, барные стулья, кофейные столики, мягкая мебель.</p>"
                        . "<p><strong>Материалы:</strong> оборудование Италия/Турция/Китай, массив дуба, ореха, чёрного клена, экологичные лаки и краски.</p>"
                        . "<p><strong>Экспорт:</strong> ОАЭ, Германия, Саудовская Аравия, Таджикистан, Казахстан, Азербайджан, Россия, США.</p>"
                        . "<p><strong>Проектов:</strong> ~2000, клиентов ~5000. BON CAFE, GIOTTO, SAFIA, Олий Мажлис, Президентская администрация — Кўксарой, CTR CHICKEN, PAPAHA, OSTERIO MARIO, SHVILI, GUNAYDIN, ETCI MEHMET, MAHMOOD KABOB, ZOHID KEBAB.</p>"
                        . "<p><strong>Сертификаты:</strong> ISO 9001:2015, ISO 14001:2015, ISO 45001:2018.</p>"
                        . "<p><strong>Гарантия:</strong> 3 года. Есть доставка и монтаж.</p>",
                    'uz' => "<p><strong>IHLOS Furniture</strong> — 2005-yildan beri mebel fabrikasi. 150 xodim, yiliga 30 000 mahsulot, ishlab chiqarish va ombor 6 000 m² dan ortiq, Yevropa uskunalari.</p>"
                        . "<p><strong>Mahsulotlar:</strong> zamonaviy, klassik, loft uslubidagi stol-stullar, bar stullari, qahva stollari, yumshoq mebel.</p>"
                        . "<p><strong>Materiallar:</strong> Italiya/Turkiya/Xitoy uskunalari, eman, qora qayin, yongʻoq massivi, ekologik toza lak-boʻyoq.</p>"
                        . "<p><strong>Eksport:</strong> BAA, Germaniya, Saudiya Arabistoni, Tojikiston, Qozogʻiston, Ozarbayjon, Rossiya, AQSh.</p>"
                        . "<p><strong>Loyihalar:</strong> ~2000, mijozlar ~5000. BON CAFE, GIOTTO, SAFIA, Oliy Majlis, Prezident Administratsiyasi — Koʻksaroy, CTR CHICKEN, PAPAHA, OSTERIO MARIO, SHVILI, GUNAYDIN, ETCI MEHMET, MAHMOOD KABOB, ZOHID KEBAB.</p>"
                        . "<p><strong>Sertifikatlar:</strong> ISO 9001:2015, ISO 14001:2015, ISO 45001:2018.</p>"
                        . "<p><strong>Kafolat:</strong> 3 yil. Yetkazib berish va montaj mavjud.</p>",
                    'en' => "<p><strong>IHLOS Furniture</strong> — a furniture factory since 2005. 150 employees, 30,000 items per year, over 6,000 m² of European-equipped production and warehousing.</p>"
                        . "<p><strong>Products:</strong> modern, classic and loft tables and chairs, bar stools, coffee tables, upholstered furniture.</p>"
                        . "<p><strong>Materials:</strong> Italian/Turkish/Chinese equipment, solid oak, walnut, black birch; eco-safe lacquers and paints.</p>"
                        . "<p><strong>Exports:</strong> UAE, Germany, Saudi Arabia, Tajikistan, Kazakhstan, Azerbaijan, Russia, USA.</p>"
                        . "<p><strong>Projects:</strong> ~2,000, ~5,000 clients. BON CAFE, GIOTTO, SAFIA, Oliy Majlis, Presidential Administration — Ko‘ksaroy, CTR CHICKEN, PAPAHA, OSTERIO MARIO, SHVILI, GUNAYDIN, ETCI MEHMET, MAHMOOD KABOB, ZOHID KEBAB.</p>"
                        . "<p><strong>Certifications:</strong> ISO 9001:2015, ISO 14001:2015, ISO 45001:2018.</p>"
                        . "<p><strong>Warranty:</strong> 3 years. Delivery and installation available.</p>",
                ],
                'website_url' => 'https://ihlosfurniture.com',
                'contact_email' => 'Info@ihlos.uz',
                'contact_phone' => '+998 99 444 00 05',
                'socials' => ['instagram' => 'ihlosfurniture'],
            ],

            // 4. SHARAF MEBEL
            [
                'slug' => 'sharaf-mebel',
                'category' => 'production',
                'region' => 'tashkent_region',
                'is_published' => true,
                'show_on_home' => true,
                'sort' => 40,
                'founded_year' => 2007,
                'logo_text' => 'SHARAF',
                'name' => ['ru' => 'Sharaf Mebel', 'uz' => 'Sharaf Mebel', 'en' => 'Sharaf Mebel'],
                'description' => [
                    'ru' => 'Узбекский бренд корпусной мебели: спальные гарнитуры, шкафы, кровати, коридорная мебель и индивидуальные заказы. 80+ дилерских точек.',
                    'uz' => 'Korpus mebellari uzbek brendi: yotoqxona garniturlari, shkaflar, krovatlar, koridor mebellari va individual buyurtmalar. 80+ dilerlik nuqtasi.',
                    'en' => 'Uzbek case furniture brand: bedroom sets, wardrobes, beds, hallway furniture and custom orders. 80+ dealer locations.',
                ],
                'about' => [
                    'ru' => "<p><strong>Sharaf Mebel</strong> (юр. INTER ORIGINAL MEBEL) — производитель корпусной мебели с 2007 года. Собственная фабрика 3 000 м², 30 сотрудников, мощность 300 шкафов/месяц.</p>"
                        . "<p><strong>Оборудование:</strong> HOMAG SAWTEQ B-300 (пиление), EDGETEQ S-380 (кромка), точная сверлилка, механизмы Hettich, GTV, HiTech.</p>"
                        . "<p><strong>Продукция:</strong> LDSP/MDF корпус, шкафы, кровати, тумбы и трюмо, коридорная мебель, готовые и индивидуальные модели.</p>"
                        . "<p><strong>Сеть:</strong> 80+ дилеров по Узбекистану, два шоурума в Ташкенте (Зангиата + Кичик халка йўли, 8G).</p>"
                        . "<p><strong>Гарантия:</strong> 24 месяца. Доставка и профессиональный монтаж в Ташкенте — бесплатно для B2C.</p>",
                    'uz' => "<p><strong>Sharaf Mebel</strong> (yur. INTER ORIGINAL MEBEL MChJ) — 2007-yildan beri korpus mebellari ishlab chiqaruvchisi. Oʻz fabrikasi 3 000 m², 30 xodim, oyiga 300 shkaf.</p>"
                        . "<p><strong>Uskunalar:</strong> HOMAG SAWTEQ B-300, EDGETEQ S-380, aniq teshish, Hettich/GTV/HiTech mexanizmlari.</p>"
                        . "<p><strong>Mahsulotlar:</strong> LDSP/MDF korpus, shkaflar, krovatlar, tumba va trumolar, koridor mebellari, tayyor va individual modellar.</p>"
                        . "<p><strong>Tarmoq:</strong> Oʻzbekiston boʻylab 80+ diler, Toshkentda 2 shou-rum.</p>"
                        . "<p><strong>Kafolat:</strong> 24 oy. Toshkent boʻylab yetkazib berish va montaj B2C uchun bepul.</p>",
                    'en' => "<p><strong>Sharaf Mebel</strong> (legally INTER ORIGINAL MEBEL LLC) — a case furniture manufacturer since 2007. Own 3,000 m² factory, 30 staff, capacity 300 wardrobes/month.</p>"
                        . "<p><strong>Equipment:</strong> HOMAG SAWTEQ B-300, EDGETEQ S-380, precision drilling; Hettich, GTV, HiTech hardware.</p>"
                        . "<p><strong>Products:</strong> LDSP/MDF case, wardrobes, beds, dressers, hallway furniture, stock and custom.</p>"
                        . "<p><strong>Network:</strong> 80+ dealers across Uzbekistan, two Tashkent showrooms.</p>"
                        . "<p><strong>Warranty:</strong> 24 months. Free delivery and pro installation across Tashkent for B2C.</p>",
                ],
                'website_url' => 'https://sharafmebel.uz',
                'contact_phone' => '+998 88 496 44 44',
                'socials' => ['instagram' => 'sharafmebel', 'telegram' => 'sharafmebel'],
            ],

            // 5. YASSI ELEGANCE
            [
                'slug' => 'yassi-elegance',
                'category' => 'production',
                'region' => 'tashkent_region',
                'is_published' => true,
                'show_on_home' => true,
                'sort' => 50,
                'founded_year' => 1999,
                'logo_text' => 'YASSI',
                'name' => ['ru' => 'Yassi Elegance', 'uz' => 'Yassi Elegance', 'en' => 'Yassi Elegance'],
                'description' => [
                    'ru' => 'Производитель столов и стульев с 1999 года. Дизайн, прочность, эргономика. 50–60 сотрудников, экспорт в Казахстан, Россию, Киргизию, Таджикистан.',
                    'uz' => '1999-yildan stol va stul ishlab chiqaruvchi. Dizayn, mustahkamlik, ergonomika. 50–60 xodim, Qozogʻiston, Rossiya, Qirgʻiziston, Tojikistonga eksport.',
                    'en' => 'Table and chair manufacturer since 1999. Design, durability, ergonomics. 50–60 staff; exports to Kazakhstan, Russia, Kyrgyzstan, Tajikistan.',
                ],
                'about' => [
                    'ru' => "<p><strong>Yassi Elegance</strong> (юр. YASSI OBOD BIZNES) — производство столов и стульев с 1999 года. 50–60 сотрудников, три собственных магазина в Ташкенте и дилерская сеть в каждой области.</p>"
                        . "<p><strong>Материалы:</strong> массив дерева, фанера, LMDF, MDF, ДСП, немецкий клей, польские/китайские/турецкие ткани.</p>"
                        . "<p><strong>Клиенты:</strong> Safia, Yapona Mama, Giotto, Ecorn, Global Coffee, Dag House (Москва), Sakura, Donerci Hamdi Usta, Mondo, Jonon Chicken, Go'sht, Gruzinka, Ribambelle, Rabbat (Алматы), LAGHAN.</p>"
                        . "<p><strong>Член MEYOS.</strong></p>",
                    'uz' => "<p><strong>Yassi Elegance</strong> (yur. YASSI OBOD BIZNES) — 1999-yildan stol va stul ishlab chiqarish. 50–60 xodim, Toshkentda 3 doʻkon va har viloyatda dilerlar.</p>"
                        . "<p><strong>Materiallar:</strong> massiv yogʻoch, fanera, LMDF, MDF, DSP, olmon kleyi, Polsha/Xitoy/Turk matolari.</p>"
                        . "<p><strong>Mijozlar:</strong> Safia, Yapona Mama, Giotto, Ecorn, Global Coffee, Dag House (Moskva), Sakura, Donerci Hamdi Usta, Mondo, Jonon Chicken, Goʻsht, Gruzinka, Ribambelle, Rabbat (Almaty), LAGHAN.</p>"
                        . "<p><strong>MEYOS aʼzosi.</strong></p>",
                    'en' => "<p><strong>Yassi Elegance</strong> (legally YASSI OBOD BIZNES) — table and chair production since 1999. 50–60 staff, three own stores in Tashkent plus regional dealers.</p>"
                        . "<p><strong>Materials:</strong> solid wood, plywood, LMDF, MDF, chipboard, German glue, Polish/Chinese/Turkish fabrics.</p>"
                        . "<p><strong>Clients:</strong> Safia, Yapona Mama, Giotto, Ecorn, Global Coffee, Dag House (Moscow), Sakura, Donerci Hamdi Usta, Mondo, Jonon Chicken, Go'sht, Gruzinka, Ribambelle, Rabbat (Almaty), LAGHAN.</p>"
                        . "<p><strong>MEYOS member.</strong></p>",
                ],
                'website_url' => 'https://yassielegance.com',
                'contact_email' => 'yassielegance@gmail.com',
                'contact_phone' => '+998 91 132 67 77',
                'socials' => ['instagram' => 'yassi_elegance'],
            ],

            // 6. NORITSUISU (ACES JAPAN)
            [
                'slug' => 'noritsuisu',
                'category' => 'production',
                'region' => 'tashkent_city',
                'is_published' => true,
                'show_on_home' => true,
                'sort' => 60,
                'founded_year' => 2021,
                'logo_text' => 'NORITSU',
                'name' => ['ru' => 'Noritsuisu', 'uz' => 'Noritsuisu', 'en' => 'Noritsuisu'],
                'description' => [
                    'ru' => 'Японское качество офисных кресел в Узбекистане. Сертификаты ISO, JIS, GOS. Гарантия 3 года, срок эксплуатации 15+ лет. 10 000 кресел в год.',
                    'uz' => 'Ofis kreslolarining yapon sifati Oʻzbekistonda. ISO, JIS, GOS sertifikatlari. 3 yil kafolat, ishlatish muddati 15+ yil. Yiliga 10 000 ta kreslo.',
                    'en' => 'Japanese-quality office chairs in Uzbekistan. ISO, JIS and GOS certified. 3-year warranty, 15+ years service life. 10,000 chairs/year.',
                ],
                'about' => [
                    'ru' => "<p><strong>Aces Japan LLC (Noritsuisu)</strong> — с 2021 года производит офисные кресла в Ташкенте, сочетая традиционное японское мастерство и современный дизайн. 10 сотрудников, площадь 1 500 м².</p>"
                        . "<p><strong>Сертификаты:</strong> ISO, JIS, GOS. Бренд с 80-летней историей.</p>"
                        . "<p><strong>Клиенты:</strong> Toyota Uzbekistan, UJC Tashkent, Anorbank, Damira Beverages, Ucell, Profi University, Vosiq International School, KIMYO International University, Ташкентский аграрный университет.</p>"
                        . "<p><strong>Экспорт:</strong> Узбекистан и Средняя Азия.</p>"
                        . "<p><strong>Логистика:</strong> доставка в течение 24 часов, сервис без ограничения срока.</p>",
                    'uz' => "<p><strong>Aces Japan XK MChJ (Noritsuisu)</strong> — 2021-yildan Toshkentda ofis kreslolari ishlab chiqarish. Anʼanaviy yapon ustachiligini zamonaviy dizayn bilan uygʻunlashtiradi. 10 xodim, 1 500 m².</p>"
                        . "<p><strong>Sertifikatlar:</strong> ISO, JIS, GOS. 80 yillik brend tarixi.</p>"
                        . "<p><strong>Mijozlar:</strong> Toyota Uzbekistan, UJC Tashkent, Anorbank, Damira Beverages, Ucell, Profi University, Vosiq International School, KIMYO International University, Toshkent Agrar universiteti.</p>"
                        . "<p><strong>Eksport:</strong> Oʻzbekiston va Markaziy Osiyo.</p>"
                        . "<p><strong>Logistika:</strong> 24 soat ichida yetkazib berish, cheksiz muddatga servis.</p>",
                    'en' => "<p><strong>Aces Japan LLC (Noritsuisu)</strong> — has produced office chairs in Tashkent since 2021, combining traditional Japanese craftsmanship with modern design. 10 staff, 1,500 m².</p>"
                        . "<p><strong>Certifications:</strong> ISO, JIS, GOS. 80-year brand heritage.</p>"
                        . "<p><strong>Clients:</strong> Toyota Uzbekistan, UJC Tashkent, Anorbank, Damira Beverages, Ucell, Profi University, Vosiq International School, KIMYO International University, Tashkent Agrarian University.</p>"
                        . "<p><strong>Exports:</strong> Uzbekistan and Central Asia.</p>"
                        . "<p><strong>Logistics:</strong> 24-hour delivery, unlimited service.</p>",
                ],
                'website_url' => 'https://noritsuisu.com',
                'contact_email' => 'islom_isaakov@noritsuisu.co.jp',
                'contact_phone' => '+998 99 840 91 91',
                'socials' => ['instagram' => 'noritsuisu_uzbekistan'],
            ],

            // 7. MURATBEK MEBEL
            [
                'slug' => 'muratbek-mebel',
                'category' => 'production',
                'region' => 'karakalpakstan',
                'is_published' => true,
                'show_on_home' => false,
                'sort' => 70,
                'founded_year' => 2009,
                'logo_text' => 'MURATBEK',
                'name' => ['ru' => 'MURATBEK MEBEL', 'uz' => 'MURATBEK MEBEL', 'en' => 'MURATBEK MEBEL'],
                'description' => [
                    'ru' => 'Мебель на заказ для дома, офиса, госучреждений и медицины. Каракалпакстан, Нукус. С 2009 года. Победитель конкурса «Ташаббус — 2018».',
                    'uz' => 'Uy, ofis, davlat idoralari va meditsina uchun buyurtma asosida mebel. Qoraqalpogʻiston, Nukus. 2009-yildan. “Tashabbus 2018” gʻolibi.',
                    'en' => 'Custom furniture for home, office, government and medical facilities. Karakalpakstan, Nukus. Since 2009. Winner of the "Tashabbus 2018" contest.',
                ],
                'about' => [
                    'ru' => "<p><strong>MURATBEK MEBEL</strong> — производитель мебели на заказ с 2009 года. 34 сотрудника, 1 500 м² производства, 570 м² шоурум. Годовой оборот ~5–5,5 млрд сумов.</p>"
                        . "<p><strong>Материалы:</strong> LDSP, LMDF, ДСП, акрил, МДФ, металлопрофиль. Оборудование: пильные, кромкообработка, ЧПУ, пресс, Rover.</p>"
                        . "<p><strong>Крупные проекты:</strong> таможенный пост Довут-Ота, Damir Hotel, Ramada Hotel, ресторан Dunyo Multi (Нукус).</p>"
                        . "<p><strong>Награды:</strong> победитель «Tashabbus 2018» в Каракалпакстане.</p>"
                        . "<p><strong>Гарантия:</strong> 1 год + сервис. Доставка и монтаж по региону.</p>",
                    'uz' => "<p><strong>MURATBEK MEBEL MChJ</strong> — 2009-yildan buyurtma asosida mebel. 34 xodim, 1 500 m² ishlab chiqarish, 570 m² shou-rum. Yillik aylanma ~5–5,5 mlrd soʻm.</p>"
                        . "<p><strong>Materiallar:</strong> LDSP, LMDF, DSP, akril, MDF, temir profillar. Uskunalar: kesuvchi arra, kromka, CNC, press, Rover.</p>"
                        . "<p><strong>Yirik loyihalar:</strong> Dovut Ota bojxona posti, Damir Hotel, Ramada Hotel, Dunyo Multi restorani (Nukus).</p>"
                        . "<p><strong>Mukofot:</strong> Qoraqalpogʻistonda “Tashabbus 2018” gʻolibi.</p>"
                        . "<p><strong>Kafolat:</strong> 1 yil + servis. Yetkazib berish va montaj mavjud.</p>",
                    'en' => "<p><strong>MURATBEK MEBEL LLC</strong> — custom furniture manufacturer since 2009. 34 staff, 1,500 m² production, 570 m² showroom. Annual turnover ~5–5.5 billion soʻm.</p>"
                        . "<p><strong>Materials:</strong> LDSP, LMDF, chipboard, acrylic, MDF, steel profiles. Equipment: saws, edge-banding, CNC, press, Rover.</p>"
                        . "<p><strong>Major projects:</strong> Dovut-Ota customs post, Damir Hotel, Ramada Hotel, Dunyo Multi restaurant (Nukus).</p>"
                        . "<p><strong>Awards:</strong> Winner of the \"Tashabbus 2018\" contest in Karakalpakstan.</p>"
                        . "<p><strong>Warranty:</strong> 1 year + service. Delivery and installation available.</p>",
                ],
                'contact_email' => 'Muratbekmebel8008@gmail.com',
                'contact_phone' => '+998 55 108 80 80',
                'socials' => ['instagram' => 'muratbekmebel', 'telegram' => 'muratbekmebel', 'facebook' => 'https://facebook.com/muratbekmebel'],
            ],

            // 8. Woodone / Mono mebel
            [
                'slug' => 'woodone',
                'category' => 'production',
                'region' => 'tashkent_region',
                'is_published' => true,
                'show_on_home' => false,
                'sort' => 80,
                'founded_year' => 2014,
                'logo_text' => 'WOODONE',
                'name' => ['ru' => 'Woodone', 'uz' => 'Woodone', 'en' => 'Woodone'],
                'description' => [
                    'ru' => 'Спальные гарнитуры и мебель для детских комнат. Собственное закрытое производство, конвейерная сборка. Экспорт в Казахстан. С 2014 года.',
                    'uz' => 'Yotoqxona garniturlari va bolalar xonalari uchun mebel. Toʻliq yopiq ishlab chiqarish, konveyer. Qozogʻistonga eksport. 2014-yildan.',
                    'en' => 'Bedroom sets and children\'s room furniture. Fully in-house production, assembly line. Exports to Kazakhstan. Since 2014.',
                ],
                'about' => [
                    'ru' => "<p><strong>Woodone</strong> (с 2023 также бренд Mono Mebel) — мебельная фабрика с закрытым циклом. Собственная база 1 000 м², конвейерная сборка, 30+ квалифицированных сотрудников.</p>"
                        . "<p><strong>Оборудование:</strong> Brandt (Германия), Excitech (Китай). Сырьё: Ultradecor. Фурнитура: Hettich, Mesan, Aosite. Краски: Betek, Gench.</p>"
                        . "<p><strong>Мощность:</strong> 100+ моделей в месяц. 10–12 постоянных дилеров.</p>"
                        . "<p><strong>Экспорт:</strong> Узбекистан + Казахстан (с июля 2026).</p>"
                        . "<p><strong>Модель работы:</strong> вся розница через дилерскую сеть, монтаж — через дилеров.</p>",
                    'uz' => "<p><strong>Woodone</strong> (2023-yildan “Mono Mebel” brendi ham) — toʻliq yopiq ishlab chiqarishga ega mebel fabrikasi. Oʻz bazasi 1 000 m², konveyer, 30+ malakali xodim.</p>"
                        . "<p><strong>Uskuna:</strong> Brandt (Germaniya), Excitech (Xitoy). Xomashyo: Ultradecor. Furnitura: Hettich, Mesan, Aosite. Boʻyoqlar: Betek, Gench.</p>"
                        . "<p><strong>Quvvat:</strong> oyiga 100+ model. 10–12 doimiy diler.</p>"
                        . "<p><strong>Eksport:</strong> Oʻzbekiston + Qozogʻiston (2026-yil iyul).</p>"
                        . "<p><strong>Model:</strong> barcha chakana savdo dilerlar orqali, montaj ham dilerlar orqali.</p>",
                    'en' => "<p><strong>Woodone</strong> (also Mono Mebel brand since 2023) — a fully in-house furniture factory. 1,000 m² own base, assembly line, 30+ skilled staff.</p>"
                        . "<p><strong>Equipment:</strong> Brandt (Germany), Excitech (China). Raw materials: Ultradecor. Hardware: Hettich, Mesan, Aosite. Paints: Betek, Gench.</p>"
                        . "<p><strong>Capacity:</strong> 100+ models/month. 10–12 long-term dealers.</p>"
                        . "<p><strong>Exports:</strong> Uzbekistan + Kazakhstan (from July 2026).</p>"
                        . "<p><strong>Model:</strong> all retail via dealer network, installation via dealers.</p>",
                ],
                'contact_email' => 'XojaNizamov@mail.ru',
                'contact_phone' => '+998 93 380 95 59',
                'socials' => ['instagram' => 'woodone_uz'],
            ],

            // 9. Мир Матрасов
            [
                'slug' => 'mir-matrasov',
                'category' => 'production',
                'region' => 'tashkent_city',
                'is_published' => true,
                'show_on_home' => true,
                'sort' => 90,
                'founded_year' => 2000,
                'logo_text' => 'MATRAS',
                'name' => [
                    'ru' => 'Мир Матрасов',
                    'uz' => 'Mir Matrasov',
                    'en' => 'Mir Matrasov',
                ],
                'description' => [
                    'ru' => 'Ортопедические и анатомические матрасы с 2000 года. 30+ моделей, европейское наполнение, экспорт по регионам Узбекистана. Поставщик Radisson Blu, Lotte City и Asia Hotels.',
                    'uz' => '2000-yildan ortopedik va anatomik matraslar. 30+ model, Yevropa toʻldiruvchi, Oʻzbekiston boʻylab eksport. Radisson Blu, Lotte City va Asia Hotels yetkazib beruvchisi.',
                    'en' => 'Orthopedic and anatomical mattresses since 2000. 30+ models, European fillings, exports across Uzbekistan. Supplier to Radisson Blu, Lotte City and Asia Hotels.',
                ],
                'about' => [
                    'ru' => "<p><strong>Мир Матрасов</strong> — производство ортопедических и анатомических матрасов с 2000 года. 20 сотрудников, до 5 000 матрасов в месяц. Шоурум 300 м², производство 3 000 м².</p>"
                        . "<p><strong>Материалы:</strong> пружинные блоки, пенополиуретан, анатомические слои, поролоны из Европы.</p>"
                        . "<p><strong>Клиенты:</strong> Radisson Blu, Lotte City Hotel Tashkent Palace, Asia Hotels, Tashkent Hotel; санатории, гостиницы, дома отдыха. 300 000+ клиентов за 25 лет.</p>"
                        . "<p><strong>Гарантия:</strong> от 3 до 15 лет в зависимости от модели.</p>"
                        . "<p><strong>Логистика:</strong> доставка по Ташкенту + сервисное обслуживание.</p>",
                    'uz' => "<p><strong>Mir Matrasov</strong> — 2000-yildan ortopedik va anatomik matraslar ishlab chiqarish. 20 xodim, oyiga 5 000 matrasgacha. Shou-rum 300 m², ishlab chiqarish 3 000 m².</p>"
                        . "<p><strong>Materiallar:</strong> prujina bloklari, penopoliuretan, anatomik qatlamlar, Yevropa poroloni.</p>"
                        . "<p><strong>Mijozlar:</strong> Radisson Blu, Lotte City Hotel Tashkent Palace, Asia Hotels, Tashkent Hotel; sanatoriylar, mehmonxonalar, dam olish maskanlari. 25 yil ichida 300 000+ mijoz.</p>"
                        . "<p><strong>Kafolat:</strong> modelga qarab 3 dan 15 yilgacha.</p>"
                        . "<p><strong>Logistika:</strong> Toshkent boʻylab yetkazib berish va servis.</p>",
                    'en' => "<p><strong>Mir Matrasov</strong> — orthopedic and anatomical mattresses since 2000. 20 staff, up to 5,000 mattresses/month. 300 m² showroom, 3,000 m² production.</p>"
                        . "<p><strong>Materials:</strong> spring blocks, polyurethane foam, anatomical layers, European foams.</p>"
                        . "<p><strong>Clients:</strong> Radisson Blu, Lotte City Hotel Tashkent Palace, Asia Hotels, Tashkent Hotel; sanatoriums, hotels, resorts. 300,000+ customers over 25 years.</p>"
                        . "<p><strong>Warranty:</strong> 3 to 15 years depending on model.</p>"
                        . "<p><strong>Logistics:</strong> delivery across Tashkent + after-sales service.</p>",
                ],
                'contact_email' => 'Matras.uz',
                'contact_phone' => '+998 97 770 09 62',
            ],

            // 10. DIVANNOVO
            [
                'slug' => 'divannovo',
                'category' => 'production',
                'region' => 'tashkent_region',
                'is_published' => true,
                'show_on_home' => true,
                'sort' => 100,
                'founded_year' => 2009,
                'logo_text' => 'DIVAN',
                'logo_image' => 'partners/divannovo.jpg',
                'name' => ['ru' => 'DIVANNOVO', 'uz' => 'DIVANNOVO', 'en' => 'DIVANNOVO'],
                'description' => [
                    'ru' => 'Мягкая мебель на заказ: диваны, кресла. Индивидуальные размеры, финансовая ответственность за сроки. 64 000+ подписчиков в Instagram. С 2009 года.',
                    'uz' => 'Buyurtma asosida yumshoq mebel: divan, kreslo. Individual oʻlchamlar, muddat uchun moliyaviy javobgarlik. Instagramda 64 000+ obunachi. 2009-yildan.',
                    'en' => 'Custom upholstered furniture: sofas and armchairs. Individual sizes, financial liability for deadlines. 64,000+ Instagram followers. Since 2009.',
                ],
                'about' => [
                    'ru' => "<p><strong>DIVANNOVO</strong> (юр. QALBINUR MEBEL FAYZ) — производитель диванов и кресел с 2009 года. 8 сотрудников, 500 м² производства.</p>"
                        . "<p><strong>Материалы:</strong> Польша, Турция, Китай. Поролон Egida, Foam line. Механизмы раскладывания.</p>"
                        . "<p><strong>Преимущества:</strong> изготовление в согласованные сроки; финансовая компенсация за каждый день просрочки; кастомизация размера/цвета/материала/наполнителя; индивидуальные заказы.</p>"
                        . "<p><strong>География:</strong> вся территория Узбекистана.</p>"
                        . "<p><strong>Гарантия:</strong> 12 месяцев. Средний срок изготовления — 7–10 дней.</p>",
                    'uz' => "<p><strong>DIVANNOVO</strong> (yur. QALBINUR MEBEL FAYZ) — 2009-yildan divan va kreslo ishlab chiqaruvchi. 8 xodim, 500 m² ishlab chiqarish.</p>"
                        . "<p><strong>Materiallar:</strong> Polsha, Turkiya, Xitoy. Paralon: Egida, Foam line. Yigʻiladigan mexanizmlar.</p>"
                        . "<p><strong>Ustunliklar:</strong> kelishilgan muddatda tayyorlash; muddat buzilsa moliyaviy kompensatsiya; oʻlcham/rang/material/toʻldiruvchini tanlash; individual buyurtma.</p>"
                        . "<p><strong>Geografiya:</strong> Oʻzbekiston boʻylab.</p>"
                        . "<p><strong>Kafolat:</strong> 12 oy. Oʻrtacha muddat — 7–10 kun.</p>",
                    'en' => "<p><strong>DIVANNOVO</strong> (legally QALBINUR MEBEL FAYZ) — sofa and armchair manufacturer since 2009. 8 staff, 500 m² production.</p>"
                        . "<p><strong>Materials:</strong> Poland, Turkey, China. Foam: Egida, Foam line. Folding mechanisms.</p>"
                        . "<p><strong>Strengths:</strong> agreed deadlines; financial penalty for late delivery; custom sizes/colors/materials/fillings; individual orders.</p>"
                        . "<p><strong>Coverage:</strong> all of Uzbekistan.</p>"
                        . "<p><strong>Warranty:</strong> 12 months. Average lead time — 7–10 days.</p>",
                ],
                'contact_email' => 'Divannovo.uz@gmail.com',
                'contact_phone' => '+998 94 900 29 00',
                'socials' => ['instagram' => 'DIVANNOVO'],
            ],

            // 11. DAKOTA
            [
                'slug' => 'dakota-sofa-master',
                'category' => 'production',
                'region' => 'tashkent_city',
                'is_published' => true,
                'show_on_home' => false,
                'sort' => 110,
                'founded_year' => 2025,
                'logo_text' => 'DAKOTA',
                'logo_image' => 'partners/dakota.png',
                'name' => ['ru' => 'Dakota Sofa Master', 'uz' => 'Dakota Sofa Master', 'en' => 'Dakota Sofa Master'],
                'description' => [
                    'ru' => 'Мягкая мебель на заказ: диваны и кресла. Индивидуальные размеры и материалы. 18 месяцев гарантии. Ташкент, Сергели.',
                    'uz' => 'Buyurtma asosida yumshoq mebel: divan va kreslo. Individual oʻlcham va material. 18 oy kafolat. Toshkent, Sergeli.',
                    'en' => 'Custom upholstered furniture: sofas and armchairs. Individual sizes and materials. 18-month warranty. Tashkent, Sergeli.',
                ],
                'about' => [
                    'ru' => "<p><strong>Dakota Sofa Master</strong> — молодой производитель мягкой мебели с 2025 года. 8 сотрудников, 200 м² производства.</p>"
                        . "<p><strong>Материалы:</strong> Польша, Турция, Китай. Поролон Egida, Foam line.</p>"
                        . "<p><strong>Преимущества:</strong> изготовление в срок с финансовой ответственностью, кастомизация размера/цвета/материала.</p>"
                        . "<p><strong>Клиенты:</strong> 13 000+ в Instagram. Тренд в тематике «женский трюмо».</p>"
                        . "<p><strong>Сертификат MEYOS.</strong> Гарантия 18 месяцев. Средний срок изготовления — 7 дней.</p>",
                    'uz' => "<p><strong>Dakota Sofa Master</strong> — 2025-yildan yumshoq mebel ishlab chiqaruvchi yosh brend. 8 xodim, 200 m².</p>"
                        . "<p><strong>Materiallar:</strong> Polsha, Turkiya, Xitoy. Paralon: Egida, Foam line.</p>"
                        . "<p><strong>Ustunliklar:</strong> muddatda tayyorlash + moliyaviy javobgarlik, oʻlcham/rang/materialni tanlash.</p>"
                        . "<p><strong>Mijozlar:</strong> Instagramda 13 000+. “Ayollar trumogi” trendi.</p>"
                        . "<p><strong>MEYOS sertifikatiga ega.</strong> Kafolat 18 oy. Oʻrtacha muddat — 7 kun.</p>",
                    'en' => "<p><strong>Dakota Sofa Master</strong> — a young upholstered furniture brand launched in 2025. 8 staff, 200 m².</p>"
                        . "<p><strong>Materials:</strong> Poland, Turkey, China. Foam: Egida, Foam line.</p>"
                        . "<p><strong>Strengths:</strong> on-time delivery with financial liability, custom sizes/colors/materials.</p>"
                        . "<p><strong>Customers:</strong> 13,000+ on Instagram. Trending «women's dresser» models.</p>"
                        . "<p><strong>MEYOS certified.</strong> 18-month warranty. Average lead time — 7 days.</p>",
                ],
                'contact_email' => 'Dakotamebel@gmail.com',
                'contact_phone' => '+998 99 336 35 76',
                'socials' => ['instagram' => 'dakota_comfy'],
            ],

            // 12. WODEX — только логотип
            [
                'slug' => 'wodex',
                'category' => 'production',
                'region' => null,
                'is_published' => true,
                'show_on_home' => false,
                'sort' => 120,
                'logo_text' => 'WODEX',
                'logo_image' => 'partners/wodex.png',
                'name' => ['ru' => 'WODEX', 'uz' => 'WODEX', 'en' => 'WODEX'],
                'description' => [
                    'ru' => 'Партнёр MEYOS. Данные компании уточняются.',
                    'uz' => 'MEYOS hamkori. Kompaniya maʼlumotlari aniqlanmoqda.',
                    'en' => 'MEYOS partner. Company details being finalized.',
                ],
                'about' => null,
            ],

            // 13. MONDELUX (GULOBOD MEBEL) — Самарканд, крупный игрок
            [
                'slug' => 'mondelux',
                'category' => 'production',
                'region' => 'samarkand',
                'is_published' => true,
                'show_on_home' => true,
                'sort' => 130,
                'founded_year' => 2003,
                'logo_text' => 'MONDELUX',
                'name' => ['ru' => 'Mondelux', 'uz' => 'Mondelux', 'en' => 'Mondelux'],
                'description' => [
                    'ru' => 'Три собственные фабрики в Самаркандской области. С 2003 года — корпусная, мягкая, кухонная и офисная мебель. Более 24 000 изделий в год.',
                    'uz' => 'Samarqand viloyatida uchta oʻz fabrikasi. 2003-yildan — korpus, yumshoq, oshxona va ofis mebeli. Yiliga 24 000 dan ortiq mahsulot.',
                    'en' => 'Three own factories in Samarqand region. Since 2003 — case, upholstered, kitchen and office furniture. Over 24,000 units per year.',
                ],
                'about' => [
                    'ru' => "<p><strong>Mondelux</strong> (юр. GULOBOD MEBEL) — производственная группа с 2003 года. 178 сотрудников, три собственные фабрики общей площадью 12 400 м², шоурум 5 000 м².</p>"
                        . "<p><strong>Продукция:</strong> корпусная мебель, мягкая мебель, кухни, офисная мебель, для ресторанов и отелей. 20+ лет опыта, сертификация ISO.</p>"
                        . "<p><strong>Мощность:</strong> 24 000+ изделий в год.</p>"
                        . "<p><strong>Экспорт:</strong> Узбекистан, Россия, Кыргызстан, Казахстан.</p>"
                        . "<p><strong>Гарантия:</strong> 2–5 лет. Средний срок выполнения заказа — 15–30 дней. Доставка и монтаж.</p>",
                    'uz' => "<p><strong>Mondelux</strong> (yur. GULOBOD MEBEL MChJ) — 2003-yildan ishlab chiqarish guruhi. 178 xodim, umumiy maydoni 12 400 m² boʻlgan uchta fabrikasi, 5 000 m² shou-rum.</p>"
                        . "<p><strong>Mahsulotlar:</strong> korpus mebellari, yumshoq mebel, oshxona, ofis mebeli, restoran va mehmonxonalar uchun. 20+ yil tajriba, ISO sertifikatlari.</p>"
                        . "<p><strong>Quvvat:</strong> yiliga 24 000+ mahsulot.</p>"
                        . "<p><strong>Eksport:</strong> Oʻzbekiston, Rossiya, Qirgʻiziston, Qozogʻiston.</p>"
                        . "<p><strong>Kafolat:</strong> 2–5 yil. Oʻrtacha muddat — 15–30 kun. Yetkazib berish va montaj.</p>",
                    'en' => "<p><strong>Mondelux</strong> (legally GULOBOD MEBEL LLC) — a manufacturing group since 2003. 178 staff, three own factories totalling 12,400 m², 5,000 m² showroom.</p>"
                        . "<p><strong>Products:</strong> case furniture, upholstered, kitchens, office, hospitality and hotel. 20+ years of experience, ISO-certified.</p>"
                        . "<p><strong>Capacity:</strong> 24,000+ units per year.</p>"
                        . "<p><strong>Exports:</strong> Uzbekistan, Russia, Kyrgyzstan, Kazakhstan.</p>"
                        . "<p><strong>Warranty:</strong> 2–5 years. Average lead time — 15–30 days. Delivery and installation available.</p>",
                ],
                'website_url' => 'https://mondelux.uz',
                'contact_email' => '937202001@mail.ru',
                'contact_phone' => '+998 93 720 20 77',
                'socials' => ['instagram' => 'mondelux'],
            ],

            // 14. SHOSH CONCEPT — Ташкент, из брошюры MEYOS
            [
                'slug' => 'shosh-concept',
                'category' => 'production',
                'region' => 'tashkent_city',
                'is_published' => true,
                'show_on_home' => true,
                'sort' => 140,
                'founded_year' => 2010,
                'logo_text' => 'SHOSH',
                'name' => ['ru' => 'SHOSH CONCEPT', 'uz' => 'SHOSH CONCEPT', 'en' => 'SHOSH CONCEPT'],
                'description' => [
                    'ru' => 'Один из крупнейших производителей мебели в Узбекистане. Современные ЧПУ-станки, технологический контроль на каждом этапе. С 2010 года.',
                    'uz' => 'Oʻzbekistonning eng yirik mebel ishlab chiqaruvchilaridan biri. Zamonaviy CNC uskunalari, har bir bosqichda texnologik nazorat. 2010-yildan.',
                    'en' => 'One of Uzbekistan\'s largest furniture manufacturers. Modern CNC equipment, technological control at every stage. Since 2010.',
                ],
                'about' => [
                    'ru' => "<p><strong>SHOSH CONCEPT</strong> — производство с 2010 года. Кредо: «Качество превыше прибыли». 120+ сотрудников, 1 000 комплектов в месяц, 50 000+ клиентов.</p>"
                        . "<p><strong>Продукция:</strong> спальни, пеналы и комоды, угловая мягкая мебель, диваны и кресла, индивидуальные дизайн-проекты.</p>"
                        . "<p><strong>Технологии:</strong> современные ЧПУ-станки, контроль качества на всех этапах, экосертификат на МДФ и краску, современные материалы и фурнитура.</p>"
                        . "<p><strong>География:</strong> 13 областей Узбекистана + 3 страны.</p>"
                        . "<p><strong>Гарантия:</strong> 3 года. Постгарантийный сервис. Средний срок заказа — 3–20 дней. Шоурум 600 м².</p>",
                    'uz' => "<p><strong>SHOSH CONCEPT</strong> — 2010-yildan ishlab chiqarish. Kredo: “Sifat foydadan ustun”. 120+ xodim, oyiga 1 000 komplekt, 50 000+ mijoz.</p>"
                        . "<p><strong>Mahsulotlar:</strong> yotoqxona mebellari, penal va komodlar, burchak yumshoq mebel, divan va kreslolar, individual dizayn loyihalari.</p>"
                        . "<p><strong>Texnologiyalar:</strong> zamonaviy CNC uskunalari, barcha bosqichlarda sifat nazorati, MDF va boʻyoq boʻyicha ekosertifikat, zamonaviy material va furnitura.</p>"
                        . "<p><strong>Geografiya:</strong> Oʻzbekistonning 13 viloyati + 3 davlat.</p>"
                        . "<p><strong>Kafolat:</strong> 3 yil. Kafolatdan keyingi servis. Oʻrtacha muddat — 3–20 kun. Shou-rum 600 m².</p>",
                    'en' => "<p><strong>SHOSH CONCEPT</strong> — production since 2010. Credo: «Quality above profit». 120+ staff, 1,000 sets/month, 50,000+ clients.</p>"
                        . "<p><strong>Products:</strong> bedrooms, wardrobes and dressers, corner sofas, sofas and armchairs, custom design projects.</p>"
                        . "<p><strong>Tech:</strong> modern CNC equipment, quality control at every stage, MDF and paint eco-certification.</p>"
                        . "<p><strong>Coverage:</strong> 13 regions of Uzbekistan + 3 countries.</p>"
                        . "<p><strong>Warranty:</strong> 3 years. Post-warranty service. Lead time 3–20 days. 600 m² showroom.</p>",
                ],
                'website_url' => 'https://shoshmebel.uz',
                'contact_phone' => '+998 99 856 05 55',
            ],

            // 15. TEXNO BALANCE — стулья, Ташкент
            [
                'slug' => 'texno-balance',
                'category' => 'production',
                'region' => 'tashkent_city',
                'is_published' => true,
                'show_on_home' => false,
                'sort' => 150,
                'founded_year' => 2010,
                'logo_text' => 'TEXNO',
                'name' => ['ru' => 'Texno Balance', 'uz' => 'Texno Balance', 'en' => 'Texno Balance'],
                'description' => [
                    'ru' => 'Фабрика стульев, приоритезирует комфорт и качество. Модели в люксовом стиле для любого интерьера. С 2010 года.',
                    'uz' => 'Stul fabrikasi, qulaylik va sifatga ustuvorlik beradi. Har qanday interyerga mos lyuks uslubdagi modellar. 2010-yildan.',
                    'en' => 'A chair factory that prioritizes comfort and quality. Luxury-style models fitting any interior. Since 2010.',
                ],
                'about' => [
                    'ru' => "<p><strong>Texno Balance</strong> (юр. OOO «TEXNO-BIO-BALLANS») — производство столов и стульев с 2010 года. 12 сотрудников, площадь 1 250 м².</p>"
                        . "<p><strong>Продукция:</strong> мягкая мебель, кухонная и офисная мебель, изделия на заказ. Люксовый стиль под любой интерьер.</p>"
                        . "<p><strong>Материалы:</strong> дерево бук, МДФ.</p>"
                        . "<p><strong>Мощность:</strong> 500–1 000 комплектов.</p>"
                        . "<p><strong>Гарантия:</strong> 3 года. Средний срок изготовления — 7 дней. Есть доставка и монтаж.</p>",
                    'uz' => "<p><strong>Texno Balance</strong> (yur. OOO “TEXNO-BIO-BALLANS”) — 2010-yildan stol va stul ishlab chiqarish. 12 xodim, 1 250 m² maydon.</p>"
                        . "<p><strong>Mahsulotlar:</strong> yumshoq mebel, oshxona va ofis mebeli, buyurtma asosida. Har qanday interyerga mos lyuks uslub.</p>"
                        . "<p><strong>Materiallar:</strong> buk yogʻochi, MDF.</p>"
                        . "<p><strong>Quvvat:</strong> 500–1 000 komplekt.</p>"
                        . "<p><strong>Kafolat:</strong> 3 yil. Oʻrtacha muddat — 7 kun. Yetkazib berish va montaj mavjud.</p>",
                    'en' => "<p><strong>Texno Balance</strong> (legally «TEXNO-BIO-BALLANS» LLC) — table and chair production since 2010. 12 staff, 1,250 m².</p>"
                        . "<p><strong>Products:</strong> upholstered, kitchen and office furniture, custom orders. Luxury style for any interior.</p>"
                        . "<p><strong>Materials:</strong> beech wood, MDF.</p>"
                        . "<p><strong>Capacity:</strong> 500–1,000 sets.</p>"
                        . "<p><strong>Warranty:</strong> 3 years. Lead time — 7 days. Delivery and installation available.</p>",
                ],
                'website_url' => 'https://techno-balance.uz',
                'contact_email' => 'aparvazova@gmail.com',
                'contact_phone' => '+998 90 909 05 64',
                'socials' => ['instagram' => 'techno_balance', 'telegram' => 'technobalance'],
            ],

            // 16. WELLWOOD — Ташкент, 2001
            [
                'slug' => 'wellwood',
                'category' => 'production',
                'region' => 'tashkent_city',
                'is_published' => true,
                'show_on_home' => false,
                'sort' => 160,
                'founded_year' => 2001,
                'logo_text' => 'WELL',
                'name' => ['ru' => 'Wellwood', 'uz' => 'Wellwood', 'en' => 'Wellwood'],
                'description' => [
                    'ru' => 'Мебельный производитель с 2001 года. Резидент MEYOS. Подробные данные уточняются.',
                    'uz' => '2001-yildan mebel ishlab chiqaruvchi. MEYOS rezidenti. Batafsil maʼlumotlar aniqlanmoqda.',
                    'en' => 'Furniture manufacturer since 2001. MEYOS resident. Full details to be confirmed.',
                ],
                'about' => null,
            ],

            // 17. KASH — Ташкент, Олмазор, 2009
            [
                'slug' => 'kash',
                'category' => 'production',
                'region' => 'tashkent_city',
                'is_published' => true,
                'show_on_home' => false,
                'sort' => 170,
                'founded_year' => 2009,
                'logo_text' => 'KASH',
                'name' => ['ru' => 'KASH', 'uz' => 'KASH', 'en' => 'KASH'],
                'description' => [
                    'ru' => 'Ташкент, Олмазор. Производство мебели с 2009 года. Резидент MEYOS. Подробные данные уточняются.',
                    'uz' => 'Toshkent, Olmazor. 2009-yildan mebel ishlab chiqarish. MEYOS rezidenti. Maʼlumotlar aniqlanmoqda.',
                    'en' => 'Tashkent, Olmazor. Furniture production since 2009. MEYOS resident. Details to be confirmed.',
                ],
                'about' => null,
            ],

            // 18. UNIQUE MEBEL — Ташкент, 2024
            [
                'slug' => 'unique-mebel',
                'category' => 'production',
                'region' => 'tashkent_city',
                'is_published' => true,
                'show_on_home' => false,
                'sort' => 180,
                'founded_year' => 2024,
                'logo_text' => 'UNIQUE',
                'name' => ['ru' => 'Unique Mebel', 'uz' => 'Unique Mebel', 'en' => 'Unique Mebel'],
                'description' => [
                    'ru' => 'Молодой производитель мебели, Ташкент. С 2024 года. Резидент MEYOS.',
                    'uz' => 'Yosh mebel ishlab chiqaruvchi, Toshkent. 2024-yildan. MEYOS rezidenti.',
                    'en' => 'Young furniture manufacturer, Tashkent. Since 2024. MEYOS resident.',
                ],
                'about' => null,
            ],
        ];

        foreach ($partners as $data) {
            Partner::create($data);
        }
    }
}
