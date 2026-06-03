<?php

namespace App\Filament\Resources\Benefits\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BenefitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                \App\Filament\Support\IconPicker::make('icon'),
                Toggle::make('is_published')->label('Опубликовано')->default(true),
                TextInput::make('sort')->label('Порядок')->numeric(),
        
            ])->columns(2),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make(array (
  'title' => 
  array (
    'label' => 'Название',
    'type' => 'text',
    'required' => true,
  ),
  'description' => 
  array (
    'label' => 'Описание',
    'type' => 'textarea',
  ),
)),
                ]),
        ]);
    }
}
