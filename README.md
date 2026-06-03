# MEYOS — Корпоративный сайт + CMS (Laravel)

Laravel-монолит сайта ассоциации мебельщиков Узбекистана **MEYOS**. Многоязычная админка на Filament, аналитика посещаемости, обработка заявок, интеграция с Telegram.

## Стек

- **Laravel 13** + **PHP 8.3**
- **Filament 5** (админ-панель)
- **SQLite** в dev / **MySQL 8** в проде (`.env`)
- **Spatie**: `laravel-translatable`, `laravel-medialibrary`
- **Blade** + ванильный CSS из дизайн-системы (Tailwind 4 в админке)

## Структура

```
app/
├── Filament/
│   ├── Pages/SiteSettings.php          # Логотип + контакты + Telegram
│   ├── Resources/                       # 18 ресурсов админки
│   └── Widgets/                         # 6 виджетов дашборда
├── Http/
│   ├── Controllers/                     # 5 контроллеров
│   └── Middleware/
│       ├── SetLocale.php                # RU/UZ/EN из URL/cookie
│       └── TrackPageView.php            # Аналитика посещаемости
├── Models/                              # 21 модель
└── Services/TelegramNotifier.php        # Отправка заявок в TG-чат

database/
├── migrations/                          # 6 миграций
└── seeders/MeyosContentSeeder.php       # Полное наполнение сайта

resources/views/
├── layouts/app.blade.php                # Главный layout
├── partials/{header,footer,mobile-menu}.blade.php
└── pages/                               # 10 страниц
```

## Возможности

### Контент
- **8 страниц**: home, about, residency, programs, partners, events, news, contacts
- **Программы** с под-блоками описания и преимуществами (Relation Managers)
- **Бизнес-кейсы** с метриками
- **Timeline** истории ассоциации
- **Команда**, **Партнёры** (фильтр по категориям), **Сертификаты**
- **FAQ**, **Льготы** (таблица), **Шаги вступления**
- **Новости** с превью, полным текстом и фильтром по категориям
- **Мероприятия** со страницей детализации и формой регистрации
- Все поля **переводимые** RU/UZ/EN через `spatie/laravel-translatable`

### Админка
- 18 ресурсов с CRUD, поиском, фильтрами, медиа-загрузкой
- Табы переводов RU/UZ/EN на каждой форме
- Drag-and-drop файлы с image-editor (crop, rotate)
- RelationManagers (блоки и преимущества внутри Программ)

### Заявки
3 формы → запись в БД + опциональная отправка в **Telegram-чат**:
- Заявка на резидентство
- Регистрация на мероприятие
- Сообщение из контактов

### Дашборд (6 виджетов)
- 6 KPI: заявки, посещения, уникальные посетители, конверсия, регистрации, сообщения
- График заявки + посещения за 30 дней
- Donut по категориям заявок
- Pie по устройствам (mobile/desktop/tablet)
- Таблица последних заявок со статус-бейджем
- Топ страниц за неделю

### Аналитика посещаемости
- Собственный middleware `TrackPageView` без внешних сервисов
- SHA-256 хэширование IP (GDPR-friendly)
- Бот-фильтр (Googlebot, YandexBot и др.)
- Определение устройства (mobile/tablet/desktop)

### Настройки сайта
- Загрузка логотипа и favicon через UI
- Контакты, реквизиты, мессенджеры
- Telegram-бот: токен + chat_id + on/off + кнопка «Проверить»

## Установка

```bash
git clone <repo-url> meyos
cd meyos

# Зависимости
composer install

# .env
cp .env.example .env
php artisan key:generate

# БД (SQLite в dev)
touch database/database.sqlite
php artisan migrate --seed

# Storage symlink
php artisan storage:link

# Запуск
php artisan serve
```

- **Сайт**: http://localhost:8000
- **Админка**: http://localhost:8000/admin
  - `admin@meyos.uz` / `meyos-admin-2026`

## Тесты

```bash
php artisan test
# 17 tests, 57 assertions — passed
```

## Продакшн

- БД: переключить `.env` на MySQL (`DB_CONNECTION=mysql`)
- Storage symlink на сервере: `php artisan storage:link`
- Кэш: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
