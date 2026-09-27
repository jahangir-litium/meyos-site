<?php

namespace Database\Seeders;

use App\Models\TimelineItem;
use Illuminate\Database\Seeder;

/**
 * «Путь ассоциации» — 6 ключевых событий MEYOS, все с подтверждённым
 * источником. Специально не заполняем каждый год: показываем только
 * поворотные точки, чтобы блок оставался читаемым.
 *
 * Источники:
 *  - брошюра MEYOS 2026 (год основания, Tashkent Home Show, статистика)
 *  - spot.uz 30.12.2020 — конференция в Hyatt Regency Tashkent
 *  - Текстовый документ клиента — правовой статус кластера, ПП-5155 (21.06.2021)
 *  - lex.uz / gazeta.uz 26.05.2025 — постановление ПП-193
 *  - t.me/meyosassociation — делегации, международная экспансия
 */
class RealTimelineSeeder extends Seeder
{
    public function run(): void
    {
        TimelineItem::query()->forceDelete();

        $items = [
            [
                'year' => '2018',
                'sort' => 10,
                'is_highlight' => true,
                'is_published' => true,
                'title' => [
                    'ru' => 'Основание MEYOS',
                    'uz' => 'MEYOS tashkil etilishi',
                    'en' => 'MEYOS is founded',
                ],
                'description' => [
                    'ru' => 'Ведущие мебельные и деревообрабатывающие предприятия Узбекистана объединяются в отраслевое B2B-объединение, чтобы совместно решать вопросы сырья, кадров, экспорта и представлять интересы отрасли перед государственными органами.',
                    'uz' => 'Oʻzbekistonning yetakchi mebel va yogʻochsozlik korxonalari xomashyo, kadrlar, eksport masalalarini birgalikda hal etish va tarmoq manfaatlarini davlat organlari oldida ifodalash uchun tarmoq B2B birlashmasiga birlashadi.',
                    'en' => 'Uzbekistan\'s leading furniture and woodworking companies join a B2B industry alliance to jointly tackle raw materials, workforce and export issues and to represent the sector before government agencies.',
                ],
            ],
            [
                'year' => '2019',
                'sort' => 20,
                'is_highlight' => false,
                'is_published' => true,
                'title' => [
                    'ru' => 'Первая Tashkent Home Show',
                    'uz' => 'Birinchi Tashkent Home Show',
                    'en' => 'The first Tashkent Home Show',
                ],
                'description' => [
                    'ru' => 'Ассоциация запускает первую отраслевую выставку мебели, организованную самим бизнесом. Tashkent Home Show становится основной ежегодной площадкой отрасли, где резиденты выходят единым коллективным стендом.',
                    'uz' => 'Uyushma biznes tomonidan tashkil etilgan birinchi mebel koʻrgazmasini ishga tushiradi. Tashkent Home Show tarmoqning asosiy yillik uchrashuv maydoniga aylanadi — rezidentlar yagona jamoaviy stendda ishtirok etadi.',
                    'en' => 'The association launches the first furniture exhibition organized by industry professionals themselves. Tashkent Home Show becomes the sector\'s main annual meeting point where residents share a unified booth.',
                ],
            ],
            [
                'year' => '2020',
                'sort' => 30,
                'is_highlight' => false,
                'is_published' => true,
                'title' => [
                    'ru' => 'Годовая конференция в Hyatt Regency',
                    'uz' => 'Hyatt Regencyda yillik konferensiya',
                    'en' => 'Annual conference at Hyatt Regency',
                ],
                'description' => [
                    'ru' => '17 декабря 2020 года MEYOS собирает в Hyatt Regency Tashkent руководителей крупнейших мебельных и деревообрабатывающих компаний из всех регионов Узбекистана — подводит итоги года и формулирует направления развития отрасли (spot.uz).',
                    'uz' => '2020-yil 17-dekabrida MEYOS Hyatt Regency Tashkentda Oʻzbekistonning barcha viloyatlaridan yirik mebel va yogʻochsozlik kompaniyalari rahbarlarini yigʻadi — yil yakunlarini sarhisob qiladi va tarmoq rivojlanish yoʻnalishlarini shakllantiradi (spot.uz).',
                    'en' => 'On December 17, 2020, MEYOS convenes leaders of the largest furniture and woodworking companies from every region of Uzbekistan at Hyatt Regency Tashkent to review the year and set industry direction (spot.uz).',
                ],
            ],
            [
                'year' => '2021',
                'sort' => 40,
                'is_highlight' => true,
                'is_published' => true,
                'title' => [
                    'ru' => 'Правовой статус мебельных кластеров',
                    'uz' => 'Mebel klasterlarining huquqiy maqomi',
                    'en' => 'Legal status for furniture clusters',
                ],
                'description' => [
                    'ru' => 'Постановление ПП-5155 от 21.06.2021 закрепляет создание мебельных кластеров в форме малых промышленных зон (МПЗ). Резиденты получают освобождение от налогов на имущество, прибыль и ЕНП на 2 года, при экспорте ≥ 30% срок продлевается ещё на 2 года.',
                    'uz' => 'PQ-5155 (21.06.2021) mebel klasterlarini kichik sanoat zonalari (KSZ) shaklida shakllantirishni belgilaydi. Rezidentlar mulk, foyda va YaST soliqlaridan 2 yil ozod boʻladi; eksport ≥ 30% boʻlsa yana 2 yilga uzaytiriladi.',
                    'en' => 'Decree PP-5155 (21.06.2021) establishes furniture clusters as Small Industrial Zones (SIZ). Residents receive a 2-year exemption from property, profit and turnover taxes — extended by another 2 years for exports ≥ 30%.',
                ],
            ],
            [
                'year' => '2025',
                'sort' => 50,
                'is_highlight' => true,
                'is_published' => true,
                'title' => [
                    'ru' => 'ПП-193 — пакет мер поддержки отрасли',
                    'uz' => 'PQ-193 — tarmoqni qoʻllab-quvvatlash chora-tadbirlari',
                    'en' => 'PP-193 — industry support package',
                ],
                'description' => [
                    'ru' => 'Постановление Президента ПП-193 от 27.05.2025: до 01.01.2029 таможенная пошлина 1% на 14 видов сырья и фурнитуры, компенсация 60% на обучение кадров и 50% на международную сертификацию (ISO/CE/BIFMA/OEKO-Tex) и на цифровизацию — крупнейший пакет поддержки в новейшей истории мебельной индустрии Узбекистана (gazeta.uz).',
                    'uz' => 'Prezidentning 27.05.2025 kunidagi PQ-193 qarori: 01.01.2029 gacha 14 tur xomashyo va furnituraga bojxona boji 1%, kadrlarni tayyorlash uchun 60% va xalqaro sertifikatlash (ISO/CE/BIFMA/OEKO-Tex) hamda raqamlashtirish uchun 50% kompensatsiya — Oʻzbekiston mebel sanoatining zamonaviy tarixidagi eng katta qoʻllov paketi (gazeta.uz).',
                    'en' => 'Presidential Decree PP-193 of 27.05.2025: 1% customs duty on 14 types of raw materials and hardware until 01.01.2029, 60% compensation for staff training and 50% for international certification (ISO/CE/BIFMA/OEKO-Tex) and digitalization — the largest support package for the Uzbek furniture industry to date (gazeta.uz).',
                ],
            ],
            [
                'year' => '2026',
                'sort' => 60,
                'is_highlight' => true,
                'is_published' => true,
                'title' => [
                    'ru' => 'Международная экспансия и 500+ резидентов',
                    'uz' => 'Xalqaro ekspansiya va 500+ rezident',
                    'en' => 'International expansion and 500+ residents',
                ],
                'description' => [
                    'ru' => 'В брошюре ассоциации 2026 года — 500+ компаний-резидентов, экспорт в 12 стран (СНГ, Ближний Восток, Европа), средний рост выручки 38%. За сезон MEYOS формирует бизнес-миссии в Таджикистан (Худжанд, Душанбе) и Россию (Казань, Уфа, Самара), направляет в Кабмин 9 предложений по стимулированию отрасли.',
                    'uz' => '2026-yil brochurada — 500+ rezident kompaniyalar, 12 davlatga eksport (MDH, Yaqin Sharq, Yevropa), oʻrtacha daromad oʻsishi 38%. Mavsum davomida MEYOS Tojikistonga (Xoʻjand, Dushanbe) va Rossiyaga (Qozon, Ufa, Samara) biznes-missiyalarini shakllantiradi, Vazirlar Mahkamasiga tarmoqni qoʻllab-quvvatlash boʻyicha 9 taklif yuboradi.',
                    'en' => 'The 2026 brochure lists 500+ member companies, exports to 12 countries (CIS, Middle East, Europe) and 38% average revenue growth. Over the season MEYOS runs business missions to Tajikistan (Khujand, Dushanbe) and Russia (Kazan, Ufa, Samara), and submits 9 industry-support proposals to the Cabinet of Ministers.',
                ],
            ],
        ];

        foreach ($items as $it) {
            TimelineItem::create($it);
        }
    }
}
