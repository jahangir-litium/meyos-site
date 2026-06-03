<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\TelegramNotifier;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
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

    public function mount(): void
    {
        $this->form->fill([
            'site_name'       => Setting::get('site_name', 'MEYOS'),
            'logo_path'       => Setting::get('logo_path'),
            'favicon_path'    => Setting::get('favicon_path'),
            'phone'           => Setting::get('phone'),
            'email'           => Setting::get('email'),
            'residency_email' => Setting::get('residency_email'),
            'address'         => Setting::get('address'),
            'hours'           => Setting::get('hours'),
            'entity_name'     => Setting::get('entity_name'),
            'requisites'      => Setting::get('requisites'),
            'telegram_url'    => Setting::get('telegram_url'),
            'whatsapp_url'    => Setting::get('whatsapp_url'),
            'tg_enabled'      => (bool) Setting::get('tg_enabled', false),
            'tg_bot_token'    => Setting::get('tg_bot_token'),
            'tg_chat_id'      => Setting::get('tg_chat_id'),
        ]);
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
                                            ->label('Логотип (PNG / SVG, рекомендуется 200×60)')
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
                                        TextInput::make('phone')->label('Телефон')->tel(),
                                        TextInput::make('email')->label('Email')->email(),
                                        TextInput::make('residency_email')->label('Email для заявок резидентов')->email(),
                                        Textarea::make('address')->label('Адрес офиса')->rows(2),
                                        TextInput::make('hours')->label('Часы работы')->placeholder('Пн–Пт, 09:00 – 18:00'),
                                        TextInput::make('telegram_url')->label('Telegram (публичный)')->url(),
                                        TextInput::make('whatsapp_url')->label('WhatsApp')->url(),
                                    ])
                                    ->columns(2),
                                Section::make('Реквизиты')
                                    ->schema([
                                        TextInput::make('entity_name')->label('Юр. лицо')->placeholder('ННО «MEYOS Association»'),
                                        Textarea::make('requisites')->label('ИНН, ОКЭД, р/с')->rows(2)->columnSpanFull(),
                                    ])
                                    ->columns(2),
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
        foreach ($data as $key => $value) {
            Setting::put($key, $value);
        }
        Notification::make()->title('Настройки сохранены')->success()->send();
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

    protected function getHeaderActions(): array
    {
        return [
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
