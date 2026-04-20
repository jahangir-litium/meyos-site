/* MEYOS — Автоматический текстовый словарь.
   Ключ = русский текст, значения = UZ / EN.
   Применяется text-node walker к body, все совпадения заменяются.
   Узлы внутри <script>, <style>, .theme-switcher, .modal и с атрибутом data-i18n пропускаются
   (у них свой перевод). */

window.MEYOS_AUTO = (() => {
  const T = (uz, en) => ({ uz, en });
  const D = {
    /* Copyright / footer bottom */
    '© 2026 MEYOS': T('© 2026 MEYOS', '© 2026 MEYOS'),
    '© 2026 MEYOS · Ассоциация мебельщиков Узбекистана': T('© 2026 MEYOS · Oʻzbekiston mebelsozlari assotsiatsiyasi', '© 2026 MEYOS · Uzbekistan Furniture Association'),
    'Политика конфиденциальности': T('Maxfiylik siyosati', 'Privacy Policy'),
    'Политика конфиденциальности · Публичная оферта': T('Maxfiylik siyosati · Ommaviy oferta', 'Privacy Policy · Public Offer'),
    'Ассоциация мебельщиков Узбекистана.': T('Oʻzbekiston mebelsozlari assotsiatsiyasi.', 'Uzbekistan Furniture Association.'),
    'Ассоциация мебельщиков Узбекистана. IT и стратегический партнёр мебельного бизнеса.': T('Oʻzbekiston mebelsozlari assotsiatsiyasi. Mebel biznesining IT va strategik hamkori.', 'Uzbekistan Furniture Association. IT and strategic partner for the furniture business.'),
    'Разделы': T('Boʻlimlar', 'Sections'),
    'Активности': T('Faolliklar', 'Activities'),
    'Контакты': T('Aloqa', 'Contacts'),
    'Ташкент, ул. Буюк Ипак Йули, 12': T('Toshkent, Buyuk Ipak Yoʻli koʻchasi, 12', 'Tashkent, Buyuk Ipak Yuli str., 12'),

    /* Page titles */
    'Контакты — MEYOS | Связаться с ассоциацией мебельщиков Узбекистана': T('Kontaktlar — MEYOS | Oʻzbekiston mebelsozlari assotsiatsiyasi bilan bogʻlanish', 'Contacts — MEYOS | Contact the Uzbekistan Furniture Association'),
    'Мероприятия — MEYOS | Форумы, выставки, экспортные миссии мебельной отрасли': T('Tadbirlar — MEYOS | Mebel sohasining forumlari, koʻrgazmalari, eksport missiyalari', 'Events — MEYOS | Furniture industry forums, exhibitions, export missions'),
    'Новости — MEYOS | События мебельной отрасли Узбекистана': T('Yangiliklar — MEYOS | Oʻzbekiston mebel sohasining voqealari', 'News — MEYOS | Events of Uzbekistan\'s furniture industry'),
    'О компании — MEYOS | История, миссия, команда ассоциации мебельщиков': T('Kompaniya haqida — MEYOS | Mebelsozlar assotsiatsiyasining tarixi, missiyasi, jamoasi', 'About — MEYOS | History, mission, team of the furniture association'),
    'Партнёры и резиденты — MEYOS | База мебельных компаний Узбекистана': T('Hamkorlar va rezidentlar — MEYOS | Oʻzbekiston mebel kompaniyalari bazasi', 'Partners and residents — MEYOS | Directory of Uzbekistan\'s furniture companies'),
    'Программы и EduJob — MEYOS | Обучение и развитие мебельного бизнеса': T('Dasturlar va EduJob — MEYOS | Mebel biznesini oʻqitish va rivojlantirish', 'Programs and EduJob — MEYOS | Furniture business education and growth'),
    'Резидентство и льготы — MEYOS | Налоговые преференции для мебельного бизнеса': T('Rezidentlik va imtiyozlar — MEYOS | Mebel biznesi uchun soliq imtiyozlari', 'Residency and benefits — MEYOS | Tax preferences for the furniture business'),

    /* Metrics prefixes / short */
    '×2,4': T('×2,4', '×2.4'),
    'Контрактов за квартал': T('Choraklik shartnomalar', 'Quarterly contracts'),
    'Доля рынка страны': T('Mamlakat bozorining ulushi', 'Country market share'),
    'Доля экспорта': T('Eksport ulushi', 'Export share'),
    'Рост выручки': T('Daromad oʻsishi', 'Revenue growth'),
    'Налоговая нагрузка': T('Soliq yuki', 'Tax burden'),
    'Объём контракта': T('Shartnoma hajmi', 'Contract value'),
    'Новых сотрудников': T('Yangi xodimlar', 'New employees'),
    'Новых линий мебели': T('Yangi mebel liniyalari', 'New furniture lines'),
    'Масштаб производства': T('Ishlab chiqarish miqyosi', 'Production scale'),
    'Обученных мастеров': T('Oʻqitilgan ustalar', 'Trained masters'),
    'Себестоимость': T('Tannarx', 'Cost of goods'),
    'Объём экспортных контрактов по итогам миссий': T('Missiyalar yakunidagi eksport shartnomalari hajmi', 'Volume of export contracts from missions'),
    'Сертифицированных специалистов EduJob': T('EduJob sertifikatlangan mutaxassislar', 'EduJob certified specialists'),
    'Средняя экономия на сырье через коллективные закупки': T('Jamoaviy xaridlar orqali xomashyoda oʻrtacha tejash', 'Average raw-material savings via collective procurement'),
    'Соглашений с ведомствами': T('Idoralar bilan shartnomalar', 'Agreements with agencies'),
    'Национальных программ': T('Milliy dasturlar', 'National programs'),
    'Резидентов': T('Rezidentlar', 'Residents'),
    'Средний рост выручки': T('Oʻrtacha daromad oʻsishi', 'Average revenue growth'),
    'Стран экспорта': T('Eksport davlatlari', 'Export countries'),
    'Лет на рынке': T('Bozorda yil', 'Years on the market'),

    /* Team names & roles */
    'Акмаль Каримов': T('Akmal Karimov', 'Akmal Karimov'),
    'Дилором Усманова': T('Dilorom Usmanova', 'Dilorom Usmanova'),
    'Руслан Сафаров': T('Ruslan Safarov', 'Ruslan Safarov'),
    'Нигора Тураева': T('Nigora Turaeva', 'Nigora Turaeva'),
    'Председатель совета': T('Kengash raisi', 'Chairman of the Board'),
    'Исполнительный директор': T('Ijrochi direktor', 'Executive Director'),
    'Директор по экспорту': T('Eksport direktori', 'Director of Export'),
    'Руководитель EduJob': T('EduJob rahbari', 'Head of EduJob'),
    'АК': T('AK', 'AK'), 'ДУ': T('DU', 'DU'), 'РС': T('RS', 'RS'), 'НТ': T('NT', 'NT'),
    'Совет директоров': T('Direktorlar kengashi', 'Board of Directors'),

    /* Navigation / short labels */
    'Вступить': T('Aʼzo boʻlish', 'Join'),
    'Вступить в ассоциацию': T('Assotsiatsiyaga aʼzo boʻlish', 'Join the Association'),
    'Стать резидентом': T('Rezident boʻlish', 'Become a Resident'),
    'Оставить заявку': T('Ariza qoldirish', 'Leave a request'),
    'Отправить заявку': T('Arizani yuborish', 'Send Application'),
    'Отправить': T('Yuborish', 'Send'),
    'Подать заявку': T('Ariza yuborish', 'Apply'),
    'Подать заявку на участие': T('Ishtirok uchun ariza yuborish', 'Apply to participate'),
    'Зарегистрироваться': T('Roʻyxatdan oʻtish', 'Register'),
    'Записаться': T('Yozilish', 'Sign up'),
    'Записаться на курс': T('Kursga yozilish', 'Enroll in course'),
    'Подробнее': T('Batafsil', 'Learn more'),
    'Связаться': T('Bogʻlanish', 'Contact'),
    'Связаться с нами': T('Biz bilan bogʻlaning', 'Contact us'),
    'Задать вопрос': T('Savol berish', 'Ask a question'),
    'Загрузить ещё': T('Yana yuklash', 'Load more'),
    'Весь список': T('Butun roʻyxat', 'Full list'),
    'Смотреть всех партнёров': T('Barcha hamkorlarni koʻrish', 'View all partners'),
    'Все новости': T('Barcha yangiliklar', 'All news'),
    'Все мероприятия': T('Barcha tadbirlar', 'All events'),
    'Все программы': T('Barcha dasturlar', 'All programs'),
    'Все преимущества': T('Barcha imtiyozlar', 'All benefits'),
    'Все условия резидентства': T('Rezidentlikning barcha shartlari', 'All residency terms'),
    'Программа форума': T('Forum dasturi', 'Forum program'),
    'Читать →': T('Oʻqish →', 'Read →'),
    'Купить билет': T('Chipta sotib olish', 'Buy ticket'),

    /* Nav items */
    'О компании': T('Kompaniya haqida', 'About'),
    'Резидентство': T('Rezidentlik', 'Residency'),
    'Резидентство и льготы': T('Rezidentlik va imtiyozlar', 'Residency & Benefits'),
    'Программы': T('Dasturlar', 'Programs'),
    'Партнёры': T('Hamkorlar', 'Partners'),
    'Мероприятия': T('Tadbirlar', 'Events'),
    'Новости': T('Yangiliklar', 'News'),
    'Партнёрство / медиа': T('Hamkorlik / media', 'Partnership / media'),

    /* Categories (chips and filters) */
    'Резидентство_cat': T('Rezidentlik', 'Residency'),
    'Экспорт': T('Eksport', 'Export'),
    'Регулирование': T('Tartibga solish', 'Regulation'),
    'Производство': T('Ishlab chiqarish', 'Manufacturing'),
    'Дизайн': T('Dizayn', 'Design'),
    'Материалы': T('Materiallar', 'Materials'),
    'Логистика': T('Logistika', 'Logistics'),
    'Государственный': T('Davlat', 'Government'),
    'Государственные': T('Davlat', 'Government'),
    'Финансы': T('Moliya', 'Finance'),
    'Производители': T('Ishlab chiqaruvchilar', 'Manufacturers'),
    'Дизайн-студии': T('Dizayn-studiyalar', 'Design studios'),
    'Поставщики материалов': T('Materiallar taʼminotchilari', 'Material suppliers'),

    /* Events & locations */
    'Главное событие года': T('Yilning asosiy voqeasi', 'Main event of the year'),
    'Стратегия мебельной отрасли 2026–2030': T('Mebel sohasi strategiyasi 2026–2030', 'Furniture industry strategy 2026–2030'),
    'Ежегодный отраслевой форум: стратегия развития, встречи с регуляторами, презентация программы на год.': T('Yillik tarmoq forumi: rivojlanish strategiyasi, regulyatorlar bilan uchrashuvlar, yil uchun dastur taqdimoti.', 'Annual industry forum: development strategy, meetings with regulators, yearly program presentation.'),
    'Афиша MEYOS': T('MEYOS afishasi', 'MEYOS calendar'),
    'Афиша на ближайшие месяцы': T('Yaqinlashayotgan oylar afishasi', 'Calendar for upcoming months'),
    'Ближайшие мероприятия': T('Yaqinlashayotgan tadbirlar', 'Upcoming events'),
    'Регистрация': T('Roʻyxatdan oʻtish', 'Registration'),
    'Регистрация резидентов открывается за 30 дней, для остальных участников — за 14 дней до начала.': T('Rezidentlarning roʻyxatdan oʻtishi 30 kun oldin, boshqa ishtirokchilar uchun — 14 kun oldin ochiladi.', 'Resident registration opens 30 days in advance, other participants — 14 days before start.'),
    'Экспортная миссия': T('Eksport missiyasi', 'Export mission'),
    'Экспортная миссия в ОАЭ': T('BAAga eksport missiyasi', 'Export mission to UAE'),
    'Коллективный стенд MEYOS на Index Dubai — прямые встречи с закупщиками HoReCa и застройщиками.': T('Index Dubai koʻrgazmasida MEYOS jamoaviy stendi — HoReCa xaridorlari va quruvchilar bilan toʻgʻridan-toʻgʻri uchrashuvlar.', 'MEYOS collective stand at Index Dubai — direct meetings with HoReCa buyers and developers.'),
    'Крупнейшая интерьерная выставка региона. 15 резидентов MEYOS — встречи с закупщиками HoReCa и застройщиками ОАЭ.': T('Mintaqaning eng yirik interyer koʻrgazmasi. 15 MEYOS rezidenti — HoReCa xaridorlari va BAA quruvchilari bilan uchrashuvlar.', 'The region\'s largest interior exhibition. 15 MEYOS residents — meetings with HoReCa buyers and UAE developers.'),
    'Открытый день образовательной программы: демо-курсы для мастеров, технологов и руководителей производств.': T('Taʼlim dasturining ochiq kuni: ustalar, texnologlar va ishlab chiqarish rahbarlari uchun demo-kurslar.', 'Open day of the education program: demo courses for masters, technologists, and production managers.'),
    'Круглый стол': T('Davra suhbati', 'Round table'),
    'Круглый стол «Цифровизация» (08 июля)': T('«Raqamlashtirish» davra suhbati (08 iyul)', '"Digitalization" round table (July 08)'),
    'Практический разбор внедрения цифровых инструментов. Кейсы 3 резидентов MEYOS.': T('Raqamli vositalarni joriy etishning amaliy tahlili. MEYOS uchta rezidentining keyslari.', 'Practical analysis of digital tools implementation. Cases of 3 MEYOS residents.'),
    'Выставка': T('Koʻrgazma', 'Exhibition'),
    'Tashkent Furniture Expo': T('Tashkent Furniture Expo', 'Tashkent Furniture Expo'),
    'Миссия в Казахстан (02–06 октября)': T('Qozogʻistonga missiya (02–06 oktyabr)', 'Mission to Kazakhstan (Oct 02–06)'),
    'Миссия в Казахстан — Алматы и Астана': T('Qozogʻistonga missiya — Olmaota va Ostona', 'Mission to Kazakhstan — Almaty and Astana'),
    'Казахстан': T('Qozogʻiston', 'Kazakhstan'),
    'Самарканд': T('Samarqand', 'Samarkand'),
    'Ташкент': T('Toshkent', 'Tashkent'),
    'Ташкент, Uzexpocentre': T('Toshkent, Uzexpocentre', 'Tashkent, Uzexpocentre'),
    'Дубай, ОАЭ': T('Dubay, BAA', 'Dubai, UAE'),
    'Деловой завтрак': T('Ishbilarmonlik nonushtasi', 'Business breakfast'),
    'июн': T('iyn', 'Jun'), 'июл': T('iyl', 'Jul'),
    'сен': T('sen', 'Sep'), 'окт': T('okt', 'Oct'), 'ноя': T('noy', 'Nov'),
    'Количество участников': T('Ishtirokchilar soni', 'Number of participants'),

    /* News headlines */
    'Предложения MEYOS по маркировке мебели войдут в новый техрегламент': T('MEYOSning mebel markalash boʻyicha takliflari yangi texnik reglamentga kiradi', 'MEYOS furniture marking proposals will be included in the new technical regulation'),
    'Число резидентов MEYOS превысило 500: ассоциация занимает 22% рынка мебели страны': T('MEYOS rezidentlari soni 500 dan oshdi: assotsiatsiya mamlakat mebel bozorining 22% ni egallaydi', 'MEYOS residents exceeded 500: the association occupies 22% of the country\'s furniture market'),
    'Объявлен состав делегации MEYOS на выставку Index Dubai 2026': T('Index Dubai 2026 koʻrgazmasiga MEYOS delegatsiyasi tarkibi eʼlon qilindi', 'MEYOS delegation to Index Dubai 2026 announced'),
    'Сертификат «Мастер MEYOS» признан работодателями как стандарт отрасли': T('«MEYOS ustasi» sertifikati ish beruvchilar tomonidan soha standarti sifatida tan olindi', '"MEYOS Master" certificate recognized by employers as an industry standard'),
    'Запущен третий поток EduJob: 180 мест на курсах мастеров и технологов': T('EduJob dasturining uchinchi oqimi ishga tushirildi: ustalar va texnologlar kurslarida 180 oʻrin', 'Third EduJob cohort launched: 180 seats in master and technologist courses'),
    'Запущен третий поток программы EduJob: 180 мест на курсах мастеров и технологов': T('EduJob dasturining uchinchi oqimi ishga tushirildi: ustalar va texnologlar kurslarida 180 oʻrin', 'Third EduJob cohort launched: 180 seats in master and technologist courses'),
    'Приняты поправки в Налоговый кодекс: льготы для мебельщиков закреплены до 2030 года': T('Soliq kodeksiga oʻzgartishlar qabul qilindi: mebelsozlar uchun imtiyozlar 2030 yilgacha mustahkamlandi', 'Tax Code amendments adopted: benefits for furniture makers secured until 2030'),
    'Набор открыт до 15 мая. Обучение совмещает теорию, стажировку и сертификацию.': T('Qabul 15 maygacha ochiq. Oʻqitish nazariya, amaliyot va sertifikatsiyani birlashtiradi.', 'Enrollment open until May 15. Training combines theory, internship, and certification.'),
    'Соглашение упрощает доступ к 12 мерам господдержки через личный кабинет резидента.': T('Shartnoma rezidentning shaxsiy kabineti orqali 12 ta davlat yordam choralarini soddalashtiradi.', 'The agreement simplifies access to 12 state support measures via the resident\'s personal account.'),

    /* What's happening */
    'Что происходит в мебельной индустрии Узбекистана': T('Oʻzbekiston mebel industriyasida nimalar boʻlmoqda', 'What\'s happening in Uzbekistan\'s furniture industry'),
    'Новости ассоциации': T('Assotsiatsiya yangiliklari', 'Association news'),
    'Последние события MEYOS': T('MEYOSning soʻnggi voqealari', 'Latest MEYOS events'),
    'Новости отрасли, отчёты по проектам, изменения в регулировании и анонсы программ.': T('Soha yangiliklari, loyihalar boʻyicha hisobotlar, tartibga solishdagi oʻzgarishlar va dastur eʼlonlari.', 'Industry news, project reports, regulatory changes, and program announcements.'),

    /* About page */
    'Создаём инфраструктуру мебельной индустрии Узбекистана': T('Oʻzbekiston mebel industriyasi infratuzilmasini yaratamiz', 'Building Uzbekistan\'s furniture industry infrastructure'),
    'Миссия': T('Missiya', 'Mission'),
    'Видение': T('Vision', 'Vision'),
    'История': T('Tarix', 'History'),
    'Путь ассоциации': T('Assotsiatsiya yoʻli', 'The association\'s path'),
    'От отраслевой инициативы до национального института развития мебельной индустрии — ключевые точки роста.': T('Soha tashabbusidan mebel industriyasining milliy rivojlanish institutigacha — asosiy oʻsish nuqtalari.', 'From an industry initiative to a national institute for furniture industry development — key growth points.'),
    'Основание ассоциации': T('Assotsiatsiya tashkil etilishi', 'Association founded'),
    'Первые налоговые льготы': T('Dastlabki soliq imtiyozlari', 'First tax benefits'),
    'Запуск EduJob': T('EduJob ishga tushirildi', 'EduJob launched'),
    'Экспорт под брендом MEYOS': T('MEYOS brendi ostida eksport', 'Export under the MEYOS brand'),
    'Команда, которая ведёт индустрию': T('Sohani boshqaradigan jamoa', 'The team leading the industry'),
    'Сертификаты и соглашения': T('Sertifikatlar va shartnomalar', 'Certificates and agreements'),
    'Официальный статус и признание': T('Rasmiy maqom va eʼtirof', 'Official status and recognition'),
    'MEYOS аккредитована как отраслевое объединение и является партнёром министерств Республики Узбекистан.': T('MEYOS tarmoq birlashmasi sifatida akkreditatsiyalangan va Oʻzbekiston Respublikasi vazirliklari hamkori hisoblanadi.', 'MEYOS is accredited as an industry association and a partner of the ministries of Uzbekistan.'),
    'Реестр отраслевых объединений': T('Tarmoq birlashmalar reyestri', 'Industry associations registry'),
    'Внесение в государственный реестр как некоммерческая отраслевая ассоциация.': T('Nodavlat tarmoq assotsiatsiyasi sifatida davlat reyestriga kiritilish.', 'Entry in the state registry as a non-profit industry association.'),
    'Соглашение с Минэкономики': T('Iqtisodiyot vazirligi bilan shartnoma', 'Agreement with the Ministry of Economy'),
    'Официальное соглашение о сотрудничестве в вопросах регулирования мебельной отрасли.': T('Mebel sohasini tartibga solish masalalarida hamkorlik toʻgʻrisida rasmiy shartnoma.', 'Official cooperation agreement on furniture industry regulation.'),
    'Партнёр UzExport': T('UzExport hamkori', 'UzExport partner'),
    'Аккредитация при Агентстве продвижения экспорта — доступ к программам господдержки.': T('Eksportni qoʻllab-quvvatlash agentligida akkreditatsiya — davlat yordami dasturlariga kirish.', 'Accredited at the Export Promotion Agency — access to state support programs.'),
    'Сертификат системы менеджмента качества в деятельности ассоциации.': T('Assotsiatsiya faoliyatida sifat menejmenti tizimi sertifikati.', 'Quality management system certificate in association activities.'),
    'Партнёрство с FSC для продвижения ответственного использования древесины.': T('Yogʻochdan masʼul foydalanishni ilgari surish uchun FSC bilan hamkorlik.', 'Partnership with FSC to promote responsible wood use.'),
    'Член FEMB Europe': T('FEMB Europe aʼzosi', 'FEMB Europe member'),
    'Ассоциированное членство в Европейской федерации производителей мебели.': T('Yevropa mebel ishlab chiqaruvchilari federatsiyasida assotsiativ aʼzolik.', 'Associate membership in the European Furniture Industries Confederation.'),
    'Станьте частью национальной мебельной инициативы': T('Milliy mebel tashabbusi bir qismiga aylaning', 'Become part of the national furniture initiative'),
    'Присоединитесь к 500+ компаниям, которые уже используют преимущества резидентства MEYOS.': T('MEYOS rezidentligi imtiyozlaridan foydalanayotgan 500+ kompaniyaga qoʻshiling.', 'Join 500+ companies already using MEYOS residency benefits.'),

    /* Home sections */
    'Что вы получите в ассоциации MEYOS': T('MEYOS assotsiatsiyasida nimalarga ega boʻlasiz', 'What you get in the MEYOS association'),
    'Три шага к росту': T('Oʻsishga uch qadam', 'Three steps to growth'),
    'Три ключевых действия, которые открывают перед вашей компанией весь потенциал мебельной экосистемы.': T('Sizning kompaniyangiz uchun mebel ekotizimining toʻliq salohiyatini ochadigan uchta asosiy harakat.', 'Three key actions that unlock the full potential of the furniture ecosystem for your company.'),
    'Пять измеримых причин вступить в MEYOS': T('MEYOSga aʼzo boʻlishning beshta oʻlchanadigan sababi', 'Five measurable reasons to join MEYOS'),
    'Преимущества резидентства': T('Rezidentlik imtiyozlari', 'Residency advantages'),
    'Барьеры отрасли': T('Soha toʻsiqlari', 'Industry barriers'),
    'Проблемы мебельного бизнеса и их решения': T('Mebel biznesining muammolari va ularning yechimlari', 'Furniture business challenges and solutions'),
    'Без ассоциации': T('Assotsiatsiyasiz', 'Without the association'),
    'С MEYOS': T('MEYOS bilan', 'With MEYOS'),
    'Индустриальный рост · кейсы': T('Sanoat oʻsishi · keyslar', 'Industrial growth · cases'),
    'Как резиденты MEYOS развивают свой бизнес': T('MEYOS rezidentlari biznesni qanday rivojlantirmoqda', 'How MEYOS residents grow their business'),
    'Реальные истории мебельных компаний, которые получили льготы, партнёрства и новые рынки через ассоциацию.': T('Assotsiatsiya orqali imtiyozlar, hamkorliklar va yangi bozorlarga ega boʻlgan mebel kompaniyalarining haqiqiy hikoyalari.', 'Real stories of furniture companies that gained benefits, partnerships, and new markets via the association.'),
    'Кейс 01 · Производство': T('Keys 01 · Ishlab chiqarish', 'Case 01 · Production'),
    'Кейс 02 · Дизайн и бренд': T('Keys 02 · Dizayn va brend', 'Case 02 · Design & brand'),
    'Кейс 03 · Кадры через EduJob': T('Keys 03 · EduJob orqali kadrlar', 'Case 03 · Talent via EduJob'),
    'Фабрика корпусной мебели: выход на экспорт в Казахстан и ОАЭ': T('Korpus mebel fabrikasi: Qozogʻiston va BAAga eksport', 'Cabinet furniture factory: exports to Kazakhstan and UAE'),
    'Студия авторской мебели: контракт с национальным застройщиком': T('Muallif mebel studiyasi: milliy quruvchi bilan shartnoma', 'Author furniture studio: contract with a national developer'),
    'Семейная мастерская: из цеха в сеть из 4 производств': T('Oilaviy ustaxona: sexdan 4 ishlab chiqarish tarmogʻigacha', 'Family workshop: from one shop to a network of 4 sites'),

    /* Programs */
    'Программы ассоциации': T('Assotsiatsiya dasturlari', 'Association programs'),
    'Проекты, которые запускает MEYOS': T('MEYOS ishga tushirayotgan loyihalar', 'Projects launched by MEYOS'),
    'Проекты, которые запускает MEYOS для роста отрасли': T('Soha oʻsishi uchun MEYOS ishga tushirayotgan loyihalar', 'Projects launched by MEYOS for industry growth'),
    'Собственные инициативы ассоциации — от обучения мастеров до координации национальных проектов.': T('Assotsiatsiyaning oʻz tashabbuslari — ustalarni oʻqitishdan milliy loyihalarni muvofiqlashtirishgacha.', 'Association\'s own initiatives — from master training to national project coordination.'),
    'Флагманская программа': T('Bosh dastur', 'Flagship program'),
    'EduJob — кадры и компетенции мебельной отрасли': T('EduJob — mebel sohasi kadrlari va kompetensiyalari', 'EduJob — talent and competencies of the furniture industry'),
    'Единая образовательная платформа ассоциации. От базового курса для начинающих мебельщиков до сертификации руководителей производств.': T('Assotsiatsiyaning yagona taʼlim platformasi. Boshlangʻich kursdan ishlab chiqarish rahbarlari sertifikatsiyasigacha.', 'The association\'s unified education platform. From basic courses to production-manager certification.'),
    'Базовый курс': T('Asosiy kurs', 'Basic course'),
    'Как начать мебельный бизнес': T('Mebel biznesini qanday boshlash kerak', 'How to start a furniture business'),
    'Двухмесячный онлайн-курс для начинающих предпринимателей. От идеи до первого цеха: бизнес-план, юридическая регистрация, подбор оборудования, расчёт себестоимости, каналы продаж.': T('Boshlovchi tadbirkorlar uchun ikki oylik onlayn kurs. Gʻoyadan birinchi sexgacha.', 'Two-month online course for new entrepreneurs. From idea to first workshop.'),
    'Длительность': T('Davomiyligi', 'Duration'),
    'Программа': T('Dastur', 'Program'),
    'Формат': T('Format', 'Format'),
    'Онлайн': T('Onlayn', 'Online'),
    'Мастер-столяр': T('Usta-duradgor', 'Master carpenter'),
    '6-месячный курс с практикой на производственной базе партнёров. Сертификация по стандартам MEYOS.': T('Hamkorlar ishlab chiqarishida amaliyoti bilan 6 oylik kurs. MEYOS standartlari boʻyicha sertifikatsiya.', '6-month course with practice at partner production. MEYOS standard certification.'),
    'Технолог производства': T('Ishlab chiqarish texnologi', 'Production technologist'),
    'Lean-методы, работа со станками с ЧПУ, контроль качества, оптимизация цеха.': T('Lean-usullar, CNC dastgohlari bilan ishlash, sifat nazorati, sex optimallashtirish.', 'Lean methods, CNC machine operation, quality control, shop optimization.'),
    'Руководитель производства': T('Ishlab chiqarish rahbari', 'Production manager'),
    'Управление цехом, планирование, финансы, HR. Модуль стажировки в ЕС или ОАЭ.': T('Sex boshqaruvi, rejalashtirish, moliya, HR. EI yoki BAAda amaliyot moduli.', 'Shop management, planning, finance, HR. Internship module in EU or UAE.'),
    'Остальные программы': T('Boshqa dasturlar', 'Other programs'),
    'Инструменты роста для резидентов': T('Rezidentlar uchun oʻsish vositalari', 'Growth tools for residents'),
    'Программы ассоциации, которые работают параллельно с EduJob и закрывают ключевые задачи бизнеса.': T('EduJob bilan parallel ishlaydigan va biznesning asosiy vazifalarini yopadigan assotsiatsiya dasturlari.', 'Programs running alongside EduJob, covering the key business tasks.'),
    'Экспортные миссии': T('Eksport missiyalari', 'Export missions'),
    'Коллективные стенды на выставках, поиск дистрибьюторов, сертификация по целевым рынкам.': T('Koʻrgazmalarda jamoaviy stendlar, distribyutorlarni qidirish, maqsadli bozorlar boʻyicha sertifikatsiya.', 'Collective exhibition stands, distributor search, target-market certification.'),
    'Коллективные стенды на международных выставках, подготовка к целевому рынку, поиск дистрибьюторов. До 70% расходов возвращает государство.': T('Xalqaro koʻrgazmalarda jamoaviy stendlar, maqsadli bozorga tayyorgarlik, distribyutorlarni qidirish. Xarajatlarning 70% gacha davlat qaytaradi.', 'Collective international stands, target-market prep, distributor search. Up to 70% of costs refunded by the state.'),
    'Оптовые закупки': T('Ulgurji xaridlar', 'Bulk procurement'),
    'Коллективный прямой импорт фурнитуры, ЛДСП и комплектующих — экономия 18–24% на сырье.': T('Furnitura, LDSP va komplektlovchilarning jamoaviy toʻgʻridan-toʻgʻri importi — xomashyoda 18–24% tejash.', 'Collective direct import of hardware, particleboard, and components — 18–24% raw-material savings.'),
    'Коллективный прямой импорт фурнитуры, ЛДСП, клея и тканей. Экономия для резидентов — 18–24% на сырье.': T('Furnitura, LDSP, yelim va matolarning jamoaviy toʻgʻridan-toʻgʻri importi. Rezidentlar uchun xomashyoda 18–24% tejash.', 'Collective direct import of hardware, particleboard, glue, and fabrics. 18–24% savings for residents.'),
    'Центр компетенций': T('Kompetensiyalar markazi', 'Competence Center'),
    'Консалтинг по Lean-производству, автоматизации цеха, выбору оборудования и сертификации.': T('Lean-ishlab chiqarish, sex avtomatlashtirish, uskuna tanlash va sertifikatsiya boʻyicha konsalting.', 'Consulting on Lean production, shop automation, equipment selection, and certification.'),
    'Консалтинг по автоматизации производства, выбору оборудования, Lean-внедрению и сертификации.': T('Ishlab chiqarish avtomatlashtirish, uskuna tanlash, Lean joriy etish va sertifikatsiya boʻyicha konsalting.', 'Consulting on production automation, equipment selection, Lean implementation, and certification.'),
    'Отраслевые форумы': T('Tarmoq forumlari', 'Industry forums'),
    'MEYOS Forum, Design Week, Furniture Expo — площадки встречи производителей, дизайнеров, закупщиков.': T('MEYOS Forum, Design Week, Furniture Expo — ishlab chiqaruvchilar, dizaynerlar va xaridorlarning uchrashuv joyi.', 'MEYOS Forum, Design Week, Furniture Expo — meeting places for manufacturers, designers, buyers.'),
    'MEYOS Forum, Design Week, Furniture Expo — площадки встречи производителей, дизайнеров, закупщиков и регуляторов.': T('MEYOS Forum, Design Week, Furniture Expo — ishlab chiqaruvchilar, dizaynerlar, xaridorlar va regulyatorlar uchrashadigan joylar.', 'MEYOS Forum, Design Week, Furniture Expo — meeting places for manufacturers, designers, buyers, and regulators.'),
    'Сертификация качества': T('Sifat sertifikatsiyasi', 'Quality certification'),
    'Знак «MEYOS Certified» — подтверждение стандартов качества и безопасности мебели для B2B-клиентов.': T('«MEYOS Certified» belgisi — B2B mijozlar uchun mebel sifati va xavfsizligi standartlarini tasdiqlash.', '"MEYOS Certified" mark — confirmation of quality and safety standards for B2B clients.'),
    'B2B-матчинг': T('B2B-matching', 'B2B matching'),
    'Еженедельные подборки запросов от застройщиков, HoReCa и ритейла — адресно резидентам с подходящим профилем.': T('Quruvchilar, HoReCa va chakana savdodan haftalik soʻrovlar toʻplami — mos profildagi rezidentlarga yoʻnaltirilgan.', 'Weekly request digests from developers, HoReCa, and retail — addressed to residents with matching profiles.'),
    'Результаты программ': T('Dasturlar natijalari', 'Program results'),
    'Что получили резиденты MEYOS за 2025 год': T('MEYOS rezidentlari 2025 yilda nimalarga ega boʻldi', 'What MEYOS residents gained in 2025'),
    'Получите доступ ко всем программам MEYOS': T('MEYOSning barcha dasturlariga kiring', 'Get access to all MEYOS programs'),
    'Резидентство открывает двери во все проекты ассоциации одновременно.': T('Rezidentlik assotsiatsiyaning barcha loyihalariga bir vaqtning oʻzida eshik ochadi.', 'Residency opens doors to all association projects at once.'),

    /* Residency page */
    'Резидентство MEYOS — льготы, защита интересов и доступ к рынку': T('MEYOS rezidentligi — imtiyozlar, manfaatlar himoyasi va bozorga kirish', 'MEYOS residency — benefits, protection, and market access'),
    'Особый индустриальный статус': T('Maxsus sanoat maqomi', 'Special industrial status'),
    'Финансовые преференции': T('Moliyaviy imtiyozlar', 'Financial advantages'),
    'Льготы резидента — в цифрах': T('Rezident imtiyozlari — raqamlarda', 'Resident benefits — in numbers'),
    'Сравнение стандартных ставок и ставок для резидентов ассоциации. Экономия начинается с первого месяца после получения статуса.': T('Standart stavkalar va rezidentlar stavkalarini taqqoslash. Tejash maqom olingan birinchi oydan boshlanadi.', 'Comparison of standard rates and resident rates. Savings start from the first month after getting status.'),
    'Параметр': T('Parametr', 'Parameter'),
    'Стандартная ставка': T('Standart stavka', 'Standard rate'),
    'Для резидента MEYOS': T('MEYOS rezidentiga', 'For MEYOS resident'),
    'Экономия': T('Tejash', 'Savings'),
    'Налог на прибыль (CIT)': T('Foyda soligʻi (CIT)', 'Corporate tax (CIT)'),
    'Социальные отчисления': T('Ijtimoiy ajratmalar', 'Social contributions'),
    'Таможенная пошлина на сырьё': T('Xomashyoga bojxona boji', 'Customs duty on raw materials'),
    'Таможенная пошлина на сырьё и фурнитуру': T('Xomashyo va furnituraga bojxona boji', 'Customs duty on raw materials and hardware'),
    'НДС на экспорт мебели': T('Mebel eksportiga QQS', 'VAT on furniture export'),
    'Государственное софинансирование выставок': T('Koʻrgazmalar uchun davlat hamkorlikda moliyalashtirish', 'State co-financing of exhibitions'),
    'Госсофинансирование выставок': T('Davlat hamkorlikda moliyalashtirish', 'State co-financing'),
    'Ускоренная амортизация оборудования': T('Uskuna tezlashtirilgan amortizatsiyasi', 'Accelerated equipment depreciation'),
    'до 70%': T('70% gacha', 'up to 70%'),
    'ставка 0%': T('0% stavka', '0% rate'),
    'возврат': T('qaytarish', 'refund'),
    'стандартно': T('standart', 'standard'),
    'быстрее': T('tezroq', 'faster'),

    'За рамками налогов': T('Soliqlardan tashqari', 'Beyond taxes'),
    'Нефинансовые преимущества резидентства': T('Rezidentlikning nomoliyaviy imtiyozlari', 'Non-financial residency benefits'),
    'Доступ к B2B-каналам, кадрам, партнёрским сервисам и голосу перед государством.': T('B2B kanallar, kadrlar, hamkor xizmatlari va davlat oldida ovozga kirish.', 'Access to B2B channels, talent, partner services, and a voice before the state.'),
    'B2B-база отрасли': T('Soha B2B bazasi', 'Industry B2B database'),
    'Закрытый каталог 500+ верифицированных компаний с рейтингом надёжности и тегами специализации.': T('Ishonchlilik reytingi va ixtisoslik teglari bilan 500+ tasdiqlangan kompaniyaning yopiq katalogi.', 'Closed catalog of 500+ verified companies with reliability rating and specialization tags.'),
    'Экспортная инфраструктура': T('Eksport infratuzilmasi', 'Export infrastructure'),
    'Коллективные стенды, логистика, сертификация, поиск дистрибьюторов в 12 странах.': T('Jamoaviy stendlar, logistika, sertifikatsiya, 12 mamlakatda distribyutorlarni qidirish.', 'Collective stands, logistics, certification, distributor search in 12 countries.'),
    'Защита интересов': T('Manfaatlarni himoya qilish', 'Interest protection'),
    'Представление перед регуляторами, юридическая экспертиза, поддержка в отраслевых спорах.': T('Regulyatorlar oldida namoyon boʻlish, yuridik ekspertiza, soha nizolarida qoʻllab-quvvatlash.', 'Representation before regulators, legal expertise, support in industry disputes.'),
    'Программа EduJob': T('EduJob dasturi', 'EduJob program'),
    'Обучение мастеров и управленцев, стажировки в ЕС и ОАЭ, сертификация специалистов.': T('Ustalar va boshqaruvchilarni oʻqitish, EI va BAAda amaliyot, mutaxassislar sertifikatsiyasi.', 'Training of masters and managers, internships in EU and UAE, specialist certification.'),
    'Коллективные закупки': T('Jamoaviy xaridlar', 'Collective procurement'),
    'Прямой импорт фурнитуры и ЛДСП на условиях оптовой цены — экономия 18–24% на сырье.': T('Furnitura va LDSP ulgurji narxda toʻgʻridan-toʻgʻri import — xomashyoda 18–24% tejash.', 'Direct import of hardware and particleboard at wholesale price — 18–24% savings.'),
    'Льготное финансирование': T('Imtiyozli moliyalashtirish', 'Preferential financing'),
    'Банковские продукты с пониженной ставкой, лизинг оборудования, факторинг по B2B-контрактам.': T('Pasaytirilgan stavkali bank mahsulotlari, uskuna lizingi, B2B shartnomalar boʻyicha faktoring.', 'Bank products with reduced rates, equipment leasing, factoring on B2B contracts.'),
    'Условия вступления': T('Aʼzolik shartlari', 'Membership conditions'),
    'Кто может стать резидентом': T('Kim rezident boʻlishi mumkin', 'Who can become a resident'),
    'Ассоциация открыта для зарегистрированных в Узбекистане компаний мебельной и смежных отраслей.': T('Assotsiatsiya Oʻzbekistonda roʻyxatdan oʻtgan mebel va aloqador sohalar kompaniyalariga ochiq.', 'The association is open to companies registered in Uzbekistan in the furniture and related industries.'),
    'Требования': T('Talablar', 'Requirements'),
    'Обязательства резидента': T('Rezident majburiyatlari', 'Resident obligations'),
    'Юридическое лицо с резидентством Узбекистана': T('Oʻzbekiston rezidenti yuridik shaxs', 'Legal entity with Uzbekistan residency'),
    'Основной вид деятельности связан с мебельной отраслью': T('Asosiy faoliyat turi mebel sohasi bilan bogʻliq', 'Main activity is related to the furniture industry'),
    'Отсутствие налоговой задолженности и судебных взысканий': T('Soliq qarzi va sud undiruvlarining yoʻqligi', 'No tax debt and no court recoveries'),
    'Готовность соблюдать этический кодекс и стандарты качества MEYOS': T('MEYOS axloq kodeksi va sifat standartlariga rioya qilishga tayyorlik', 'Willingness to follow the MEYOS code of ethics and quality standards'),
    'Уплата членского взноса согласно категории бизнеса': T('Biznes toifasiga koʻra aʼzolik badalini toʻlash', 'Payment of membership fee according to business category'),
    'Ежегодная отчётность по объёмам производства': T('Ishlab chiqarish hajmi boʻyicha yillik hisobot', 'Annual reporting on production volumes'),
    'Участие минимум в одном отраслевом мероприятии в год': T('Yiliga kamida bitta tarmoq tadbirida ishtirok etish', 'Participation in at least one industry event per year'),
    'Соблюдение стандартов качества и сертификации': T('Sifat va sertifikatsiya standartlariga rioya qilish', 'Compliance with quality and certification standards'),
    'Своевременная уплата членских взносов': T('Aʼzolik badallarini oʻz vaqtida toʻlash', 'Timely payment of membership fees'),
    'Добросовестная деловая практика в отношении других резидентов': T('Boshqa rezidentlar bilan vijdonli ish yuritish', 'Fair business practice with other residents'),

    'Процесс вступления': T('Aʼzolik jarayoni', 'Joining process'),
    'Путь от заявки до статуса резидента': T('Arizadan rezident maqomigacha yoʻl', 'Path from application to resident status'),
    'Среднее время рассмотрения — 10–14 рабочих дней с момента подачи полного пакета документов.': T('Oʻrtacha koʻrib chiqish vaqti — toʻliq hujjatlar paketi topshirilgan paytdan 10–14 ish kuni.', 'Average review time — 10–14 business days from full document package submission.'),
    'Заявка': T('Ariza', 'Application'),
    'Документы': T('Hujjatlar', 'Documents'),
    'Рассмотрение': T('Koʻrib chiqish', 'Review'),
    'Статус': T('Maqom', 'Status'),
    'Рост': T('Oʻsish', 'Growth'),
    'Активация': T('Faollashtirish', 'Activation'),
    'Активация статуса': T('Maqomni faollashtirish', 'Status activation'),
    'Подача заявки': T('Ariza topshirish', 'Application submission'),
    'Техническая проверка': T('Texnik tekshiruv', 'Technical review'),
    'Заполните форму на сайте. Менеджер свяжется в течение 24 часов и передаст список документов.': T('Saytdagi shaklni toʻldiring. Menejer 24 soat ichida bogʻlanib hujjatlar roʻyxatini yuboradi.', 'Fill out the form on the website. A manager will contact you within 24 hours with the document list.'),
    'Заполните форму на сайте или оставьте контакты — менеджер свяжется в течение 24 часов.': T('Saytdagi shaklni toʻldiring yoki kontaktlaringizni qoldiring — menejer 24 soat ichida bogʻlanadi.', 'Fill out the form or leave your contacts — a manager will reach out within 24 hours.'),
    'Заполните форму на сайте — менеджер свяжется в течение 24 часов.': T('Saytdagi shaklni toʻldiring — menejer 24 soat ichida bogʻlanadi.', 'Fill out the form — a manager will reach out within 24 hours.'),
    'Заполнение формы за 5 минут. Ответ менеджера — в течение 24 часов.': T('Shaklni 5 daqiqada toʻldirish. Menejer javobi — 24 soat ichida.', '5-minute form. Manager response — within 24 hours.'),
    'Передача учредительных документов, финансовой отчётности и анкеты резидента.': T('Taʼsis hujjatlari, moliyaviy hisobot va rezident anketasini topshirish.', 'Submission of founding documents, financial reports, and resident questionnaire.'),
    'Подготовка учредительных документов и анкеты резидента.': T('Taʼsis hujjatlari va rezident anketasini tayyorlash.', 'Preparation of founding documents and resident questionnaire.'),
    'Комиссия проверяет соответствие критериям и принимает решение в течение 10 рабочих дней.': T('Komissiya mezonlarga moslikni tekshiradi va 10 ish kuni ichida qaror qabul qiladi.', 'The committee verifies compliance and makes a decision within 10 business days.'),
    'Комиссия проверяет соответствие критериям: профиль деятельности, юрстатус, объёмы производства.': T('Komissiya mezonlarga moslikni tekshiradi: faoliyat profili, yuridik maqom, ishlab chiqarish hajmi.', 'The committee verifies compliance: activity profile, legal status, production volumes.'),
    'Комиссия MEYOS проверяет соответствие стандартам качества, налоговой дисциплине и профилю отрасли.': T('MEYOS komissiyasi sifat standartlari, soliq intizomi va soha profiliga moslikni tekshiradi.', 'The MEYOS committee checks compliance with quality standards, tax discipline, and industry profile.'),
    'Комиссия принимает решение, подписывается соглашение о резидентстве.': T('Komissiya qaror qabul qiladi, rezidentlik shartnomasi imzolanadi.', 'The committee makes a decision, the residency agreement is signed.'),
    'Подписание соглашения, выдача реестрового номера и подключение к льготам и сервисам.': T('Shartnoma imzolash, reyestr raqamini berish va imtiyozlar va xizmatlarga ulanish.', 'Agreement signing, registry number issue, and connection to benefits and services.'),
    'При положительном решении подписывается соглашение, присваивается статус резидента и реестровый номер.': T('Ijobiy qaror boʻlsa, shartnoma imzolanadi, rezident maqomi va reyestr raqami beriladi.', 'Upon positive decision, an agreement is signed, resident status and registry number assigned.'),
    'Подключение к льготам, B2B-базе, программе EduJob и всем проектам ассоциации.': T('Imtiyozlar, B2B bazasi, EduJob dasturi va assotsiatsiyaning barcha loyihalariga ulanish.', 'Connection to benefits, B2B database, EduJob program, and all association projects.'),

    /* Partners page */
    'Экосистема MEYOS': T('MEYOS ekotizimi', 'MEYOS ecosystem'),
    'Партнёры и резиденты ассоциации': T('Assotsiatsiya hamkorlari va rezidentlari', 'Partners and residents of the association'),
    'Все': T('Barchasi', 'All'),
    'Корпусная мебель, объёмы до 40 000 изделий в год.': T('Korpus mebel, yiliga 40 000 tagacha mahsulot hajmi.', 'Cabinet furniture, up to 40,000 units/year.'),
    'Авторская мебель и интерьерный дизайн премиум-сегмента.': T('Premium segmentdagi muallif mebeli va interyer dizayni.', 'Author furniture and premium-segment interior design.'),
    'Проектирование офисных и HoReCa-пространств «под ключ».': T('Ofis va HoReCa makonlarini «kalit ostida» loyihalash.', 'Turnkey design of office and HoReCa spaces.'),
    'Мягкая мебель и корпусные решения для ритейл-сегмента.': T('Chakana savdo uchun yumshoq mebel va korpus yechimlari.', 'Upholstered furniture and cabinet solutions for retail.'),
    'Массив ореха, ясеня и карагача — сертификация FSC.': T('Yongʻoq, shumtol va qoragʻoch massivi — FSC sertifikatsiyasi.', 'Walnut, ash, and karagach hardwood — FSC certified.'),
    'OEM-производство для международных брендов.': T('Xalqaro brendlar uchun OEM ishlab chiqarish.', 'OEM production for international brands.'),
    'Пиломатериалы и шпон, прямой импорт и собственное производство.': T('Yogʻochsoz materiallar va shpon, toʻgʻridan-toʻgʻri import va oʻz ishlab chiqarish.', 'Lumber and veneer, direct import and own production.'),
    'Грузоперевозки по СНГ, ЕС и MENA — специализация на мебели.': T('MDH, EI va MENA boʻylab yuk tashish — mebel boʻyicha ixtisoslik.', 'Freight across CIS, EU, MENA — furniture specialization.'),
    'Агентство продвижения экспорта — софинансирование выставок.': T('Eksportni ilgari surish agentligi — koʻrgazmalarni hamkorlikda moliyalashtirish.', 'Export promotion agency — exhibition co-financing.'),
    'Студия промышленного и мебельного дизайна.': T('Sanoat va mebel dizayni studiyasi.', 'Industrial and furniture design studio.'),
    'Фурнитура премиум-сегмента — эксклюзивный импорт в регионе.': T('Premium segment furniturasi — mintaqada eksklyuziv import.', 'Premium-segment hardware — exclusive regional import.'),
    'Мебель из массива для частных резиденций и HoReCa.': T('Xususiy rezidensiyalar va HoReCa uchun massiv mebel.', 'Hardwood furniture for private residences and HoReCa.'),
    'Льготное финансирование и лизинг оборудования для резидентов.': T('Rezidentlar uchun imtiyozli moliyalashtirish va uskuna lizingi.', 'Preferential financing and equipment leasing for residents.'),
    'Министерство экономики': T('Iqtisodiyot vazirligi', 'Ministry of Economy'),
    'Регуляторный партнёр в вопросах льгот и отраслевых программ.': T('Imtiyozlar va soha dasturlari masalalarida regulyator hamkor.', 'Regulatory partner for benefits and industry programs.'),
    'Кухни и гардеробные, семейное производство в Фергане.': T('Oshxonalar va garderoblar, Fargʻonada oilaviy ishlab chiqarish.', 'Kitchens and wardrobes, family production in Fergana.'),
    'Мультимодальная логистика и таможенное оформление.': T('Multimodal logistika va bojxona rasmiylashtiruvi.', 'Multimodal logistics and customs clearance.'),
    'Хотите присоединиться к сети MEYOS?': T('MEYOS tarmogʻiga qoʻshilishni xohlaysizmi?', 'Want to join the MEYOS network?'),

    /* Contacts page */
    'Свяжитесь с ассоциацией MEYOS': T('MEYOS assotsiatsiyasi bilan bogʻlaning', 'Contact the MEYOS association'),
    'Телефон': T('Telefon', 'Phone'),
    'Email': T('Email', 'Email'),
    'Офис': T('Ofis', 'Office'),
    'Офис MEYOS в Ташкенте': T('Toshkentdagi MEYOS ofisi', 'MEYOS office in Tashkent'),
    'Пн–Пт, 09:00 – 18:00': T('Du–Ju, 09:00 – 18:00', 'Mon–Fri, 09:00 – 18:00'),
    'Пн–Пт, 09:00 – 18:00. Встречи — по предварительной записи.': T('Du–Ju, 09:00 – 18:00. Uchrashuvlar — oldindan yozilish orqali.', 'Mon–Fri, 09:00 – 18:00. Meetings — by prior appointment.'),
    'Как нас найти': T('Bizni qanday topish mumkin', 'How to find us'),
    'Метро «Мирзо Улугбек», 5 минут пешком. Парковка для автомобилей и зарядные станции во дворе здания.': T('«Mirzo Ulugʻbek» metrosi, 5 daqiqa piyoda. Bino hovlisida avtoturargoh va zaryadlash stansiyalari.', '"Mirzo Ulugbek" metro, 5 minutes walking. Parking and charging stations in the building courtyard.'),
    'Реквизиты': T('Rekvizitlar', 'Requisites'),
    'ННО «MEYOS Association»': T('NNT «MEYOS Association»', 'NGO "MEYOS Association"'),
    'ИНН 304567890 · ОКЭД 94.11.0 · р/с 20208000000123456789': T('INN 304567890 · OKED 94.11.0 · h/r 20208000000123456789', 'TIN 304567890 · NACE 94.11.0 · a/c 20208000000123456789'),
    'Мессенджеры': T('Messenjerlar', 'Messengers'),
    'Для оперативной связи с отделом резидентства.': T('Rezidentlik boʻlimi bilan operativ aloqa uchun.', 'For operational contact with the residency department.'),
    'ул. Буюк Ипак Йули, 12, 4 этаж': T('Buyuk Ipak Yoʻli koʻchasi, 12, 4-qavat', 'Buyuk Ipak Yuli str., 12, 4th floor'),
    'Заявки на вступление — на residency@meyos.uz': T('Aʼzolik arizalari — residency@meyos.uz ga', 'Membership applications — to residency@meyos.uz'),
    'Оставьте сообщение — мы ответим в течение одного рабочего дня.': T('Xabar qoldiring — bir ish kuni ichida javob beramiz.', 'Leave a message — we\'ll respond within one business day.'),
    'Готовы стать частью мебельной экосистемы?': T('Mebel ekotizimining bir qismiga aylanishga tayyormisiz?', 'Ready to become part of the furniture ecosystem?'),
    'Если вы хотите вступить в ассоциацию — оставьте заявку, и менеджер свяжется с вами в течение рабочего дня.': T('Agar assotsiatsiyaga aʼzo boʻlishni istasangiz — ariza qoldiring, menejer ish kuni ichida siz bilan bogʻlanadi.', 'If you want to join the association — leave a request, and a manager will contact you within a business day.'),
    'Тема обращения': T('Murojaat mavzusi', 'Subject'),
    'Сообщение': T('Xabar', 'Message'),
    'Имя': T('Ism', 'Name'),
    'Компания': T('Kompaniya', 'Company'),
    'Вступление в ассоциацию': T('Assotsiatsiyaga aʼzo boʻlish', 'Joining the association'),
    'Льготы и преференции': T('Imtiyozlar va preferensiyalar', 'Benefits and preferences'),
    'Другое': T('Boshqa', 'Other'),

    /* Forge section (safety duplicates, some already in DICT) */
    'Индустриальные решения': T('Sanoat yechimlari', 'Industrial solutions'),
    'От проблемы — к результату': T('Muammodan — natijaga', 'From problem to result'),
    'Каждая боль мебельного бизнеса перекрыта инструментом ассоциации.': T('Mebel biznesining har bir muammosi assotsiatsiya vositasi bilan yopilgan.', 'Every furniture business pain is covered by an association tool.'),
    'Проблема': T('Muammo', 'Problem'),
    'Решение MEYOS': T('MEYOS yechimi', 'MEYOS solution'),
    'Переплата налогов и отсутствие понятной льготной схемы': T('Soliqlarni ortiqcha toʻlash va tushunarli imtiyozlar sxemasi yoʻqligi', 'Tax overpayment and no clear benefits scheme'),
    'Нет выхода на международные рынки и сертификацию': T('Xalqaro bozorlar va sertifikatsiyaga chiqish yoʻq', 'No access to international markets and certification'),
    'Кадровый голод и отсутствие отраслевых стандартов обучения': T('Kadrlar tanqisligi va tarmoq taʼlim standartlari yoʻq', 'Talent shortage and no industry training standards'),
    'Высокая себестоимость закупок фурнитуры и материалов': T('Furnitura va materiallar xaridining yuqori tannarxi', 'High cost of hardware and materials procurement'),
    'Коллективные экспортные миссии, сертификация по целевым рынкам, 70% возврата госсредствами': T('Jamoaviy eksport missiyalari, maqsadli bozorlar boʻyicha sertifikatsiya, 70% davlat qaytarish', 'Collective export missions, target-market certification, 70% state refund'),
    'Программа EduJob — сертификация мастеров, технологов и управленцев': T('EduJob dasturi — ustalar, texnologlar va boshqaruvchilar sertifikatsiyasi', 'EduJob program — certification of masters, technologists, and managers'),
    'Коллективный прямой импорт — экономия 18–24% на сырье': T('Jamoaviy toʻgʻridan-toʻgʻri import — xomashyoda 18–24% tejash', 'Collective direct import — 18–24% savings'),
    'Экосистема роста': T('Oʻsish ekotizimi', 'Growth ecosystem'),
    'Единая технологическая среда': T('Yagona texnologik muhit', 'Unified tech environment'),
    'Четыре направления, в которых MEYOS строит инфраструктуру для резидентов.': T('MEYOS rezidentlar uchun infratuzilma yaratayotgan toʻrtta yoʻnalish.', 'Four directions in which MEYOS builds resident infrastructure.'),
    'Путь к': T('Yoʻl', 'Path to'),
    'резидентству': T('rezidentlikka', 'residency'),
    'Всего 3 шага до получения статуса участника MEYOS и доступа к льготам.': T('MEYOS ishtirokchisi maqomi va imtiyozlariga ega boʻlish uchun atigi 3 qadam.', 'Just 3 steps to MEYOS participant status and access to benefits.'),

    /* Sovereign section */
    'Укрепление промышленного суверенитета отрасли': T('Sohaning sanoat suverenitetini mustahkamlash', 'Strengthening industrial sovereignty of the industry'),
    'Стандартизация': T('Standartlashtirish', 'Standardization'),
    'Инновации': T('Innovatsiyalar', 'Innovation'),
    'Кадры': T('Kadrlar', 'Talent'),
    'Стратегия 2030': T('Strategiya 2030', 'Strategy 2030'),
    'Приоритетные направления': T('Ustuvor yoʻnalishlar', 'Priority directions'),
    'Пять программ государственного масштаба, через которые MEYOS развивает отрасль до 2030 года.': T('MEYOS 2030 yilgacha sohani rivojlantiradigan davlat miqyosidagi besh dastur.', 'Five state-scale programs through which MEYOS develops the industry up to 2030.'),
    'Цифровой двойник отрасли': T('Soha raqamli egizagi', 'Industry digital twin'),
    'Лесной попечитель': T('Oʻrmon vasisi', 'Forest Keeper'),
    'Академия MEYOS': T('MEYOS akademiyasi', 'MEYOS Academy'),
    'Подготовка кадров нового поколения для высокотехнологичных мебельных производств.': T('Yuqori texnologiyali mebel ishlab chiqarishi uchun yangi avlod kadrlarini tayyorlash.', 'Training new-generation talent for high-tech furniture production.'),
    'СЭЗ «Мебельград»': T('FIZ «Mebelgrad»', 'Mebelgrad SEZ'),
    'Льготное налогообложение, инфраструктура и совместный логистический кластер для резидентов ассоциации.': T('Assotsiatsiya rezidentlari uchun imtiyozli soliq, infratuzilma va birgalikdagi logistika klasteri.', 'Preferential taxation, infrastructure, and joint logistics cluster for residents.'),
    'Реестр участников': T('Ishtirokchilar reyestri', 'Registry of participants'),
    'Элита мебельной индустрии': T('Mebel sanoati elitasi', 'Elite of the furniture industry'),
    'Правовая база': T('Huquqiy asos', 'Legal framework'),
    'Официальные документы ассоциации': T('Assotsiatsiyaning rasmiy hujjatlari', 'Official association documents'),
    'Устав Ассоциации MEYOS': T('MEYOS assotsiatsiyasi ustavi', 'MEYOS Association Charter'),
    'Этический кодекс участников': T('Ishtirokchilar axloq kodeksi', 'Participants\' Code of Ethics'),
    'Регламент сертификации «MEYOS Certified»': T('«MEYOS Certified» sertifikatsiya reglamenti', '"MEYOS Certified" certification regulations'),
    'Меморандум с Министерством инвестиций': T('Investitsiyalar vazirligi bilan memorandum', 'Memorandum with Ministry of Investments'),
    'Год основания': T('Tashkil etilgan yil', 'Year of founding'),

    /* Corporate */
    'Как это работает': T('Bu qanday ishlaydi', 'How it works'),
    'От заявки до роста за 4 этапа': T('Arizadan oʻsishgacha 4 bosqichda', 'From application to growth in 4 stages'),
    'Структурированный процесс вступления в ассоциацию — без бюрократии и скрытых условий.': T('Assotsiatsiyaga aʼzo boʻlishning tizimli jarayoni — byurokratiyasiz va yashirin shartlarsiz.', 'Structured joining process — no bureaucracy, no hidden conditions.'),

    /* Missing chips */
    'Цифровая экосистема': T('Raqamli ekotizim', 'Digital ecosystem'),
    'Цифровой рост': T('Raqamli oʻsish', 'Digital growth'),
    'Миссия и мандат': T('Missiya va mandat', 'Mission and mandate'),
    'Национальная инициатива развития мебельной индустрии': T('Mebel industriyasini rivojlantirish milliy tashabbusi', 'National initiative for furniture industry development'),
    'MEYOS действует как институт развития отрасли в рамках программы поддержки несырьевого экспорта и индустриализации Республики Узбекистан.': T('MEYOS Oʻzbekiston Respublikasining xomashyo boʻlmagan eksport va sanoatlashtirishni qoʻllab-quvvatlash dasturi doirasida soha rivojlanish instituti sifatida faoliyat yuritadi.', 'MEYOS acts as an industry development institute under Uzbekistan\'s program for non-commodity export and industrialization support.'),
    'Связь с государством': T('Davlat bilan bogʻlanish', 'Government liaison'),
    'Регулярные рабочие группы с Министерством экономики, Министерством инвестиций и Агентством поддержки экспорта.': T('Iqtisodiyot vazirligi, Investitsiyalar vazirligi va Eksportni qoʻllab-quvvatlash agentligi bilan muntazam ishchi guruhlar.', 'Regular working groups with the Ministries of Economy, Investment, and Export Support Agency.'),
    'Формирование регуляторики': T('Tartibga solishni shakllantirish', 'Regulatory shaping'),
    'Экспертиза проектов законов и техрегламентов, предложения по стандартизации отрасли, общественный контроль.': T('Qonun loyihalari va texnik reglamentlar ekspertizasi, sohani standartlashtirish takliflari, ommaviy nazorat.', 'Expert review of draft laws and technical regulations, industry standardization proposals, public oversight.'),
    'Национальный бренд': T('Milliy brend', 'National brand'),
    'Продвижение бренда «Made in Uzbekistan Furniture» на международных выставках и торговых миссиях.': T('«Made in Uzbekistan Furniture» brendini xalqaro koʻrgazmalar va savdo missiyalarida ilgari surish.', 'Promoting "Made in Uzbekistan Furniture" brand at international exhibitions and trade missions.'),

    /* Final CTA / forms */
    'Присоединяйтесь к сообществу': T('Jamoaga qoʻshiling', 'Join the community'),
    'Если вы в мебельном бизнесе — ваше место в MEYOS': T('Agar mebel biznesida boʻlsangiz — sizning oʻrningiz MEYOSda', 'If you\'re in the furniture business — your place is in MEYOS'),
    'Оставьте заявку, и менеджер ассоциации свяжется с вами в течение одного рабочего дня для консультации и подготовки документов.': T('Ariza qoldiring, assotsiatsiya menejeri konsultatsiya va hujjatlar tayyorlash uchun bir ish kuni ichida bogʻlanadi.', 'Leave a request, and an association manager will contact you within one business day for consultation and document preparation.'),
    'Заявка на вступление в ассоциацию': T('Assotsiatsiyaga aʼzolik uchun ariza', 'Association membership application'),
    'Заполните поля — и менеджер MEYOS свяжется с вами в течение 24 часов.': T('Maydonlarni toʻldiring — MEYOS menejeri 24 soat ichida bogʻlanadi.', 'Fill in the fields — a MEYOS manager will contact you within 24 hours.'),
    'Название компании': T('Kompaniya nomi', 'Company name'),
    'Контактное лицо': T('Kontakt shaxs', 'Contact person'),
    'Категория бизнеса': T('Biznes toifasi', 'Business category'),
    'Производство мебели': T('Mebel ishlab chiqarish', 'Furniture manufacturing'),
    'Дизайн-студия': T('Dizayn-studiya', 'Design studio'),
    'Поставщик материалов и фурнитуры': T('Materiallar va furnitura taʼminotchisi', 'Materials & hardware supplier'),
    'Поставщик материалов': T('Materiallar taʼminotchisi', 'Material supplier'),
    'Логистика и розница': T('Logistika va chakana savdo', 'Logistics and retail'),
    'Комментарий': T('Izoh', 'Comment'),
    'Отправляя форму, вы соглашаетесь с политикой обработки персональных данных.': T('Shakl yuborish orqali siz shaxsiy maʼlumotlarni qayta ishlash siyosatiga rozilik bildirasiz.', 'By submitting the form, you agree to the personal data processing policy.'),

    /* FAQ */
    'Часто задаваемые вопросы': T('Koʻp beriladigan savollar', 'Frequently asked questions'),
    'Собрали ответы на вопросы, которые чаще всего задают компании перед вступлением в MEYOS.': T('MEYOSga aʼzo boʻlishdan oldin kompaniyalar eng koʻp beradigan savollarga javoblar toʻplandi.', 'Answers to the most common questions asked before joining MEYOS.'),
    'Кто может стать резидентом MEYOS?': T('Kim MEYOS rezidenti boʻlishi mumkin?', 'Who can become a MEYOS resident?'),
    'Какой размер членского взноса?': T('Aʼzolik badali miqdori qancha?', 'What is the membership fee?'),
    'Как быстро начинают действовать льготы?': T('Imtiyozlar qanchalik tez ishlay boshlaydi?', 'How fast do benefits take effect?'),
    'Помогает ли MEYOS с экспортом?': T('MEYOS eksport bilan yordam beradimi?', 'Does MEYOS help with exports?'),
    'Что такое EduJob?': T('EduJob nima?', 'What is EduJob?'),
    'Можно ли выйти из ассоциации?': T('Assotsiatsiyadan chiqish mumkinmi?', 'Can I leave the association?'),

    /* Pain block titles */
    'Переплата налогов': T('Soliqlarni ortiqcha toʻlash', 'Tax overpayment'),
    'Отсутствие новых заказов': T('Yangi buyurtmalar yoʻq', 'No new orders'),
    'Разрыв с государством': T('Davlat bilan aloqa yoʻq', 'Disconnect from state'),
    'Кадровый голод': T('Kadrlar tanqisligi', 'Talent shortage'),
    'Налоговые льготы по статусу': T('Maqom boʻyicha soliq imtiyozlari', 'Status-based tax benefits'),
    'Поток B2B-заказов': T('B2B buyurtmalar oqimi', 'B2B order flow'),
    'Голос индустрии': T('Soha ovozi', 'Industry voice'),
    'Кадры через EduJob': T('EduJob orqali kadrlar', 'Talent via EduJob'),
    'Мебельный бизнес не использует доступные льготы, теряя до 15% прибыли ежегодно.': T('Mebel biznesi mavjud imtiyozlardan foydalanmaydi va yiliga 15% gacha foyda yoʻqotadi.', 'The furniture business doesn\'t use available benefits, losing up to 15% of profit annually.'),
    'Нет выхода на застройщиков, HoReCa и экспортные рынки — конкуренция только на низких ценах.': T('Quruvchilar, HoReCa va eksport bozorlariga chiqish yoʻq — raqobat faqat past narxlarda.', 'No access to developers, HoReCa, and export markets — competition only on low prices.'),
    'Невозможно донести позицию отрасли до регуляторов; законы принимаются без учёта специфики мебельного рынка.': T('Soha pozitsiyasini regulyatorlarga yetkazish imkonsiz; qonunlar mebel bozorining xususiyatlarisiz qabul qilinadi.', 'Unable to convey industry positions to regulators; laws passed without considering furniture market specifics.'),
    'Нет квалифицированных мастеров, технологов и дизайнеров — обучение ложится на бизнес в одиночку.': T('Malakali ustalar, texnologlar va dizaynerlar yoʻq — oʻqitish biznes zimmasiga tushadi.', 'No qualified masters, technologists, or designers — training falls on businesses alone.'),
    'CIT 7,5%, SSC 12%, нулевой импортный тариф — экономия до 22% от фонда оплаты и себестоимости.': T('CIT 7,5%, SSC 12%, nol import tarifi — maosh fondi va tannarxning 22% gacha tejash.', 'CIT 7.5%, SSC 12%, zero import tariff — savings up to 22% of payroll and cost.'),
    'CIT 7,5%, SSC 12%, 0% импорт — закреплены законодательно до 2030 года': T('CIT 7,5%, SSC 12%, 0% import — 2030 yilgacha qonun bilan mustahkamlangan', 'CIT 7.5%, SSC 12%, 0% imports — legislatively secured until 2030'),
    'База партнёров, тендеры, госзакупки, экспорт под единым брендом MEYOS.': T('Hamkorlar bazasi, tenderlar, davlat xaridlari, MEYOS brendi ostida eksport.', 'Partner base, tenders, public procurement, export under the MEYOS brand.'),
    'Прямой диалог с министерствами, участие в законотворчестве, лоббирование интересов отрасли.': T('Vazirliklar bilan toʻgʻridan-toʻgʻri muloqot, qonunchilikda ishtirok, soha manfaatlarini lobbichilik.', 'Direct dialogue with ministries, participation in legislation, industry lobbying.'),
    'Подготовка мастеров и управленцев, сертификация, стажировки у партнёров в ЕС и ОАЭ.': T('Ustalar va boshqaruvchilarni tayyorlash, sertifikatsiya, EI va BAA hamkorlarida amaliyot.', 'Master and manager training, certification, internships with EU and UAE partners.'),

    /* Benefits card titles */
    'Налоговые преференции': T('Soliq imtiyozlari', 'Tax preferences'),
    'Доступ к базе компаний': T('Kompaniyalar bazasiga kirish', 'Access to company database'),
    'Поддержка государства': T('Davlat yordami', 'Government support'),
    'Новые заказы и партнёрства': T('Yangi buyurtmalar va hamkorliklar', 'New orders and partnerships'),
    'Обучение и кадры': T('Oʻqitish va kadrlar', 'Training and talent'),
    'Экспорт и рынки': T('Eksport va bozorlar', 'Export and markets'),
    'Получить льготы': T('Imtiyozlarni olish', 'Get benefits'),
    'Принять участие в проектах': T('Loyihalarda ishtirok etish', 'Join the projects'),
    'Изучить льготы': T('Imtiyozlarni oʻrganish', 'Explore benefits'),
    'Узнать преимущества': T('Imtiyozlarni koʻrish', 'Explore benefits'),

    /* Residency hero & tax phrases */
    'Пять программ': T('Besh dastur', 'Five programs'),
    'Путь резидента': T('Rezident yoʻli', 'Resident path'),
    'Как вступить в ассоциацию за 4 шага': T('Assotsiatsiyaga 4 qadamda aʼzo boʻlish', 'How to join in 4 steps'),
    'Процесс прозрачен и занимает в среднем 10–14 рабочих дней от подачи заявки до получения статуса резидента.': T('Jarayon shaffof va ariza topshirishdan rezident maqomini olishgacha oʻrtacha 10–14 ish kuni oladi.', 'The process is transparent and takes on average 10–14 business days from application to status.'),
    'Налоговые и таможенные льготы резидента': T('Rezidentning soliq va bojxona imtiyozlari', 'Resident tax and customs benefits'),
    'Особый индустриальный статус даёт количественно измеримую выгоду с первого года резидентства.': T('Maxsus sanoat maqomi rezidentlikning birinchi yilidanoq miqdoriy oʻlchanadigan foyda beradi.', 'Special industrial status gives quantifiable benefits from the first year of residency.'),

    /* Misc */
    'Форумы, выставки и деловые встречи': T('Forumlar, koʻrgazmalar va biznes uchrashuvlari', 'Forums, exhibitions, and business meetings'),
    'Площадки, где резиденты MEYOS встречаются с закупщиками, дизайнерами и государственными партнёрами.': T('MEYOS rezidentlari xaridorlar, dizaynerlar va davlat hamkorlari bilan uchrashadigan maydonlar.', 'Venues where MEYOS residents meet with buyers, designers, and government partners.'),
    'Компании и институты, с которыми мы работаем': T('Biz hamkorlik qiladigan kompaniyalar va institutlar', 'Companies and institutions we work with'),
    'Производители, дизайн-студии, поставщики материалов, банки, логистические операторы и государственные партнёры.': T('Ishlab chiqaruvchilar, dizayn-studiyalar, materiallar taʼminotchilari, banklar, logistika operatorlari va davlat hamkorlari.', 'Manufacturers, design studios, material suppliers, banks, logistics operators, and government partners.'),
    'Более 500 компаний — от семейных мастерских до крупных фабрик, от дизайн-студий до государственных институтов развития. Единая B2B-сеть мебельной отрасли Узбекистана.': T('500 dan ortiq kompaniya — oilaviy ustaxonalardan yirik fabrikalargacha, dizayn-studiyalardan davlat rivojlanish institutlarigacha. Oʻzbekiston mebel sohasining yagona B2B tarmogʻi.', 'More than 500 companies — from family workshops to large factories, from design studios to state institutions. The unified B2B network of Uzbekistan\'s furniture industry.'),
    'Официальные новости ассоциации, экспортные миссии, изменения в регулировании, результаты программ и запуск новых проектов.': T('Assotsiatsiyaning rasmiy yangiliklari, eksport missiyalari, tartibga solishdagi oʻzgarishlar, dastur natijalari va yangi loyihalar ishga tushishi.', 'Official association news, export missions, regulatory changes, program results, and new project launches.'),
    'Менеджеры ассоциации отвечают на вопросы о резидентстве, льготах, программах и партнёрствах. Среднее время ответа — 4 рабочих часа.': T('Assotsiatsiya menejerlari rezidentlik, imtiyozlar, dasturlar va hamkorliklar boʻyicha savollarga javob beradi. Oʻrtacha javob vaqti — 4 ish soati.', 'Association managers answer questions about residency, benefits, programs, and partnerships. Average response time — 4 business hours.'),
    'Площадки, где резиденты MEYOS встречаются с закупщиками, дизайнерами, инвесторами и государственными партнёрами. Регистрация открыта.': T('MEYOS rezidentlari xaridorlar, dizaynerlar, investorlar va davlat hamkorlari bilan uchrashadigan maydonlar. Roʻyxatdan oʻtish ochiq.', 'Venues where MEYOS residents meet buyers, designers, investors, and government partners. Registration open.'),
    'От кадровой программы EduJob до коллективных экспортных миссий — инструменты, которые ассоциация строит для резидентов в партнёрстве с государством и бизнесом.': T('EduJob kadr dasturidan jamoaviy eksport missiyalarigacha — davlat va biznes bilan hamkorlikda assotsiatsiya rezidentlar uchun quradigan vositalar.', 'From the EduJob talent program to collective export missions — tools the association builds for residents with the state and business.'),

    /* === DOPOLNITELNYE STROKI (vse oставшиеся) === */
    'Агентство продвижения экспорта': T('Eksport ilgari surish agentligi', 'Export Promotion Agency'),
    'ISO 9001': T('ISO 9001', 'ISO 9001'),
    'FSC-партнёр': T('FSC-hamkor', 'FSC partner'),
    '8 недель': T('8 hafta', '8 weeks'),
    '12 модулей': T('12 modul', '12 modules'),
    'Двухмесячный онлайн-курс для начинающих предпринимателей. От идеи до первого цеха: бизнес-план, юридическая регистрация, подбор оборудования, расчёт себестоимости, каналы продаж.': T('Boshlovchi tadbirkorlar uchun ikki oylik onlayn kurs. Gʻoyadan birinchi sexgacha: biznes-reja, yuridik roʻyxatdan oʻtish, uskuna tanlash, tannarx hisobi, sotuv kanallari.', 'Two-month online course for new entrepreneurs. From idea to first shop: business plan, legal registration, equipment selection, cost calculation, sales channels.'),
    'Единая образовательная платформа ассоциации. От базового курса для начинающих мебельщиков до сертификации руководителей производств.': T('Assotsiatsiyaning yagona taʼlim platformasi. Boshlangʻich kurslardan ishlab chiqarish rahbarlari sertifikatsiyasigacha.', 'The association\'s unified education platform. From introductory courses to production-manager certification.'),
    'По итогам первых двух потоков EduJob сертифицировала 540 специалистов. 92% выпускников трудоустроены на предприятиях-резидентах MEYOS в течение 60 дней после выпуска.': T('EduJob dastlabki ikki oqim yakunida 540 mutaxassisni sertifikatlagan. Bitiruvchilarning 92% MEYOS rezident korxonalarida 60 kun ichida ishga joylashgan.', 'Across the first two cohorts, EduJob certified 540 specialists. 92% of graduates employed at MEYOS resident enterprises within 60 days.'),

    /* Residency details */
    'Льготы резидента': T('Rezident imtiyozlari', 'Resident benefits'),
    'Сравнение стандартных ставок и ставок для резидентов ассоциации.': T('Standart stavkalar va rezident stavkalarini taqqoslash.', 'Comparison of standard rates and resident rates.'),
    'Ассоциация открыта для зарегистрированных в Узбекистане компаний мебельной и смежных отраслей.': T('Assotsiatsiya Oʻzbekistonda roʻyxatdan oʻtgan mebel va aloqador sohalar kompaniyalariga ochiq.', 'The association is open to companies registered in Uzbekistan in furniture and related industries.'),
    'Объём производства в год': T('Yillik ishlab chiqarish hajmi', 'Annual production volume'),
    'До 5 000 изделий': T('5 000 ta mahsulotgacha', 'Up to 5,000 units'),
    '5 000 – 20 000 изделий': T('5 000 – 20 000 mahsulot', '5,000 – 20,000 units'),
    '20 000 – 100 000 изделий': T('20 000 – 100 000 mahsulot', '20,000 – 100,000 units'),
    'Более 100 000 изделий': T('100 000 dan ortiq mahsulot', 'More than 100,000 units'),
    'Коротко расскажите о компании и целях вступления': T('Kompaniya va aʼzolik maqsadlari haqida qisqa gapiring', 'Briefly describe the company and membership goals'),
    'Имя и фамилия': T('Ism va familiya', 'First and last name'),
    'ООО «Ваша компания»': T('ООО «Kompaniyangiz»', 'Ltd. "Your Company"'),
    'email@company.uz': T('email@company.uz', 'email@company.uz'),
    '+998 __ ___ __ __': T('+998 __ ___ __ __', '+998 __ ___ __ __'),
    'Ваше имя': T('Ismingiz', 'Your name'),
    'Название компании (необязательно)': T('Kompaniya nomi (ixtiyoriy)', 'Company name (optional)'),
    'Коротко опишите ваш вопрос': T('Savolingizni qisqa tavsiflang', 'Briefly describe your question'),
    'Мероприятие': T('Tadbir', 'Event'),
    'Количество участников': T('Ishtirokchilar soni', 'Number of participants'),

    /* Events page */
    'Зал отраслевого форума': T('Tarmoq forumi zali', 'Industry forum hall'),
    'Ежегодный форум ассоциации: встреча резидентов с регуляторами, презентация программы на год, круглые столы по экспорту, кадрам и цифровизации. Ожидаем 450+ участников, 30 спикеров, 12 тематических сессий.': T('Assotsiatsiyaning yillik forumi: rezidentlar va regulyatorlar uchrashuvi, yillik dastur taqdimoti, eksport, kadrlar va raqamlashtirish boʻyicha davra suhbatlari. 450+ ishtirokchi, 30 maʼruzachi, 12 mavzuli sessiya kutilmoqda.', 'Annual association forum: resident-regulator meeting, yearly program presentation, round tables on export, talent, and digitalization. 450+ participants, 30 speakers, 12 themed sessions expected.'),
    '450 участников': T('450 ishtirokchi', '450 participants'),
    '09:00 – 18:00': T('09:00 – 18:00', '09:00 – 18:00'),
    'Встреча с банками: лизинг и факторинг для мебельщиков': T('Banklar bilan uchrashuv: mebelsozlar uchun lizing va faktoring', 'Meeting with banks: leasing and factoring for furniture makers'),
    'Банки-партнёры MEYOS представят обновлённые условия финансирования для резидентов.': T('MEYOS hamkor banklari rezidentlar uchun yangilangan moliyalashtirish shartlarini taqdim etadi.', 'MEYOS partner banks will present updated financing terms for residents.'),
    'Index Dubai 2026 — коллективный стенд MEYOS': T('Index Dubai 2026 — MEYOS jamoaviy stendi', 'Index Dubai 2026 — MEYOS collective stand'),
    'EduJob Open Day — Самарканд': T('EduJob Open Day — Samarqand', 'EduJob Open Day — Samarkand'),
    'Цифровизация мебельного производства: Lean + ЧПУ': T('Mebel ishlab chiqarish raqamlashtirish: Lean + CNC', 'Furniture production digitalization: Lean + CNC'),
    'Tashkent Furniture Expo 2026': T('Tashkent Furniture Expo 2026', 'Tashkent Furniture Expo 2026'),
    'Национальная мебельная выставка. Коллективный зал MEYOS, B2B-зона, конкурс дизайна.': T('Milliy mebel koʻrgazmasi. MEYOS jamoaviy zali, B2B-zona, dizayn tanlovi.', 'National furniture exhibition. MEYOS collective hall, B2B zone, design contest.'),
    'B2B-встречи с сетевым ритейлом, HoReCa и застройщиками. Делегация MEYOS — 20 компаний.': T('Tarmoq chakana savdo, HoReCa va quruvchilar bilan B2B uchrashuvlar. MEYOS delegatsiyasi — 20 kompaniya.', 'B2B meetings with retail chains, HoReCa, and developers. MEYOS delegation — 20 companies.'),

    /* Short labels */
    'Компаний-резидентов': T('Rezident kompaniyalar', 'Resident companies'),
    'Партнёр': T('Hamkor', 'Partner'),
    'Отправляем…': T('Yuborilmoqda…', 'Sending…'),
    'Заявка отправлена ✓': T('Ariza yuborildi ✓', 'Application sent ✓'),
    'Спасибо! Менеджер MEYOS свяжется с вами в течение одного рабочего дня — для уточнения деталей и следующих шагов.': T('Rahmat! MEYOS menejeri tafsilotlar va keyingi qadamlar uchun bir ish kuni ichida siz bilan bogʻlanadi.', 'Thank you! A MEYOS manager will contact you within one business day to clarify details and next steps.'),
    'Спасибо! Менеджер MEYOS свяжется с вами в течение одного рабочего дня.': T('Rahmat! MEYOS menejeri bir ish kuni ichida siz bilan bogʻlanadi.', 'Thank you! A MEYOS manager will contact you within one business day.'),
    'Заявка принята': T('Ariza qabul qilindi', 'Application received'),
    'Хорошо': T('Yaxshi', 'OK'),
    'Посмотреть программы': T('Dasturlarni koʻrish', 'View programs'),

    /* Event registration form extras */
    'Выберите мероприятие и оставьте контакты — координатор ассоциации свяжется с вами для подтверждения участия.': T('Tadbirni tanlang va kontaktlaringizni qoldiring — assotsiatsiya koordinatori ishtirokingizni tasdiqlash uchun bogʻlanadi.', 'Choose an event and leave your contacts — an association coordinator will contact you to confirm participation.'),

    /* Top bars / headings that may still be russian */
    'Сравнение стандартных ставок и ставок для резидентов ассоциации. Экономия начинается с первого месяца после получения статуса.': T('Standart stavkalar va rezident stavkalarini taqqoslash. Tejash maqom olinganidan keyingi birinchi oydan boshlanadi.', 'Comparison of standard rates and resident rates. Savings start from the first month after receiving status.'),

    /* Numbered/mobile only */
    '16 апреля 2026': T('16 aprel 2026', 'April 16, 2026'),
    '09 апреля 2026': T('09 aprel 2026', 'April 09, 2026'),
    '02 апреля 2026': T('02 aprel 2026', 'April 02, 2026'),
    '28 марта 2026': T('28 mart 2026', 'March 28, 2026'),
    '18 марта 2026': T('18 mart 2026', 'March 18, 2026'),
    '11 марта 2026': T('11 mart 2026', 'March 11, 2026'),
    '04 марта 2026': T('04 mart 2026', 'March 04, 2026'),
    '25 февраля 2026': T('25 fevral 2026', 'February 25, 2026'),
    '18 февраля 2026': T('18 fevral 2026', 'February 18, 2026'),
    '10 февраля 2026': T('10 fevral 2026', 'February 10, 2026'),
    'Подписание соглашения в офисе': T('Ofisda shartnoma imzolash', 'Agreement signing in the office'),
    'Встреча с партнёрами в Алматы': T('Olmaotada hamkorlar bilan uchrashuv', 'Meeting with partners in Almaty'),
    'Студенты на курсе EduJob': T('EduJob kursidagi talabalar', 'Students at the EduJob course'),
    'Здание государственного учреждения': T('Davlat muassasasi binosi', 'Government institution building'),
    'Цех мебельной фабрики': T('Mebel fabrikasi sexi', 'Furniture factory shop'),
    'Конференц-зал с аудиторией': T('Auditoriyali konferensiya zali', 'Conference hall with audience'),
    'Дизайнерский интерьер': T('Dizayner interyeri', 'Designer interior'),
    'Мастер за работой': T('Ish ustidagi usta', 'Master at work'),
    'Деловая встреча': T('Ish uchrashuvi', 'Business meeting'),
    'Официальная встреча в кабинете': T('Kabinetda rasmiy uchrashuv', 'Official meeting in an office'),
  };

  /* Normalize: collapse whitespace for robust matching. */
  const norm = (s) => s.replace(/\s+/g, ' ').trim();
  const BY_KEY = new Map();
  Object.keys(D).forEach(k => BY_KEY.set(norm(k), D[k]));

  /* Walk all text nodes and remember originals. */
  const ORIG = new WeakMap();
  const SKIP_TAGS = new Set(['SCRIPT', 'STYLE', 'NOSCRIPT', 'TEXTAREA', 'CODE', 'PRE']);

  function shouldSkip(node) {
    let el = node.parentElement;
    while (el) {
      if (SKIP_TAGS.has(el.tagName)) return true;
      if (el.hasAttribute && el.hasAttribute('data-i18n')) return true;
      if (el.classList && (el.classList.contains('theme-switcher') || el.classList.contains('material-symbols-outlined'))) return true;
      el = el.parentElement;
    }
    return false;
  }

  function collectTextNodes(root) {
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
      acceptNode: (n) => {
        if (!n.nodeValue || !n.nodeValue.trim()) return NodeFilter.FILTER_REJECT;
        if (shouldSkip(n)) return NodeFilter.FILTER_REJECT;
        return NodeFilter.FILTER_ACCEPT;
      }
    });
    const nodes = [];
    let n;
    while ((n = walker.nextNode())) nodes.push(n);
    return nodes;
  }

  function apply(lang) {
    const nodes = collectTextNodes(document.body);
    nodes.forEach(node => {
      let orig = ORIG.get(node);
      if (orig === undefined) {
        orig = node.nodeValue;
        ORIG.set(node, orig);
      }
      if (lang === 'ru') {
        node.nodeValue = orig;
        return;
      }
      const key = norm(orig);
      const entry = BY_KEY.get(key);
      if (entry && entry[lang]) {
        // Preserve leading/trailing whitespace
        const leading = (orig.match(/^\s*/) || [''])[0];
        const trailing = (orig.match(/\s*$/) || [''])[0];
        node.nodeValue = leading + entry[lang] + trailing;
      }
    });
    /* Also handle placeholder/title/aria-label attributes */
    document.querySelectorAll('input[placeholder], textarea[placeholder]').forEach(el => {
      if (!ORIG.has(el)) ORIG.set(el, { placeholder: el.placeholder });
      const origP = ORIG.get(el).placeholder;
      if (lang === 'ru') el.placeholder = origP;
      else {
        const e = BY_KEY.get(norm(origP));
        if (e && e[lang]) el.placeholder = e[lang];
      }
    });
  }

  return { apply };
})();

/* Hook into main i18n: when language changes, also apply auto-dict */
window.addEventListener('meyos:lang', (e) => {
  if (window.MEYOS_AUTO) window.MEYOS_AUTO.apply(e.detail.lang);
});

/* Apply on initial load after DOM is ready */
document.addEventListener('DOMContentLoaded', () => {
  const lang = (window.MEYOS_I18N && window.MEYOS_I18N.current) || 'ru';
  if (window.MEYOS_AUTO) window.MEYOS_AUTO.apply(lang);
});
