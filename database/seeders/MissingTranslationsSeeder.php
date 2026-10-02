<?php

namespace Database\Seeders;

use App\Models\BusinessCase;
use App\Models\PainSolutionRow;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;

/**
 * Доливает UZ/EN переводы для записей, где они равны RU (признак — MeyosContentSeeder
 * положил только RU, а Spatie скопировал его в uz/en).
 *
 * Идемпотентно: обновляет только те поля, где uz=ru (или en=ru).
 * Если админ уже отредактировал через UI — его значения сохранятся.
 */
class MissingTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->painSolutions();
        $this->businessCases();
        $this->teamRoles();
    }

    /** 4 строки «Проблема → Решение» (главная страница). */
    private function painSolutions(): void
    {
        $data = [
            // [ru_title] => [uz, en]  — ключ по RU (уникальный маркер) чтобы не зависеть от id
            'pain_titles' => [
                'Переплата налогов'       => ['Soliqlarni ortiqcha toʻlash',    'Overpaying taxes'],
                'Отсутствие новых заказов' => ['Yangi buyurtmalarning yoʻqligi', 'Lack of new orders'],
                'Разрыв с государством'   => ['Davlat bilan aloqa uzilgan',     'Disconnect from the government'],
                'Кадровый голод'          => ['Kadrlar tanqisligi',             'Talent shortage'],
            ],
            'pain_descriptions' => [
                'Мебельный бизнес не использует доступные льготы, теряя до 15% прибыли ежегодно.'
                    => [
                        'Mebel biznesi mavjud imtiyozlardan foydalanmaydi va har yili foydaning 15% gacha yoʻqotadi.',
                        'The furniture business doesn\'t use available benefits, losing up to 15% of profit yearly.',
                    ],
                'Нет выхода на застройщиков, HoReCa и экспортные рынки — конкуренция только на низких ценах.'
                    => [
                        'Qurilish kompaniyalari, HoReCa va eksport bozorlariga chiqa olmayapsiz — raqobat faqat past narxlarda.',
                        'No access to developers, HoReCa or export markets — competition only on low prices.',
                    ],
                'Невозможно донести позицию отрасли до регуляторов; законы принимаются без учёта специфики мебельного рынка.'
                    => [
                        'Tarmoq pozitsiyasini tartibga soluvchilarga yetkazib boʻlmaydi; qonunlar mebel bozorining xususiyatlarini hisobga olmay qabul qilinadi.',
                        'The industry\'s position can\'t reach regulators; laws are adopted without accounting for furniture-market specifics.',
                    ],
                'Нет квалифицированных мастеров, технологов и дизайнеров — обучение ложится на бизнес в одиночку.'
                    => [
                        'Malakali ustalar, texnologlar va dizaynerlar yoʻq — oʻqitish yukini biznes yolgʻiz koʻtaradi.',
                        'No qualified craftsmen, technologists or designers — training burden falls on business alone.',
                    ],
            ],
            'solution_titles' => [
                'Налоговые льготы для мебельных кластеров' => ['Mebel klasterlari uchun soliq imtiyozlari', 'Tax benefits for furniture clusters'],
                'Налоговые льготы по статусу'              => ['Status boʻyicha soliq imtiyozlari', 'Status-based tax benefits'],
                'Поток B2B-заказов'                         => ['B2B buyurtmalar oqimi', 'A stream of B2B orders'],
                'Голос индустрии'                           => ['Sanoatning ovozi', 'Voice of the industry'],
                'Кадры через EduJob'                        => ['EduJob orqali kadrlar', 'Talent via EduJob'],
            ],
            'solution_descriptions' => [
                'Статус мебельного кластера (МПЗ) даёт освобождение от налога на имущество, прибыль и ЕНП на 2 года. Пошлина на сырьё 1% (ПП-193 до 01.01.2029), компенсация до 50% на международную сертификацию.'
                    => [
                        'Mebel klasteri (KSZ) maqomi mulk, foyda va YaST soliqlaridan 2 yil ozod qiladi. Xomashyoga boj 1% (PQ-193, 01.01.2029 gacha), xalqaro sertifikatsiya uchun 50% gacha kompensatsiya.',
                        'Furniture cluster (SIZ) status gives 2-year exemption from property, profit and turnover taxes. 1% raw-material duty (PP-193 until 01.01.2029), up to 50% compensation for international certification.',
                    ],
                'CIT 7,5%, SSC 12%, нулевой импортный тариф — экономия до 22% от фонда оплаты и себестоимости.'
                    => [
                        'CIT 7,5%, SSC 12%, nol import tarifi — mehnat haqi fondi va tannarxdan 22% gacha tejamkorlik.',
                        'CIT 7.5%, SSC 12%, zero import tariff — up to 22% savings on payroll and cost base.',
                    ],
                'База партнёров, тендеры, госзакупки, экспорт под единым брендом MEYOS.'
                    => [
                        'Hamkorlar bazasi, tenderlar, davlat xaridlari, eksport yagona MEYOS brendi ostida.',
                        'Partner base, tenders, government procurement, exports under the single MEYOS brand.',
                    ],
                'Прямой диалог с министерствами, участие в законотворчестве, лоббирование интересов отрасли.'
                    => [
                        'Vazirliklar bilan toʻgʻridan-toʻgʻri muloqot, qonunchilikda ishtirok, tarmoq manfaatlarini himoya qilish.',
                        'Direct dialogue with ministries, participation in lawmaking, lobbying for industry interests.',
                    ],
                'Подготовка мастеров и управленцев, сертификация, стажировки у партнёров в ЕС и ОАЭ.'
                    => [
                        'Ustalar va menejerlarni tayyorlash, sertifikatsiya, EI va BAAdagi hamkorlarda stajirovka.',
                        'Training craftsmen and managers, certification, internships at EU and UAE partners.',
                    ],
            ],
        ];

        $fieldToDict = [
            'pain_title'           => $data['pain_titles'],
            'pain_description'     => $data['pain_descriptions'],
            'solution_title'       => $data['solution_titles'],
            'solution_description' => $data['solution_descriptions'],
        ];

        foreach (PainSolutionRow::all() as $row) {
            foreach ($fieldToDict as $field => $dict) {
                $ru = $row->getTranslation($field, 'ru', false);
                if (!$ru || !isset($dict[$ru])) continue;
                [$uz, $en] = $dict[$ru];

                // Обновляем uz/en ТОЛЬКО если они равны ru (т.е. ещё не отредактированы в админке)
                if ($row->getTranslation($field, 'uz', false) === $ru) {
                    $row->setTranslation($field, 'uz', $uz);
                }
                if ($row->getTranslation($field, 'en', false) === $ru) {
                    $row->setTranslation($field, 'en', $en);
                }
            }
            $row->save();
        }
    }

    /** 3 бизнес-кейса. */
    private function businessCases(): void
    {
        $dict = [
            'Фабрика корпусной мебели: выход на экспорт в Казахстан и ОАЭ' => [
                'uz' => 'Korpusli mebel fabrikasi: Qozogʻiston va BAAga eksportga chiqish',
                'en' => 'Case furniture factory: breaking into exports to Kazakhstan and the UAE',
            ],
            'Студия авторской мебели: контракт с национальным застройщиком' => [
                'uz' => 'Mualliflik mebel studiyasi: milliy quruvchi bilan shartnoma',
                'en' => 'Designer furniture studio: contract with a national developer',
            ],
            'Семейная мастерская: из цеха в сеть из 4 производств' => [
                'uz' => 'Oilaviy ustaxona: bitta sexdan 4 ishlab chiqarish tarmogʻigacha',
                'en' => 'Family workshop: from one shop to a network of four plants',
            ],
        ];
        $descDict = [
            'После вступления в ассоциацию компания получила статус резидента, сертификацию по международным стандартам и участие в двух коллективных экспортных миссиях MEYOS. За 14 месяцев доля экспорта в выручке выросла с 4% до 31%.' => [
                'uz' => 'Assotsiatsiyaga aʼzo boʻlgach, kompaniya rezident maqomini, xalqaro standartlar boʻyicha sertifikatsiyani va MEYOSning ikki jamoaviy eksport missiyasida ishtirokni oldi. 14 oyda eksport ulushi 4% dan 31% gacha oʻsdi.',
                'en' => 'After joining the association, the company gained resident status, international certification, and participation in two MEYOS collective export missions. In 14 months, the export share of revenue grew from 4% to 31%.',
            ],
            'Через B2B-базу MEYOS студия вышла на подрядчика крупного жилого комплекса в Ташкенте. Ассоциация сопроводила сделку, согласовала технические условия и помогла с сертификатом качества. Итог — долгосрочный контракт на 18 месяцев.' => [
                'uz' => 'MEYOSning B2B bazasi orqali studiya Toshkentdagi yirik turar-joy majmuasi pudratchisiga chiqdi. Assotsiatsiya bitimni kuzatib bordi, texnik shartlarni kelishtirdi va sifat sertifikati bilan yordam berdi. Natija — 18 oylik uzoq muddatli shartnoma.',
                'en' => 'Through the MEYOS B2B network, the studio reached the contractor of a major residential complex in Tashkent. The association guided the deal, aligned the technical specs and helped obtain a quality certificate. Result — an 18-month long-term contract.',
            ],
            'Участие в программе EduJob позволило обучить 14 мастеров и 3 технологов. Ассоциация подключила мастерскую к партнёрской логистике и оптовой закупке фурнитуры. За два года бизнес вырос до сети из 4 производств.' => [
                'uz' => 'EduJob dasturida ishtirok 14 ustani va 3 texnologni tayyorlashga imkon berdi. Assotsiatsiya ustaxonani hamkorlar logistikasiga va furnitura ulgurji xaridiga ulab berdi. Ikki yilda biznes 4 ishlab chiqarish tarmogʻigacha oʻsdi.',
                'en' => 'Participation in EduJob trained 14 craftsmen and 3 technologists. The association connected the workshop to partner logistics and bulk hardware purchasing. Over two years the business grew into a network of four plants.',
            ],
        ];

        foreach (BusinessCase::all() as $row) {
            $ruT = $row->getTranslation('title', 'ru', false);
            if ($ruT && isset($dict[$ruT])) {
                if ($row->getTranslation('title', 'uz', false) === $ruT) $row->setTranslation('title', 'uz', $dict[$ruT]['uz']);
                if ($row->getTranslation('title', 'en', false) === $ruT) $row->setTranslation('title', 'en', $dict[$ruT]['en']);
            }
            $ruD = $row->getTranslation('description', 'ru', false);
            if ($ruD && isset($descDict[$ruD])) {
                if ($row->getTranslation('description', 'uz', false) === $ruD) $row->setTranslation('description', 'uz', $descDict[$ruD]['uz']);
                if ($row->getTranslation('description', 'en', false) === $ruD) $row->setTranslation('description', 'en', $descDict[$ruD]['en']);
            }
            $row->save();
        }
    }

    /** Должности в команде (name оставляем — имена не переводятся). */
    private function teamRoles(): void
    {
        $dict = [
            'Председатель совета'       => ['uz' => 'Kengash raisi',             'en' => 'Chairman of the board'],
            'Исполнительный директор'   => ['uz' => 'Ijrochi direktor',          'en' => 'Executive director'],
            'Директор по экспорту'      => ['uz' => 'Eksport boʻyicha direktor', 'en' => 'Director of exports'],
            'Руководитель EduJob'       => ['uz' => 'EduJob rahbari',            'en' => 'Head of EduJob'],
        ];
        foreach (TeamMember::all() as $row) {
            $ru = $row->getTranslation('role', 'ru', false);
            if ($ru && isset($dict[$ru])) {
                if ($row->getTranslation('role', 'uz', false) === $ru) $row->setTranslation('role', 'uz', $dict[$ru]['uz']);
                if ($row->getTranslation('role', 'en', false) === $ru) $row->setTranslation('role', 'en', $dict[$ru]['en']);
                $row->save();
            }
        }
    }
}
