<?php

namespace App\Filament\Resources\News\Schemas;

use App\Filament\Support\TranslatableTabs;
use App\Models\News;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')
                ->schema([
                    TextInput::make('slug')
                        ->required()
                        ->unique(News::class, 'slug', ignoreRecord: true)
                        ->maxLength(150)
                        ->helperText('Латиница, цифры, дефис'),
                    Select::make('category')
                        ->label('Категория')
                        ->options(News::CATEGORIES)
                        ->required(),
                    DatePicker::make('published_at')
                        ->label('Дата публикации')
                        ->required()
                        ->default(now()),
                    Toggle::make('is_featured')->label('Главная новость'),
                    Toggle::make('is_published')->label('Опубликована')->default(true),
                    FileUpload::make('cover_image')
                        ->label('Обложка')
                        ->collection('cover')
                        ->image()
                        
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make([
                        'title'     => ['label' => 'Заголовок', 'type' => 'text', 'required' => true],
                        'preview'   => ['label' => 'Превью',   'type' => 'textarea', 'rows' => 3],
                        'content'   => ['label' => 'Полный текст', 'type' => 'rich'],
                        'image_alt' => ['label' => 'Alt картинки', 'type' => 'text'],
                    ]),
                ]),
        ]);
    }
}
