<?php

namespace App\Filament\Resources\TimelineItems\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TimelineItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                TextInput::make('year')->label('Год')->required()->maxLength(10),
                Toggle::make('is_highlight')->label('Выделить (последняя точка)'),
                Toggle::make('is_published')->default(true),
                TextInput::make('sort')->label('Порядок')->numeric(),
        
            ])->columns(2),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make(array (
  'title' => 
  array (
    'label' => 'Заголовок',
    'type' => 'text',
    'required' => true,
  ),
  'description' => 
  array (
    'label' => 'Описание',
    'type' => 'textarea',
    'rows' => 5,
  ),
)),
                ]),
        ]);
    }
}
