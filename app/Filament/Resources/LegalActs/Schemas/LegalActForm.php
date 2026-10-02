<?php

namespace App\Filament\Resources\LegalActs\Schemas;

use App\Filament\Support\TranslatableTabs;
use App\Models\LegalAct;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LegalActForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')
                ->description('Базовые атрибуты акта. Slug — автоматически из названия.')
                ->schema([
                    TextInput::make('act_number')
                        ->label('Номер документа')
                        ->placeholder('ПП-193')
                        ->maxLength(60),
                    DatePicker::make('act_date')
                        ->label('Дата принятия')
                        ->placeholder('27.05.2025'),
                    Select::make('category')
                        ->label('Категория')
                        ->options(LegalAct::CATEGORIES)
                        ->required()
                        ->native(false)
                        ->default('other'),
                    Select::make('status')
                        ->label('Статус')
                        ->options(LegalAct::STATUSES)
                        ->required()
                        ->native(false)
                        ->default('active'),
                    TextInput::make('slug')
                        ->label('Адрес (slug)')
                        ->unique(LegalAct::class, 'slug', ignoreRecord: true)
                        ->maxLength(150)
                        ->helperText('Пусто — создастся из названия'),
                    TextInput::make('source_url')
                        ->label('Ссылка на источник')
                        ->url()
                        ->rules(['nullable', 'url', 'starts_with:https://,http://'])
                        ->placeholder('https://lex.uz/ru/docs/...')
                        ->maxLength(500)
                        ->helperText('Официальный источник: lex.uz, nrm.uz, norma.uz'),
                    Toggle::make('is_published')->label('Опубликовано')->default(true),
                    Toggle::make('is_featured')
                        ->label('Показать на главной')
                        ->helperText('Входит в блок «Законодательная база отрасли»'),
                ])->columns(2),

            Section::make('Файл для скачивания')
                ->description('Загрузите PDF оригинала (опционально). На странице появится кнопка «Скачать PDF».')
                ->schema([
                    FileUpload::make('pdf_path')
                        ->label('PDF-файл')
                        ->acceptedFileTypes(['application/pdf'])
                        ->disk('public')
                        ->directory('legal-acts')
                        ->visibility('public')
                        ->maxSize(20480), // 20 МБ
                ])->collapsible()->collapsed(fn ($record) => !$record?->pdf_path),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make([
                        'title'   => ['label' => 'Название акта', 'type' => 'text', 'required' => true],
                        'summary' => ['label' => 'Краткое описание (1-2 абзаца, что регулирует)', 'type' => 'textarea', 'rows' => 4],
                        'content' => ['label' => 'Полный текст (можно скопировать с lex.uz)', 'type' => 'rich'],
                    ]),
                ]),

            Section::make('SEO (для поисковиков + соцсетей)')
                ->description('Если не заполнено — берётся из названия и описания.')
                ->schema([
                    TranslatableTabs::make([
                        'seo_title'       => ['label' => 'SEO-заголовок', 'type' => 'text'],
                        'seo_description' => ['label' => 'SEO-описание', 'type' => 'textarea', 'rows' => 3],
                    ]),
                ])->collapsible()->collapsed(),
        ]);
    }
}
