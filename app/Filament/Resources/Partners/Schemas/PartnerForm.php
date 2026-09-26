<?php

namespace App\Filament\Resources\Partners\Schemas;

use App\Filament\Support\ImageUpload;
use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([

                TextInput::make('slug')->unique(\App\Models\Partner::class, 'slug', ignoreRecord: true),
                Select::make('category')
                    ->label('Категория')
                    ->options(fn () => \App\Models\Partner::allCategories('ru'))
                    ->required()
                    ->native(false)
                    ->helperText('Управлять списком: Настройки → Категории'),
                Select::make('region')
                    ->label('Регион')
                    ->options(\App\Models\Partner::REGIONS)
                    ->native(false)
                    ->searchable()
                    ->helperText('Область/город регистрации компании'),
                TextInput::make('founded_year')
                    ->label('Год основания')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue((int) date('Y'))
                    ->placeholder('2015'),
                TextInput::make('logo_text')->label('Текст логотипа')->maxLength(30),
                TextInput::make('website_url')->label('Сайт')->url(),
                TextInput::make('registry_id')->label('Реестровый номер'),
                Toggle::make('show_on_home')->label('Показывать на главной')->default(true),
                Toggle::make('is_published')->label('Опубликован')->default(true),
                ImageUpload::logo('logo_image', 'Логотип партнёра', 'partners', 5120),

            ])->columns(2),

            Section::make('Контакты')->schema([
                TextInput::make('contact_email')->label('Email')->email(),
                TextInput::make('contact_phone')->label('Телефон')->tel(),
            ])->columns(2)->collapsible(),

            Section::make('Соцсети')->schema([
                TextInput::make('socials.telegram')->label('Telegram')->prefix('@')->placeholder('woodline_uz'),
                TextInput::make('socials.instagram')->label('Instagram')->prefix('@')->placeholder('woodline.uz'),
                TextInput::make('socials.facebook')->label('Facebook URL')->url()->placeholder('https://facebook.com/…'),
            ])->columns(3)->collapsible()->collapsed(),

            Section::make('Галерея (до 6 фото)')->schema([
                FileUpload::make('gallery_images')
                    ->label('Фотографии производства/продукции')
                    ->multiple()
                    ->image()
                    ->imageEditor()
                    ->maxFiles(6)
                    ->maxSize(5120)
                    ->disk('public')
                    ->directory('partners/gallery')
                    ->reorderable()
                    ->helperText('Максимум 6 фото, до 5 МБ каждое'),
            ])->collapsible()->collapsed(),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make([
                        'name' => [
                            'label' => 'Название',
                            'type' => 'text',
                            'required' => true,
                        ],
                        'description' => [
                            'label' => 'Короткое описание (для карточки)',
                            'type' => 'textarea',
                        ],
                        'about' => [
                            'label' => 'Полное описание (для страницы-профиля)',
                            'type' => 'rich',
                        ],
                    ]),
                ]),

            Section::make('SEO (для поисковиков + соцсетей)')
                ->description('Если не заполнены — берутся название и короткое описание. Оптимально: title 55-60 симв., description 140-155 симв.')
                ->schema([
                    TranslatableTabs::make([
                        'seo_title' => [
                            'label' => 'Title (в результатах поиска Google)',
                            'type' => 'text',
                        ],
                        'seo_description' => [
                            'label' => 'Meta description (в результатах поиска)',
                            'type' => 'textarea',
                        ],
                    ]),
                    ImageUpload::logo('seo_image', 'OG-картинка (для Facebook/Telegram/WhatsApp предпросмотра)', 'partners/seo', 5120),
                ])->collapsible()->collapsed(),

            Section::make('Аналитика (только для чтения)')->schema([
                TextInput::make('views_count_total')->label('Всего просмотров')->disabled(),
                TextInput::make('views_count_30d')->label('За 30 дней')->disabled(),
                TextInput::make('last_viewed_at')->label('Последний просмотр')->disabled(),
            ])->columns(3)->collapsible()->collapsed()->visibleOn('edit'),
        ]);
    }
}
