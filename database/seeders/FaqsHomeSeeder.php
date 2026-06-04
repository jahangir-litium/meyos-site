<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqsHomeSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'q_ru' => 'Какой размер членского взноса в MEYOS?',
                'a_ru' => 'Размер взноса зависит от категории резидента и согласовывается индивидуально. Уточните при подаче заявки — менеджер пришлёт прайс в первый рабочий день.',
                'q_uz' => 'MEYOSning aʼzolik badali qancha?',
                'a_uz' => 'Badal miqdori rezident toifasiga bogʻliq va individual kelishiladi. Ariza topshirayotganda menejer prays-listni yuboradi.',
                'q_en' => 'What is the MEYOS membership fee?',
                'a_en' => 'The fee depends on the resident category and is agreed individually. Apply and a manager will send the price list within one business day.',
            ],
            [
                'q_ru' => 'Что включает резидентство?',
                'a_ru' => 'Налоговые льготы, защита интересов в гос. органах, доступ к программам экспорта и EduJob, льготная аренда офисных и складских помещений.',
                'q_uz' => 'Rezidentlikka nimalar kiradi?',
                'a_uz' => 'Soliq imtiyozlari, davlat organlarida manfaatlarni himoya qilish, eksport va EduJob dasturlariga kirish, ofis va omborlarning imtiyozli ijarasi.',
                'q_en' => 'What does residency include?',
                'a_en' => 'Tax benefits, government advocacy, access to export and EduJob programs, discounted office and warehouse leasing.',
            ],
            [
                'q_ru' => 'Сколько времени занимает получение статуса резидента?',
                'a_ru' => 'От 5 до 10 рабочих дней после подачи полного пакета документов. Подробный чек-лист отправит ваш менеджер.',
                'q_uz' => 'Rezident maqomini olish necha kun davom etadi?',
                'a_uz' => 'Toʻliq hujjatlar paketi topshirilgandan keyin 5–10 ish kuni. Batafsil roʻyxatni menejeringiz yuboradi.',
                'q_en' => 'How long does it take to obtain resident status?',
                'a_en' => '5–10 business days after submission of a complete document package. Your manager will send a detailed checklist.',
            ],
            [
                'q_ru' => 'Что такое EduJob?',
                'a_ru' => 'Совместная программа MEYOS с колледжами Узбекистана для подготовки сборщиков, обивщиков и дизайнеров мебели. Резиденты получают выпускников по льготным условиям.',
                'q_uz' => 'EduJob nima?',
                'a_uz' => 'MEYOSning Oʻzbekiston kollejlari bilan birgalikdagi dasturi — yigʻuvchi, qoplovchi va dizaynerlarni tayyorlash. Rezidentlar bitiruvchilarni imtiyozli shartlarda oladilar.',
                'q_en' => 'What is EduJob?',
                'a_en' => 'A joint MEYOS programme with Uzbek colleges to train furniture assemblers, upholsterers and designers. Residents get graduates on preferential terms.',
            ],
            [
                'q_ru' => 'Можно ли выйти из ассоциации?',
                'a_ru' => 'Да, можно подать заявление в любой момент. Действие членства прекращается с 1-го числа следующего месяца. Финансовых санкций за выход нет.',
                'q_uz' => 'Assotsiatsiyadan chiqish mumkinmi?',
                'a_uz' => 'Ha, istalgan vaqtda ariza topshirish mumkin. Aʼzolik keyingi oyning 1-sanasidan toʻxtatiladi. Chiqish uchun moliyaviy jarima yoʻq.',
                'q_en' => 'Can I leave the association?',
                'a_en' => 'Yes, you can submit a notice at any time. Membership ends on the 1st of the following month. There are no exit penalties.',
            ],
            [
                'q_ru' => 'Какие документы нужны для вступления?',
                'a_ru' => 'Свидетельство о регистрации, ИНН, копии паспорта руководителя, краткая презентация продукции. Полный список — в письме после подачи заявки.',
                'q_uz' => 'Aʼzo boʻlish uchun qanday hujjatlar kerak?',
                'a_uz' => 'Roʻyxatdan oʻtish guvohnomasi, INN, rahbar pasporti nusxasi, mahsulot prezentatsiyasi. Toʻliq roʻyxat ariza topshirgandan keyin yuboriladi.',
                'q_en' => 'What documents are needed to join?',
                'a_en' => 'Registration certificate, tax ID, director\'s passport copy, brief product presentation. The full list is sent by email after application.',
            ],
        ];

        foreach ($faqs as $i => $row) {
            Faq::updateOrCreate(
                ['page_slug' => 'home', 'sort' => $i + 1],
                [
                    'question' => ['ru' => $row['q_ru'], 'uz' => $row['q_uz'], 'en' => $row['q_en']],
                    'answer'   => ['ru' => $row['a_ru'], 'uz' => $row['a_uz'], 'en' => $row['a_en']],
                    'is_published' => true,
                ]
            );
        }
    }
}
