<?php

namespace Database\Seeders;

use App\Models\Benefit;
use App\Models\JoinStep;
use App\Models\Setting;
use App\Models\TaxRow;
use Illuminate\Database\Seeder;

/**
 * Пересобирает публичные блоки главной страницы под фирменные тексты
 * из брошюры MEYOS 2026 и журнала «Меры поддержки мебельной и
 * деревообрабатывающей промышленности».
 *
 * Обновляет: Setting (hero, миссия), Benefits (6 официальных),
 * JoinStep (4 шага из брошюры), TaxRow (данные с ПП-193 и ПП-5155).
 */
class UpdateFromBrochureSeeder extends Seeder
{
    public function run(): void
    {
        // ── HERO главной + миссия ────────────────────────────────────────
        Setting::put('hero.default_h1', [
            'ru' => 'Ассоциация мебельной и деревообрабатывающей промышленности Узбекистана',
            'uz' => 'Oʻzbekiston mebel va yogʻochsozlik sanoati uyushmasi',
            'en' => 'Uzbekistan Furniture and Woodworking Industry Association',
        ], 'hero');

        Setting::put('hero.default_lead', [
            'ru' => 'Объединяем производителей, дизайнеров и поставщиков отрасли в единой экосистеме: налоговые преференции, доступ к B2B-заказам, кадры и выход на экспортные рынки.',
            'uz' => 'Ishlab chiqaruvchilar, dizaynerlar va yetkazib beruvchilarni yagona ekotizimda birlashtiramiz: soliq imtiyozlari, B2B buyurtmalarga kirish, kadrlar va eksport bozorlariga chiqish.',
            'en' => 'We unite manufacturers, designers and suppliers in a single ecosystem: tax benefits, B2B contracts, workforce and export access.',
        ], 'hero');

        Setting::put('meyos.mission_tag', [
            'ru' => 'Объединяем индустрию',
            'uz' => 'Sanoatni birlashtiramiz',
            'en' => 'Uniting the industry',
        ], 'meyos');

        // ── Статистика на главной (данные из брошюры) ───────────────────
        Setting::put('stats', [
            'companies' => '500+',
            'growth'    => '38%',
            'countries' => '12',
            'years'     => (string) (date('Y') - 2018), // от года основания
        ], 'stats');

        // ── 6 официальных преимуществ из брошюры ────────────────────────
        Benefit::query()->forceDelete();

        $benefits = [
            [
                'icon' => 'request_quote',
                'sort' => 10,
                'title' => [
                    'ru' => 'Налоговые и финансовые льготы',
                    'uz' => 'Soliq va moliyaviy imtiyozlar',
                    'en' => 'Tax and financial benefits',
                ],
                'description' => [
                    'ru' => 'Таможенный тариф на сырьё 1% (ПП-193 до 01.01.2029). Мебельные кластеры МПЗ — освобождение от налогов на имущество, прибыль и ЕНП на 2 года (ПП-2973). Компенсация 50% за международную сертификацию и 60% за обучение персонала.',
                    'uz' => 'Xomashyoga bojxona tarifi 1% (PQ-193, 01.01.2029 gacha). Mebel klasterlari (KSZ) — mulk, foyda va YaST soliqlaridan 2 yil ozod (PQ-2973). Xalqaro sertifikatsiya uchun 50% va xodimlarni oʻqitish uchun 60% kompensatsiya.',
                    'en' => 'Raw-material customs duty 1% (PP-193 until 01.01.2029). Furniture cluster (SIZ) status — 2-year exemption from property, profit and turnover taxes (PP-2973). 50% compensation for international certification, 60% for staff training.',
                ],
            ],
            [
                'icon' => 'hub',
                'sort' => 20,
                'title' => [
                    'ru' => 'B2B Marketplace',
                    'uz' => 'B2B Marketplace',
                    'en' => 'B2B Marketplace',
                ],
                'description' => [
                    'ru' => 'Проверенная база поставщиков и производителей, участие в тендерах и госзакупках, доступ к сертификату отечественного производства для «Национального магазина».',
                    'uz' => 'Tekshirilgan yetkazib beruvchilar va ishlab chiqaruvchilar bazasi, tenderlar va davlat xaridlariga kirish, “Milliy doʻkon” uchun mahalliy ishlab chiqarish sertifikatiga kirish.',
                    'en' => 'Verified supplier and manufacturer base, tender and public procurement access, domestic production certification for the National Store.',
                ],
            ],
            [
                'icon' => 'school',
                'sort' => 30,
                'title' => [
                    'ru' => 'EduJob — кадры',
                    'uz' => 'EduJob — kadrlar',
                    'en' => 'EduJob — workforce',
                ],
                'description' => [
                    'ru' => 'Обучающие программы для мастеров, технологов и менеджеров совместно с колледжами и вузами. Международные стажировки, отраслевая сертификация.',
                    'uz' => 'Ustalar, texnologlar va menejerlar uchun kollejlar va OTM bilan hamkorlikda oʻquv dasturlari. Xalqaro amaliyot, tarmoq sertifikatlashi.',
                    'en' => 'Training programs for craftsmen, technologists and managers in partnership with colleges and universities. International internships and industry certification.',
                ],
            ],
            [
                'icon' => 'flight_takeoff',
                'sort' => 40,
                'title' => [
                    'ru' => 'Поддержка экспорта',
                    'uz' => 'Eksportni qoʻllab-quvvatlash',
                    'en' => 'Export support',
                ],
                'description' => [
                    'ru' => 'Коллективные стенды на выставках, сертификация ISO/CE/BIFMA/OEKO-Tex, логистика, выход на рынки ЕС, СНГ, MENA и Юго-Восточной Азии под единым брендом MEYOS.',
                    'uz' => 'Koʻrgazmalardagi jamoaviy stendlar, ISO/CE/BIFMA/OEKO-Tex sertifikatlashi, logistika, MEYOS yagona brendi ostida Yevropa, MDH, MENA va Janubi-Sharqiy Osiyo bozorlariga chiqish.',
                    'en' => 'Collective exhibition stands, ISO/CE/BIFMA/OEKO-Tex certification, logistics, market entry in EU, CIS, MENA and SE Asia under the unified MEYOS brand.',
                ],
            ],
            [
                'icon' => 'shield',
                'sort' => 50,
                'title' => [
                    'ru' => 'Защита интересов отрасли',
                    'uz' => 'Manfaatlarni himoya qilish',
                    'en' => 'Industry advocacy',
                ],
                'description' => [
                    'ru' => 'Представительство в государственных органах, участие в формировании отраслевой политики, лоббирование интересов производителей на международных площадках.',
                    'uz' => 'Davlat organlarida vakillik, tarmoq siyosatini shakllantirishda ishtirok, xalqaro maydonlarda ishlab chiqaruvchilar manfaatlarini himoya qilish.',
                    'en' => 'Representation in state bodies, participation in industry policy shaping, lobbying manufacturers\' interests on international platforms.',
                ],
            ],
            [
                'icon' => 'language',
                'sort' => 60,
                'title' => [
                    'ru' => 'Цифровая экосистема',
                    'uz' => 'Raqamli ekotizim',
                    'en' => 'Digital ecosystem',
                ],
                'description' => [
                    'ru' => 'Производители, дизайнеры и поставщики на единой платформе. Компенсация 50% расходов на CRM, ERP, PLM (до $5 000) действует до июня 2027 года.',
                    'uz' => 'Ishlab chiqaruvchilar, dizaynerlar va yetkazib beruvchilar yagona platformada. 2027-yil iyungacha CRM, ERP, PLM xarajatlarining 50% (5 000 dollargacha) kompensatsiyasi.',
                    'en' => 'Manufacturers, designers and suppliers on one platform. 50% compensation for CRM, ERP and PLM costs (up to $5,000), effective until June 2027.',
                ],
            ],
        ];
        foreach ($benefits as $b) {
            Benefit::create(['is_published' => true] + $b);
        }

        // ── 4 шага вступления по брошюре ─────────────────────────────────
        JoinStep::query()->forceDelete();

        $steps = [
            [
                'sort' => 10,
                'title' => ['ru' => 'Заявка',       'uz' => 'Ariza',         'en' => 'Application'],
                'description' => [
                    'ru' => 'Оставьте заявку на сайте или напишите менеджеру. Кратко расскажите о компании, направлении и целях вступления.',
                    'uz' => 'Saytda ariza qoldiring yoki menejerga yozing. Kompaniya, yoʻnalish va aʼzo boʻlish maqsadlari haqida qisqacha yozing.',
                    'en' => 'Submit an application online or contact a manager. Briefly describe your company, area and goals.',
                ],
            ],
            [
                'sort' => 20,
                'title' => ['ru' => 'Документы',    'uz' => 'Hujjatlar',     'en' => 'Documents'],
                'description' => [
                    'ru' => 'Подготовьте учредительные документы, свидетельство о регистрации и данные о производстве или направлении деятельности.',
                    'uz' => 'Ta’sis hujjatlarini, roʻyxatga olish guvohnomasini va ishlab chiqarish yoki faoliyat yoʻnalishi haqidagi maʼlumotlarni tayyorlang.',
                    'en' => 'Prepare founding documents, registration certificate and information about your production or line of work.',
                ],
            ],
            [
                'sort' => 30,
                'title' => ['ru' => 'Рассмотрение', 'uz' => 'Koʻrib chiqish', 'en' => 'Review'],
                'description' => [
                    'ru' => 'Комитет ассоциации рассматривает заявку и документы. Средний срок — 5 рабочих дней. При необходимости запрашиваем уточнения.',
                    'uz' => 'Uyushma qoʻmitasi arizani va hujjatlarni koʻrib chiqadi. Oʻrtacha muddat — 5 ish kuni. Zarur boʻlsa aniqlashtirishlar soʻraymiz.',
                    'en' => 'The association committee reviews the application and documents. Average timeline — 5 business days.',
                ],
            ],
            [
                'sort' => 40,
                'title' => ['ru' => 'Резидентство', 'uz' => 'Rezidentlik',    'en' => 'Residency'],
                'description' => [
                    'ru' => 'Подписываем соглашение, компания получает статус резидента и полный доступ ко всем программам ассоциации.',
                    'uz' => 'Kelishuvni imzolaymiz, kompaniya rezident maqomini va uyushmaning barcha dasturlariga toʻliq kirishni oladi.',
                    'en' => 'We sign the agreement, the company receives resident status and full access to all association programs.',
                ],
            ],
        ];
        foreach ($steps as $s) {
            JoinStep::create(['is_published' => true] + $s);
        }

        // ── TaxRow: только реально подтверждённые меры (ПП-193, ПП-5155,
        //    ПП-2973). CIT 15→7,5 и соц.налог 25→12 УБРАНЫ — 7,5% CIT
        //    относится к IT-парку и спец-статусам, а 12% соц.налога — это
        //    стандартная ставка для не-бюджетных работодателей, поэтому
        //    показывать их как «льготу для мебельщиков» вводит в заблуждение.
        //    Столбец savings переименован в «Основание» (см. Setting
        //    home.tax_th_saving).
        TaxRow::query()->forceDelete();

        $tax = [
            [
                'sort' => 10,
                'parameter' => [
                    'ru' => 'Таможенная пошлина на сырьё и фурнитуру',
                    'uz' => 'Xomashyo va furnituraga bojxona tarifi',
                    'en' => 'Customs duty on raw materials and hardware',
                ],
                'standard_rate' => ['ru' => '10–15%', 'uz' => '10–15%', 'en' => '10–15%'],
                'resident_rate' => ['ru' => '1%', 'uz' => '1%', 'en' => '1%'],
                'savings' => ['ru' => 'ПП-193 до 01.01.2029', 'uz' => 'PQ-193, 01.01.2029 gacha', 'en' => 'PP-193, until 01.01.2029'],
            ],
            [
                'sort' => 20,
                'parameter' => [
                    'ru' => 'Централизованный импорт древесины',
                    'uz' => 'Yogʻoch markazlashtirilgan importi',
                    'en' => 'Centralized wood import',
                ],
                'standard_rate' => ['ru' => 'нет', 'uz' => 'yoʻq', 'en' => 'no'],
                'resident_rate' => ['ru' => 'да, для отрасли', 'uz' => 'ha, tarmoq uchun', 'en' => 'yes, industry-wide'],
                'savings' => ['ru' => 'ПП-193', 'uz' => 'PQ-193', 'en' => 'PP-193'],
            ],
            [
                'sort' => 30,
                'parameter' => [
                    'ru' => 'Мебельный кластер (МПЗ) — налог на имущество',
                    'uz' => 'Mebel klasteri (KSZ) — mulk soligʻi',
                    'en' => 'Furniture cluster (SIZ) — property tax',
                ],
                'standard_rate' => ['ru' => 'стандартно', 'uz' => 'standart', 'en' => 'standard'],
                'resident_rate' => ['ru' => 'освобождение на 2 года', 'uz' => '2 yil ozod', 'en' => '2-year exemption'],
                'savings' => ['ru' => 'ПП-5155 + ПП-2973', 'uz' => 'PQ-5155 + PQ-2973', 'en' => 'PP-5155 + PP-2973'],
            ],
            [
                'sort' => 40,
                'parameter' => [
                    'ru' => 'МПЗ — налог на прибыль / ЕНП',
                    'uz' => 'KSZ — foyda soligʻi / YaST',
                    'en' => 'SIZ — profit tax / simplified tax',
                ],
                'standard_rate' => ['ru' => 'стандартно', 'uz' => 'standart', 'en' => 'standard'],
                'resident_rate' => ['ru' => 'освобождение на 2 года', 'uz' => '2 yil ozod', 'en' => '2-year exemption'],
                'savings' => ['ru' => 'ПП-2973', 'uz' => 'PQ-2973', 'en' => 'PP-2973'],
            ],
            [
                'sort' => 50,
                'parameter' => [
                    'ru' => 'МПЗ — при экспорте ≥ 30%',
                    'uz' => 'KSZ — eksport ≥ 30% boʻlsa',
                    'en' => 'SIZ — with exports ≥ 30%',
                ],
                'standard_rate' => ['ru' => '2 года льгот', 'uz' => '2 yil imtiyoz', 'en' => '2-year benefits'],
                'resident_rate' => ['ru' => 'продление ещё на 2 года', 'uz' => 'yana 2 yilga uzaytirish', 'en' => 'extended by 2 more years'],
                'savings' => ['ru' => 'ПП-2973', 'uz' => 'PQ-2973', 'en' => 'PP-2973'],
            ],
            [
                'sort' => 60,
                'parameter' => [
                    'ru' => 'Компенсация международной сертификации (ISO, CE, BIFMA, OEKO-Tex)',
                    'uz' => 'Xalqaro sertifikatlash uchun kompensatsiya (ISO, CE, BIFMA, OEKO-Tex)',
                    'en' => 'International certification compensation (ISO, CE, BIFMA, OEKO-Tex)',
                ],
                'standard_rate' => ['ru' => '100% за счёт бизнеса', 'uz' => '100% biznes hisobidan', 'en' => '100% at business cost'],
                'resident_rate' => ['ru' => '50% возврат', 'uz' => '50% qaytarish', 'en' => '50% reimbursement'],
                'savings' => ['ru' => 'ПП-193 (с 01.08.2025)', 'uz' => 'PQ-193 (01.08.2025 dan)', 'en' => 'PP-193 (from 01.08.2025)'],
            ],
            [
                'sort' => 70,
                'parameter' => [
                    'ru' => 'Компенсация обучения и переподготовки',
                    'uz' => 'Oʻquv va qayta tayyorlash kompensatsiyasi',
                    'en' => 'Training and re-skilling compensation',
                ],
                'standard_rate' => ['ru' => '100% за счёт бизнеса', 'uz' => '100% biznes hisobidan', 'en' => '100% at business cost'],
                'resident_rate' => ['ru' => '60% возврат', 'uz' => '60% qaytarish', 'en' => '60% reimbursement'],
                'savings' => ['ru' => 'ПП-193 (с 2025)', 'uz' => 'PQ-193 (2025 dan)', 'en' => 'PP-193 (since 2025)'],
            ],
            [
                'sort' => 80,
                'parameter' => [
                    'ru' => 'Цифровизация (CRM, ERP, PLM)',
                    'uz' => 'Raqamlashtirish (CRM, ERP, PLM)',
                    'en' => 'Digitalization (CRM, ERP, PLM)',
                ],
                'standard_rate' => ['ru' => '100% за счёт бизнеса', 'uz' => '100% biznes hisobidan', 'en' => '100% at business cost'],
                'resident_rate' => ['ru' => '50%, максимум $5 000', 'uz' => '50%, maksimum $5 000', 'en' => '50%, up to $5,000'],
                'savings' => ['ru' => 'ПП-193 (01.07.2025–01.06.2027)', 'uz' => 'PQ-193 (01.07.2025–01.06.2027)', 'en' => 'PP-193 (01.07.2025–01.06.2027)'],
            ],
        ];
        foreach ($tax as $t) {
            TaxRow::create(['is_published' => true] + $t);
        }
    }
}
