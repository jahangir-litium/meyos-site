<?php

namespace App\Filament\Resources\Programs\Schemas;

use App\Filament\Support\TranslatableTabs;
use App\Models\Program;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')
                ->description('Идентификация программы. Каждая программа отдельная и имеет свой URL.')
                ->schema([
                    TextInput::make('slug')
                        ->label('URL-идентификатор')
                        ->required()
                        ->unique(Program::class, 'slug', ignoreRecord: true)
                        ->helperText('Оставьте пустым — создастся из названия (edujob, marketplace, …)'),
                    \App\Filament\Support\IconPicker::make('icon'),
                    Select::make('color')
                        ->label('Цвет акцента')
                        ->options([
                            'primary' => 'Primary (бренд)',
                            'wood'    => 'Дерево',
                            'green'   => 'Зелёный',
                            'gold'    => 'Золотой',
                        ])
                        ->native(false),
                    Toggle::make('is_flagship')->label('Флагман (на видном месте)'),
                    Toggle::make('is_published')->label('Опубликована')->default(true),
                    TextInput::make('sort')->label('Порядок')->numeric()->default(0),
                    FileUpload::make('cover_image')
                        
                        ->label('Обложка / hero-картинка')
                        ->image()
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Карточка программы (для списка и главной)')
                ->description('Короткое название и описание для превью.')
                ->schema([
                    TranslatableTabs::make([
                        'chip'          => ['label' => 'Бейдж (например: EduJob · Флагман)', 'type' => 'text'],
                        'title'         => ['label' => 'Название программы', 'type' => 'text', 'required' => true],
                        'description'   => ['label' => 'Короткое описание для карточки', 'type' => 'textarea', 'rows' => 3],
                        'short_summary' => ['label' => 'Что включает (1-2 предложения)', 'type' => 'textarea', 'rows' => 2],
                    ]),
                ]),

            Section::make('Hero детальной страницы программы')
                ->description('Заголовок и подзаголовок на отдельной странице программы.')
                ->collapsed()
                ->schema([
                    TranslatableTabs::make([
                        'hero_h1'   => ['label' => 'H1 на странице программы', 'type' => 'text'],
                        'hero_lead' => ['label' => 'Подзаголовок / лид', 'type' => 'textarea', 'rows' => 3],
                    ]),
                ]),
        ]);
    }
}
