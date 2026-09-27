<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\TelegramNotifier;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.site-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static string|UnitEnum|null $navigationGroup = 'Настройки';
    protected static ?string $navigationLabel = 'Настройки сайта';
    protected static ?string $title = 'Настройки сайта';
    protected static ?int $navigationSort = 0;

    public ?array $data = [];

    private function loadSettings(): array
    {
        return [
            'site_name'       => Setting::get('site_name', 'MEYOS'),
            'logo_path'       => Setting::get('logo_path'),
            'logo_dark_path'  => Setting::get('logo_dark_path'),
            'favicon_path'    => Setting::get('favicon_path'),
            'phone'           => Setting::get('phone'),
            'email'           => Setting::get('email'),
            'residency_email' => Setting::get('residency_email'),
            'address'         => Setting::get('address'),
            'hours'           => Setting::get('hours'),
            'office_lat'      => Setting::get('office_lat'),
            'office_lng'      => Setting::get('office_lng'),
            'office_zoom'     => Setting::get('office_zoom', 16),
            'entity_name'     => Setting::get('entity_name'),
            'requisites'      => Setting::get('requisites'),
            'telegram_url'    => Setting::get('telegram_url'),
            'whatsapp_url'    => Setting::get('whatsapp_url'),
            'tg_enabled'      => (bool) Setting::get('tg_enabled', false),
            'tg_bot_token'    => Setting::get('tg_bot_token'),
            'tg_chat_id'      => Setting::get('tg_chat_id'),
            'yandex_metrika_id'        => Setting::get('yandex_metrika_id'),
            'google_analytics_id'      => Setting::get('google_analytics_id'),
            'yandex_verification'      => Setting::get('yandex_verification'),
            'google_site_verification' => Setting::get('google_site_verification'),
            // Опции форм (списки для select) — [{value, label:{ru,uz,en}}, …]
            'form_categories'     => $this->flattenOptions('form.categories', 'form.categories'),
            'form_volumes'        => $this->flattenOptions('form.volumes',    'form.volumes'),
            'form_contact_topics' => $this->flattenOptions('form.contact_topics', 'form.contact_topics'),
        ];
    }

    /**
     * Конвертирует Setting.<key> в плоскую форму для Repeater.
     * Из [{value: 'x', label: {ru: 'A', uz: 'B', en: 'C'}}, …]
     * получаем     [{value: 'x', label_ru: 'A', label_uz: 'B', label_en: 'C'}, …].
     * Если ключа нет — берём встроенный дефолт из Cms::builtinOptions.
     */
    private function flattenOptions(string $settingKey, string $defaultsKey): array
    {
        $rows = Setting::get($settingKey);
        if (!is_array($rows) || $rows === []) {
            // Используем те же дефолты, что и Cms — но с сохранением всех локалей
            $rows = $this->defaultOptionRows($defaultsKey);
        }
        return array_values(array_map(function ($row) {
            $label = $row['label'] ?? [];
            return [
                'value'    => $row['value'] ?? '',
                'label_ru' => $label['ru'] ?? '',
                'label_uz' => $label['uz'] ?? '',
                'label_en' => $label['en'] ?? '',
            ];
        }, $rows));
    }

    /** Встроенные дефолты списков — те же, что в Cms::builtinOptions. */
    private function defaultOptionRows(string $key): array
    {
        $map = [
            'form.categories' => [
                ['production',  ['ru' => 'Производство мебели',             'uz' => 'Mebel ishlab chiqarish',           'en' => 'Furniture manufacturing']],
                ['design',      ['ru' => 'Дизайн-студия',                   'uz' => 'Dizayn studiyasi',                 'en' => 'Design studio']],
                ['materials',   ['ru' => 'Поставщик материалов и фурнитуры','uz' => 'Material va furnitura yetkazib beruvchi','en' => 'Materials and hardware supplier']],
                ['logistics',   ['ru' => 'Логистика и розница',             'uz' => 'Logistika va chakana savdo',        'en' => 'Logistics and retail']],
                ['other',       ['ru' => 'Другое',                          'uz' => 'Boshqa',                            'en' => 'Other']],
            ],
            'form.volumes' => [
                ['up_5k',    ['ru' => 'До 5 000 изделий/год', 'uz' => '5 000 gacha mahsulot/yil',  'en' => 'Up to 5,000 items/year']],
                ['5_20k',    ['ru' => '5 000–20 000',          'uz' => '5 000–20 000',              'en' => '5,000–20,000']],
                ['20_50k',   ['ru' => '20 000–50 000',         'uz' => '20 000–50 000',             'en' => '20,000–50,000']],
                ['over_50k', ['ru' => 'Более 50 000',          'uz' => '50 000 dan koʻp',           'en' => 'Over 50,000']],
                ['unknown',  ['ru' => 'Уточнить',              'uz' => 'Aniqlashtirish kerak',      'en' => 'To be confirmed']],
            ],
            'form.contact_topics' => [
                ['membership',  ['ru' => 'Вступление в ассоциацию', 'uz' => 'Assotsiatsiyaga aʼzo boʻlish', 'en' => 'Joining the association']],
                ['edujob',      ['ru' => 'Программа EduJob',         'uz' => 'EduJob dasturi',               'en' => 'EduJob program']],
                ['partnership', ['ru' => 'Партнёрство / медиа',      'uz' => 'Hamkorlik / media',            'en' => 'Partnership / media']],
                ['export',      ['ru' => 'Экспорт и логистика',      'uz' => 'Eksport va logistika',         'en' => 'Export and logistics']],
                ['benefits',    ['ru' => 'Налоговые льготы',         'uz' => 'Soliq imtiyozlari',            'en' => 'Tax benefits']],
                ['other',       ['ru' => 'Другое',                   'uz' => 'Boshqa',                       'en' => 'Other']],
            ],
        ];
        return array_map(fn ($r) => ['value' => $r[0], 'label' => $r[1]], $map[$key] ?? []);
    }

    public function mount(): void
    {
        $this->form->fill($this->loadSettings());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('site_settings_tabs')
                    ->tabs([
                        Tab::make('Брендинг')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make('Логотип и favicon')
                                    ->description('Логотип отображается в шапке и футере сайта. Favicon — иконка во вкладке браузера.')
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Название сайта')
                                            ->required()
                                            ->default('MEYOS'),
                                        FileUpload::make('logo_path')
                                            ->label('Логотип (для шапки, светлый фон)')
                                            ->helperText('PNG / SVG, рекомендуется 200×60. Показывается в шапке сайта.')
                                            ->image()
                                            ->disk('public')
                                            ->directory('branding')
                                            ->visibility('public')
                                            ->maxSize(2048)
                                            ->imagePreviewHeight('80'),
                                        FileUpload::make('logo_dark_path')
                                            ->label('Логотип для футера (тёмный фон)')
                                            ->helperText('Загрузите белую / светлую версию логотипа для показа на тёмном футере. Если не загружен — используется обычный логотип.')
                                            ->image()
                                            ->disk('public')
                                            ->directory('branding')
                                            ->visibility('public')
                                            ->maxSize(2048)
                                            ->imagePreviewHeight('80'),
                                        FileUpload::make('favicon_path')
                                            ->label('Favicon (PNG / ICO, рекомендуется 32×32)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('branding')
                                            ->visibility('public')
                                            ->maxSize(512)
                                            ->imagePreviewHeight('48'),
                                    ])
                                    ->columns(1),
                            ]),

                        Tab::make('Контакты')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make('Публичные контакты')
                                    ->description('Отображаются в шапке, футере и на странице «Контакты».')
                                    ->schema([
                                        TextInput::make('phone')
                                            ->label('Телефон')
                                            ->tel()
                                            ->placeholder('+998 78 ___ __ __')
                                            ->helperText('Опционально — отображается в шапке и футере'),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->placeholder('info@meyos.uz')
                                            ->helperText('Опционально — общий контактный email'),
                                        TextInput::make('residency_email')
                                            ->label('Email для заявок резидентов')
                                            ->email()
                                            ->placeholder('residency@meyos.uz')
                                            ->helperText('Опционально — куда дублируются заявки'),
                                        Textarea::make('address')
                                            ->label('Адрес офиса')
                                            ->rows(2)
                                            ->placeholder('Ташкент, ул. ...')
                                            ->helperText('Отображается на странице «Контакты» и в футере'),
                                        TextInput::make('hours')
                                            ->label('Часы работы')
                                            ->placeholder('Пн–Пт, 09:00 – 18:00')
                                            ->helperText('Опционально'),
                                    ])
                                    ->columns(2),

                                Section::make('Метка на карте')
                                    ->description('Координаты офиса для точного отображения метки на карте /contacts. Если не заполнено — Яндекс сам ищет по адресу выше (может быть неточно).')
                                    ->schema([
                                        TextInput::make('office_lat')
                                            ->label('Широта (latitude)')
                                            ->placeholder('41.311081')
                                            ->helperText('Число с точкой, например 41.311081'),
                                        TextInput::make('office_lng')
                                            ->label('Долгота (longitude)')
                                            ->placeholder('69.240562')
                                            ->helperText('Число с точкой, например 69.240562'),
                                        TextInput::make('office_zoom')
                                            ->label('Зум карты')
                                            ->numeric()
                                            ->minValue(10)
                                            ->maxValue(19)
                                            ->default(16)
                                            ->helperText('От 10 (весь город) до 19 (крыша здания)'),
                                        \Filament\Schemas\Components\View::make('filament.forms.map-coordinates-helper')->columnSpanFull(),
                                    ])
                                    ->columns(3),

                                Section::make('Социальные сети')
                                    ->description('Отображаются в футере и на страницах-профилях.')
                                    ->schema([
                                        TextInput::make('telegram_url')
                                            ->label('Telegram (публичный)')
                                            ->maxLength(255)
                                            ->placeholder('https://t.me/meyos_uz или @meyos_uz')
                                            ->helperText('Опционально. Можно вводить ссылку или @username — отображается в футере.'),
                                        TextInput::make('whatsapp_url')
                                            ->label('WhatsApp')
                                            ->maxLength(255)
                                            ->placeholder('https://wa.me/998xxxxxxxxx или +998xxxxxxxxx')
                                            ->helperText('Опционально. Можно номер или wa.me-ссылку.'),
                                    ])
                                    ->columns(2),
                                Section::make('Реквизиты')
                                    ->schema([
                                        TextInput::make('entity_name')->label('Юр. лицо')->placeholder('ННО «MEYOS Association»'),
                                        Textarea::make('requisites')->label('ИНН, ОКЭД, р/с')->rows(2)->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),

                        Tab::make('Аналитика и SEO')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Section::make('Счётчики трафика')
                                    ->description('Вставьте ID счётчиков — они автоматически подгрузятся на все страницы фронта. Не подключайте если не готовы к сбору данных (влияет на PageSpeed).')
                                    ->schema([
                                        TextInput::make('yandex_metrika_id')
                                            ->label('Yandex Metrika ID')
                                            ->numeric()
                                            ->placeholder('12345678')
                                            ->helperText('Получить: metrika.yandex.ru → создать счётчик'),
                                        TextInput::make('google_analytics_id')
                                            ->label('Google Analytics 4 ID')
                                            ->placeholder('G-XXXXXXXXXX')
                                            ->helperText('Получить: analytics.google.com → Admin → Data Streams'),
                                    ])
                                    ->columns(2),

                                Section::make('Верификация в поисковиках')
                                    ->description('Одноразовые токены для подтверждения владения сайтом в Search Console / Webmaster.')
                                    ->schema([
                                        TextInput::make('yandex_verification')
                                            ->label('Yandex Webmaster — код подтверждения')
                                            ->placeholder('abc123def456')
                                            ->helperText('Из webmaster.yandex.ru → добавить сайт → HTML-мета'),
                                        TextInput::make('google_site_verification')
                                            ->label('Google Search Console — код подтверждения')
                                            ->placeholder('abc123def456')
                                            ->helperText('Из search.google.com/search-console → добавить ресурс → HTML-тег'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tab::make('Опции форм')
                            ->icon('heroicon-o-list-bullet')
                            ->schema([
                                Section::make('Категория бизнеса — форма «Заявка на резидентство»')
                                    ->description('Выпадающий список категорий в формах на главной, странице «Резидентство» и «Контакты». Каждый пункт — на 3 языках. Порядок отображения = порядку в этом списке.')
                                    ->schema([
                                        $this->optionsRepeater('form_categories'),
                                    ]),
                                Section::make('Объём производства — форма «Заявка на резидентство»')
                                    ->description('Пункты в поле «Объём производства» на странице «Резидентство».')
                                    ->schema([
                                        $this->optionsRepeater('form_volumes'),
                                    ]),
                                Section::make('Тема обращения — форма «Контакты»')
                                    ->description('Выпадающий список тем на странице «Контакты».')
                                    ->schema([
                                        $this->optionsRepeater('form_contact_topics'),
                                    ]),
                            ]),

                        Tab::make('Telegram-бот')
                            ->icon('heroicon-o-paper-airplane')
                            ->schema([
                                Section::make('Уведомления о новых заявках в Telegram')
                                    ->description('Все 3 типа форм (заявка на резидентство, регистрация на событие, сообщение) будут отправляться в указанный чат при создании.')
                                    ->schema([
                                        Toggle::make('tg_enabled')
                                            ->label('Включить отправку в Telegram')
                                            ->default(false)
                                            ->helperText('Если выключено — заявки только сохраняются в БД, в TG не уходят.'),
                                        TextInput::make('tg_bot_token')
                                            ->label('Bot Token')
                                            ->password()
                                            ->revealable()
                                            ->placeholder('123456789:AAExxxxxxxxxxxxxxx')
                                            ->helperText('Создаётся через @BotFather в Telegram.'),
                                        TextInput::make('tg_chat_id')
                                            ->label('Chat ID')
                                            ->placeholder('-1001234567890 или 123456789')
                                            ->helperText('ID канала, группы или личного чата. Узнать: написать боту /start, либо @userinfobot.'),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Список form_* — конвертируем плоский вид {value, label_ru, label_uz, label_en}
        // обратно в структуру {value, label:{ru,uz,en}}, которую ждёт Cms::options()
        $optionKeys = [
            'form_categories'     => 'form.categories',
            'form_volumes'        => 'form.volumes',
            'form_contact_topics' => 'form.contact_topics',
        ];
        foreach ($optionKeys as $flatKey => $settingKey) {
            if (!array_key_exists($flatKey, $data)) continue;
            $rows = is_array($data[$flatKey]) ? $data[$flatKey] : [];
            $normalized = [];
            foreach ($rows as $r) {
                if (empty($r['value'])) continue;
                $normalized[] = [
                    'value' => (string) $r['value'],
                    'label' => [
                        'ru' => (string) ($r['label_ru'] ?? ''),
                        'uz' => (string) ($r['label_uz'] ?? ''),
                        'en' => (string) ($r['label_en'] ?? ''),
                    ],
                ];
            }
            Setting::put($settingKey, $normalized, 'forms');
            unset($data[$flatKey]);
        }

        foreach ($data as $key => $value) {
            // FileUpload может вернуть массив — нормализуем в строку
            if (is_array($value) && count($value) === 1) {
                $value = reset($value);
            }
            Setting::put($key, $value);
        }

        // Перезаливаем форму свежими данными, чтобы Livewire понял что upload завершён
        $this->form->fill($this->loadSettings());

        Notification::make()->title('Настройки сохранены')->success()->send();
    }

    /** Стандартный Repeater для трёх списков-опций (переиспользуется 3 раза). */
    private function optionsRepeater(string $flatKey): Repeater
    {
        return Repeater::make($flatKey)
            ->label('')
            ->schema([
                TextInput::make('value')
                    ->label('Внутренний ключ (латиница, без пробелов)')
                    ->placeholder('например, production')
                    ->required()
                    ->maxLength(50),
                TextInput::make('label_ru')
                    ->label('Русский')
                    ->required()
                    ->maxLength(150),
                TextInput::make('label_uz')
                    ->label('O‘zbekcha')
                    ->required()
                    ->maxLength(150),
                TextInput::make('label_en')
                    ->label('English')
                    ->required()
                    ->maxLength(150),
            ])
            ->columns(2)
            ->addActionLabel('+ Добавить пункт')
            ->reorderable()
            ->collapsible()
            ->itemLabel(fn (array $state): ?string => $state['label_ru'] ?? $state['value'] ?? '—')
            ->defaultItems(0);
    }

    public function testTelegram(): void
    {
        $data = $this->form->getState();
        Setting::put('tg_bot_token', $data['tg_bot_token'] ?? null);
        Setting::put('tg_chat_id',   $data['tg_chat_id']   ?? null);
        $savedEnabled = (bool) Setting::get('tg_enabled');
        Setting::put('tg_enabled', true);

        $result = TelegramNotifier::test();

        Setting::put('tg_enabled', $savedEnabled);

        Notification::make()
            ->title($result['message'])
            ->{$result['ok'] ? 'success' : 'danger'}()
            ->send();
    }

    public function pingSitemap(): void
    {
        $sitemap = url('/sitemap.xml');
        $results = [];

        // Google Ping (deprecated с 2023, но всё ещё работает как no-op)
        try {
            $r = \Illuminate\Support\Facades\Http::timeout(10)->get('https://www.google.com/ping', ['sitemap' => $sitemap]);
            $results[] = 'Google: ' . ($r->successful() ? '✓' : 'HTTP ' . $r->status());
        } catch (\Throwable $e) {
            $results[] = 'Google: ошибка';
        }

        // Yandex Webmaster Ping
        try {
            $r = \Illuminate\Support\Facades\Http::timeout(10)->get('https://webmaster.yandex.ru/ping', ['sitemap' => $sitemap]);
            $results[] = 'Yandex: ' . ($r->successful() ? '✓' : 'HTTP ' . $r->status());
        } catch (\Throwable $e) {
            $results[] = 'Yandex: ошибка';
        }

        // Bing (тоже принимает)
        try {
            $r = \Illuminate\Support\Facades\Http::timeout(10)->get('https://www.bing.com/ping', ['sitemap' => $sitemap]);
            $results[] = 'Bing: ' . ($r->successful() ? '✓' : 'HTTP ' . $r->status());
        } catch (\Throwable $e) {
            $results[] = 'Bing: ошибка';
        }

        Notification::make()
            ->title('Sitemap отправлен поисковикам')
            ->body(implode(' · ', $results) . '. Реальная переиндексация занимает от нескольких часов до нескольких дней.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ping_sitemap')
                ->label('Отправить sitemap поисковикам')
                ->icon('heroicon-o-globe-alt')
                ->color('gray')
                ->requiresConfirmation()
                ->modalDescription('Уведомит Google, Yandex и Bing что sitemap обновился. Реальная переиндексация занимает несколько часов.')
                ->action('pingSitemap'),
            Action::make('test_telegram')
                ->label('Проверить Telegram')
                ->icon('heroicon-o-paper-airplane')
                ->color('info')
                ->action('testTelegram'),
            Action::make('save')
                ->label('Сохранить')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }
}
