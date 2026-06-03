<?php

namespace App\Filament\Resources\Faqs\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                Select::make('page_slug')->label('Страница')->options(['home' => 'Главная', 'residency' => 'Резидентство', 'programs' => 'Программы', 'about' => 'О компании', 'partners' => 'Партнёры', 'contacts' => 'Контакты'])->default('home')->required(),
                Toggle::make('is_published')->default(true),
                TextInput::make('sort')->label('Порядок')->numeric(),
        
            ])->columns(2),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make(array (
  'question' => 
  array (
    'label' => 'Вопрос',
    'type' => 'text',
    'required' => true,
  ),
  'answer' => 
  array (
    'label' => 'Ответ',
    'type' => 'rich',
  ),
)),
                ]),
        ]);
    }
}
