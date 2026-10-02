<?php

namespace Database\Seeders;

use App\Models\LegalAct;
use Illuminate\Database\Seeder;

/**
 * 3 ключевых нормативных акта мебельной отрасли Узбекистана
 * (расширяется клиентом через админку).
 */
class LegalActsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'slug' => 'pp-193-podderzhka-mebeli-2025',
                'act_number' => 'ПП-193',
                'act_date' => '2025-05-27',
                'status' => 'active',
                'category' => 'tariffs',
                'is_featured' => true,
                'sort' => 10,
                'source_url' => 'https://lex.uz/ru/docs/7546351',
                'title' => [
                    'ru' => 'О дополнительных мерах по созданию благоприятных условий для развития мебельной и деревообрабатывающей промышленности',
                    'uz' => 'Mebel va yogʻochsozlik sanoatini rivojlantirish uchun qulay sharoitlar yaratish boʻyicha qoʻshimcha chora-tadbirlar toʻgʻrisida',
                    'en' => 'On additional measures to create favorable conditions for the development of the furniture and woodworking industry',
                ],
                'summary' => [
                    'ru' => "Главное постановление отрасли, принято 27 мая 2025 года.\n\n• Таможенная пошлина 1% на 14 видов сырья и фурнитуры до 01.01.2029\n• Централизованный импорт древесины для отрасли\n• Компенсация 60% обучения, 50% международной сертификации, 50% цифровизации (до $5000)\n• Разрешено ИП нанимать до 5 работников для производства мебели",
                    'uz' => "Tarmoqning asosiy qarori, 2025-yil 27-mayda qabul qilingan.\n\n• 14 tur xomashyo va furnituraga 1% bojxona boji 01.01.2029 gacha\n• Yogʻochning markazlashtirilgan importi\n• Oʻqitish 60%, xalqaro sertifikatlash 50%, raqamlashtirish 50% ($5000 gacha) kompensatsiya\n• Yakka tartibdagi tadbirkorlarga 5 tagacha ishchi yollashga ruxsat",
                    'en' => "Flagship decree for the industry, adopted May 27, 2025.\n\n• 1% customs duty on 14 types of raw materials until 01.01.2029\n• Centralized wood import for the industry\n• 60% training, 50% international certification, 50% digitalization compensation (up to $5000)\n• Sole proprietors allowed to hire up to 5 workers for furniture production",
                ],
                'content' => [
                    'ru' => "<p><strong>Постановление Президента Республики Узбекистан № ПП-193</strong> «О дополнительных мерах по созданию благоприятных условий для развития мебельной и деревообрабатывающей промышленности» принято 27 мая 2025 года.</p>"
                        . "<h3>Ключевые меры:</h3>"
                        . "<ul>"
                        . "<li><strong>Таможенная пошлина 1%</strong> на 14 видов сырья, фурнитуры и аксессуаров, используемых в производстве мебели — до 1 января 2029 года</li>"
                        . "<li>Организован <strong>централизованный импорт древесины</strong> и деревоматериалов для мебельной отрасли</li>"
                        . "<li>Индивидуальным предпринимателям разрешено <strong>нанимать до 5 работников</strong> для производства мебели</li>"
                        . "<li><strong>Компенсация 60%</strong> расходов на краткосрочные курсы обучения персонала (с 2025 года)</li>"
                        . "<li><strong>Компенсация 50%</strong> расходов на международную сертификацию (ISO, CE, BIFMA, OEKO-Tex) — с 1 августа 2025 года</li>"
                        . "<li><strong>Компенсация 50%</strong> расходов на цифровизацию (CRM, ERP, PLM), максимум $5 000 — с 01.07.2025 по 01.06.2027</li>"
                        . "<li><strong>0 сум</strong> за экспертизу/сертификат на мебельную продукцию</li>"
                        . "<li><strong>Молодёжный фонд $50 млн</strong> для проектов в мебельной и деревообрабатывающей сфере</li>"
                        . "</ul>"
                        . "<h3>Целевые показатели до 2028 года:</h3>"
                        . "<ul>"
                        . "<li>Рост производства <strong>+30%</strong></li>"
                        . "<li>Экспорт <strong>$100 млн</strong> (с нынешних $20 млн)</li>"
                        . "<li>Инвестиции <strong>$200 млн</strong></li>"
                        . "<li>Занятость <strong>50 000 человек</strong></li>"
                        . "</ul>"
                        . "<p><em>Полный текст — на lex.uz по ссылке «Открыть на источнике».</em></p>",
                    'uz' => "<p><strong>Oʻzbekiston Respublikasi Prezidentining PQ-193-son qarori</strong> «Mebel va yogʻochsozlik sanoatini rivojlantirish uchun qulay sharoitlar yaratish boʻyicha qoʻshimcha chora-tadbirlar toʻgʻrisida» 2025-yil 27-mayda qabul qilingan.</p>"
                        . "<h3>Asosiy choralar:</h3>"
                        . "<ul>"
                        . "<li><strong>1% bojxona boji</strong> mebel ishlab chiqarishda qoʻllaniladigan 14 tur xomashyo va furnitura uchun — 2029-yil 1-yanvargacha</li>"
                        . "<li>Mebel sanoati uchun <strong>yogʻoch markazlashtirilgan importi</strong></li>"
                        . "<li>Yakka tartibdagi tadbirkorlarga mebel ishlab chiqarish uchun <strong>5 tagacha ishchi</strong> yollashga ruxsat</li>"
                        . "<li><strong>Oʻqitish uchun 60% kompensatsiya</strong></li>"
                        . "<li><strong>Xalqaro sertifikatlash uchun 50%</strong> (ISO, CE, BIFMA, OEKO-Tex) — 01.08.2025 dan</li>"
                        . "<li><strong>Raqamlashtirish uchun 50%</strong> (CRM, ERP, PLM), maksimum $5 000 — 01.07.2025–01.06.2027</li>"
                        . "<li>Yoshlar fondi <strong>$50 mln</strong></li>"
                        . "</ul>"
                        . "<p><strong>2028-yilgacha maqsadlar:</strong> ishlab chiqarish +30%, eksport $100 mln, investitsiyalar $200 mln, bandlik 50 000 kishi.</p>",
                    'en' => "<p><strong>Presidential Decree PP-193</strong> \"On additional measures to create favorable conditions for the development of the furniture and woodworking industry\" was signed on May 27, 2025.</p>"
                        . "<h3>Key measures:</h3>"
                        . "<ul>"
                        . "<li><strong>1% customs duty</strong> on 14 types of raw materials and hardware used in furniture production, until January 1, 2029</li>"
                        . "<li>Centralized wood import for the industry</li>"
                        . "<li>Sole proprietors may hire up to 5 workers for furniture production</li>"
                        . "<li><strong>60% training</strong> cost compensation</li>"
                        . "<li><strong>50% international certification</strong> compensation (ISO, CE, BIFMA, OEKO-Tex) from Aug 1, 2025</li>"
                        . "<li><strong>50% digitalization</strong> compensation (CRM, ERP, PLM), up to $5,000, from 01.07.2025 to 01.06.2027</li>"
                        . "<li><strong>$50M youth fund</strong> for furniture and woodworking projects</li>"
                        . "</ul>"
                        . "<p><strong>Targets by 2028:</strong> +30% production, $100M exports, $200M investment, 50,000 jobs.</p>",
                ],
            ],
            [
                'slug' => 'pp-5155-mebelnye-klastery-2021',
                'act_number' => 'ПП-5155',
                'act_date' => '2021-06-21',
                'status' => 'active',
                'category' => 'clusters',
                'is_featured' => true,
                'sort' => 20,
                'source_url' => null,
                'title' => [
                    'ru' => 'О мерах по дальнейшему развитию мебельной промышленности в регионах Республики',
                    'uz' => 'Respublikaning mintaqalarida mebel sanoatini yanada rivojlantirish chora-tadbirlari toʻgʻrisida',
                    'en' => 'On measures for further development of the furniture industry in the regions of the Republic',
                ],
                'summary' => [
                    'ru' => "Постановление от 21 июня 2021 года закрепило правовой статус мебельных кластеров как малых промышленных зон (МПЗ).\n\nРезиденты МПЗ получают:\n• Освобождение от налога на имущество, прибыль и ЕНП на 2 года\n• При экспорте ≥30% — продление ещё на 2 года\n• Минимальные инвестиции: 3000 × МРОТ",
                    'uz' => "2021-yil 21-iyundagi qaror mebel klasterlarining huquqiy maqomini kichik sanoat zonalari (KSZ) sifatida belgilaydi.\n\nKSZ rezidentlari:\n• Mulk, foyda va YaST soliqlaridan 2 yil ozod\n• Eksport ≥30% boʻlsa — yana 2 yilga uzaytirish\n• Minimal investitsiya: 3000 × EOTOʻ",
                    'en' => "Decree of June 21, 2021 established the legal status of furniture clusters as Small Industrial Zones (SIZ).\n\nSIZ residents receive:\n• 2-year exemption from property, profit and turnover taxes\n• With exports ≥30% — extended by another 2 years\n• Minimum investment: 3000 × minimum wage",
                ],
                'content' => [
                    'ru' => "<p><strong>Постановление Президента ПП-5155</strong> от 21 июня 2021 года закрепило создание <strong>мебельных кластеров в форме малых промышленных зон (МПЗ)</strong>.</p>"
                        . "<p>Согласно пункту 7 постановления ПП-2973, участники малых промышленных зон получают:</p>"
                        . "<ul>"
                        . "<li>Освобождение от <strong>налога на имущество</strong> на 2 года</li>"
                        . "<li>Освобождение от <strong>налога на прибыль</strong> на 2 года</li>"
                        . "<li>Освобождение от <strong>единого налогового платежа</strong> (упрощённая система) на 2 года</li>"
                        . "</ul>"
                        . "<p>Срок льгот исчисляется с даты принятия решения о размещении предприятия на территории МПЗ.</p>"
                        . "<p><strong>Условие:</strong> размер инвестиций должен составлять не менее <strong>3 000-кратного минимального размера оплаты труда</strong>.</p>"
                        . "<p><strong>Бонус для экспортёров:</strong> если участник МПЗ экспортирует не менее <strong>30% произведённой продукции</strong>, срок льгот <strong>продлевается ещё на 2 года</strong>.</p>"
                        . "<p>Таким образом, схема для мебельных кластеров:<br>Мебельный кластер → статус МПЗ → участник МПЗ → налоговые льготы.</p>",
                    'uz' => "<p><strong>Prezidentning PQ-5155-son qarori</strong> (2021-yil 21-iyun) <strong>mebel klasterlarini kichik sanoat zonalari (KSZ) shaklida</strong> yaratishni belgilaydi.</p>"
                        . "<p>PQ-2973 ning 7-bandiga koʻra, KSZ ishtirokchilari oladi:</p>"
                        . "<ul>"
                        . "<li><strong>Mulk soligʻi</strong>dan 2 yil ozodlik</li>"
                        . "<li><strong>Foyda soligʻi</strong>dan 2 yil ozodlik</li>"
                        . "<li><strong>Yagona soliq toʻlovi</strong>dan 2 yil ozodlik</li>"
                        . "</ul>"
                        . "<p><strong>Shart:</strong> investitsiya hajmi kamida <strong>3 000 × EOTOʻ</strong>.</p>"
                        . "<p>Eksport ≥30% boʻlsa — imtiyozlar <strong>yana 2 yilga uzaytiriladi</strong>.</p>",
                    'en' => "<p><strong>Presidential Decree PP-5155</strong> of June 21, 2021 established the creation of <strong>furniture clusters as Small Industrial Zones (SIZ)</strong>.</p>"
                        . "<p>Under PP-2973 §7, SIZ participants receive:</p>"
                        . "<ul>"
                        . "<li>2-year exemption from <strong>property tax</strong></li>"
                        . "<li>2-year exemption from <strong>profit tax</strong></li>"
                        . "<li>2-year exemption from <strong>unified tax payment</strong></li>"
                        . "</ul>"
                        . "<p><strong>Condition:</strong> investment must be at least <strong>3,000 × minimum wage</strong>.</p>"
                        . "<p>Exports ≥30% — benefits <strong>extended by another 2 years</strong>.</p>",
                ],
            ],
            [
                'slug' => 'cert-domestic-goszakaz-2025',
                'act_number' => null,
                'act_date' => '2025-10-01',
                'status' => 'active',
                'category' => 'certification',
                'is_featured' => false,
                'sort' => 30,
                'source_url' => null,
                'title' => [
                    'ru' => 'Сертификат отечественного производства — обязательное условие для госзакупок',
                    'uz' => 'Mahalliy ishlab chiqarish sertifikati — davlat xaridlari uchun majburiy shart',
                    'en' => 'Domestic production certificate — mandatory for government procurement',
                ],
                'summary' => [
                    'ru' => 'С 1 октября 2025 года мебельная продукция без сертификата отечественного производства не допускается на электронный кооперационный портал и «Национальный магазин» операторов госзакупок.',
                    'uz' => '2025-yil 1-oktabrdan mahalliy ishlab chiqarish sertifikatiga ega boʻlmagan mebel mahsulotlari elektron kooperatsiya portaliga va "Milliy doʻkon"ga joylashtirilmaydi.',
                    'en' => 'From October 1, 2025, furniture products without a domestic production certificate are not allowed on the electronic cooperation portal or "National Store" of government procurement operators.',
                ],
                'content' => [
                    'ru' => "<p>С <strong>1 октября 2025 года</strong> размещение мебельной продукции на <strong>электронном кооперационном портале</strong> и страницах <strong>«Национального магазина»</strong> операторов государственных закупок доступно только компаниям с <strong>сертификатом отечественного производства</strong>.</p>"
                        . "<p>Только такие субъекты бизнеса могут участвовать в тендерах на поставку мебели государственным учреждениям.</p>"
                        . "<p><strong>MEYOS помогает членам ассоциации с оформлением сертификата</strong> — подготовка документации и сопровождение процесса.</p>",
                    'uz' => "<p>2025-yil <strong>1-oktabrdan</strong> davlat xaridlari operatorlarining <strong>elektron kooperatsiya portali</strong> va <strong>«Milliy doʻkon»</strong> sahifalarida mebel mahsulotlarini joylashtirish faqat <strong>mahalliy ishlab chiqarish sertifikati</strong> boʻlgan kompaniyalar uchun mavjud.</p>"
                        . "<p>MEYOS aʼzolarga sertifikat rasmiylashtirishda yordam beradi.</p>",
                    'en' => "<p>From <strong>October 1, 2025</strong>, placing furniture products on the <strong>electronic cooperation portal</strong> and <strong>«National Store»</strong> pages of government procurement operators is only available to companies with a <strong>domestic production certificate</strong>.</p>"
                        . "<p>MEYOS assists members with certificate registration and documentation.</p>",
                ],
            ],
        ];

        foreach ($items as $data) {
            LegalAct::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
