<?php

namespace App\Filament\Resources\PainSolutionRows\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PainSolutionRowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                Toggle::make('is_published')->default(true),
                TextInput::make('sort')->label('Порядок')->numeric(),
        
            ])->columns(2),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make(array (
  'pain_title' => 
  array (
    'label' => 'Проблема — заголовок',
    'type' => 'text',
    'required' => true,
  ),
  'pain_description' => 
  array (
    'label' => 'Проблема — описание',
    'type' => 'textarea',
  ),
  'solution_title' => 
  array (
    'label' => 'Решение — заголовок',
    'type' => 'text',
    'required' => true,
  ),
  'solution_description' => 
  array (
    'label' => 'Решение — описание',
    'type' => 'textarea',
  ),
)),
                ]),
        ]);
    }
}
