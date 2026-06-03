<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Идентификация')
                ->schema([
                    TextInput::make('slug')->required()->unique(\App\Models\Page::class, 'slug', ignoreRecord: true)->disabled(),
                    TextInput::make('view')->label('Blade view')->disabled(),
                    Toggle::make('is_published')->label('Опубликована')->default(true),
                ])->columns(3),

            Section::make('Картинки страницы')
                ->description('Hero-фото показывается крупным баннером. OG-картинка — для шаринга в соцсетях.')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('hero')
                        ->collection('hero')
                        ->label('Hero-фото (рекомендуется 1600×900)')
                        ->image()
                        ->maxSize(10240),
                    SpatieMediaLibraryFileUpload::make('og')
                        ->collection('og')
                        ->label('OG-картинка для соцсетей (1200×630)')
                        ->image()
                        ->maxSize(5120),
                ])
                ->columns(2),

            Section::make('Контент (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make([
                        'title'           => ['label' => 'Название страницы', 'type' => 'text', 'required' => true],
                        'seo_title'       => ['label' => 'SEO title',         'type' => 'text'],
                        'seo_description' => ['label' => 'SEO description',   'type' => 'textarea'],
                        'seo_keywords'    => ['label' => 'SEO keywords',      'type' => 'text'],
                        'hero_tag'        => ['label' => 'Hero — тег',        'type' => 'text'],
                        'hero_h1'         => ['label' => 'Hero — заголовок H1','type' => 'textarea'],
                        'hero_lead'       => ['label' => 'Hero — подзаголовок','type' => 'textarea'],
                    ]),
                ]),
        ]);
    }
}
