<?php

namespace App\Filament\Resources\TaxRows\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TaxRowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                Toggle::make('is_published')->label('Опубликовано')->default(true),
                TextInput::make('sort')->label('Порядок')->numeric(),
        
            ])->columns(2),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make(array (
  'parameter' => 
  array (
    'label' => 'Параметр',
    'type' => 'text',
    'required' => true,
  ),
  'standard_rate' => 
  array (
    'label' => 'Стандартная ставка',
    'type' => 'text',
    'required' => true,
  ),
  'resident_rate' => 
  array (
    'label' => 'Для резидента',
    'type' => 'text',
    'required' => true,
  ),
  'savings' => 
  array (
    'label' => 'Экономия',
    'type' => 'text',
    'required' => true,
  ),
)),
                ]),
        ]);
    }
}
