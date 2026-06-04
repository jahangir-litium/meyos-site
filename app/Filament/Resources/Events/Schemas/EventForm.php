<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Filament\Support\ImageUpload;
use App\Filament\Support\TranslatableTabs;
use App\Models\Event;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                TextInput::make('slug')
                    ->label('Адрес (slug)')
                    ->unique(Event::class, 'slug', ignoreRecord: true)
                    ->maxLength(150)
                    ->helperText('Оставьте пустым — создастся из заголовка'),
                Select::make('category')
                    ->label('Категория')
                    ->options(fn () => Event::allCategories('ru'))
                    ->required()
                    ->native(false)
                    ->helperText('Управлять списком: Настройки → Категории'),
                DatePicker::make('event_date')->label('Дата начала')->required(),
                DatePicker::make('end_date')->label('Дата окончания (для многодневных)'),
                TimePicker::make('start_time')->label('Время начала'),
                TimePicker::make('end_time')->label('Время окончания'),
                TextInput::make('expected_attendees')->label('Ожидаемое число участников')->numeric(),
                Toggle::make('is_featured')->label('Главное событие'),
                Toggle::make('is_published')->label('Опубликовано')->default(true),
            ])->columns(2),

            Section::make('Картинка')->schema([
                ImageUpload::cover('cover_image', 'Обложка', 'events'),
            ]),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make([
                        'title'       => ['label' => 'Заголовок', 'type' => 'text', 'required' => true],
                        'preview'     => ['label' => 'Превью (краткое)', 'type' => 'textarea', 'rows' => 3],
                        'description' => ['label' => 'Описание', 'type' => 'rich'],
                        'city'        => ['label' => 'Город', 'type' => 'text'],
                        'location'    => ['label' => 'Локация / адрес площадки', 'type' => 'text'],
                        'image_alt'   => ['label' => 'Alt-текст обложки (SEO)', 'type' => 'text'],
                    ]),
                ]),

            // ============ SEO ============
            Section::make('SEO (для поисковых систем и соцсетей)')
                ->description('Если поля пустые — берутся из заголовка и превью.')
                ->schema([
                    TranslatableTabs::make([
                        'seo_title'       => ['label' => 'SEO title (до 60 символов)', 'type' => 'text'],
                        'seo_description' => ['label' => 'SEO description (до 160 символов)', 'type' => 'textarea', 'rows' => 2],
                    ]),
                    ImageUpload::og('seo_image', 'OG-картинка для соцсетей (1200×630)', 'events/og')
                        ->helperText('Используется при шере в соцсетях. Если пусто — берётся обложка.'),
                ])
                ->collapsed(),
        ]);
    }
}
