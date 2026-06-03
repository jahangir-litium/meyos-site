<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
                
                TextInput::make('slug')->required()->unique(\App\Models\Event::class, 'slug', ignoreRecord: true)->maxLength(150),
                Select::make('category')->label('Категория')->options(\App\Models\Event::CATEGORIES)->required(),
                DatePicker::make('event_date')->label('Дата начала')->required(),
                DatePicker::make('end_date')->label('Дата окончания'),
                TimePicker::make('start_time')->label('Время начала'),
                TimePicker::make('end_time')->label('Время окончания'),
                TextInput::make('expected_attendees')->label('Ожидаемое число участников')->numeric(),
                Toggle::make('is_featured')->label('Главное событие'),
                Toggle::make('is_published')->label('Опубликовано')->default(true),
                SpatieMediaLibraryFileUpload::make('cover')->collection('cover')->image()->imageEditor()->columnSpanFull(),
        
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
  'preview' => 
  array (
    'label' => 'Превью',
    'type' => 'textarea',
  ),
  'description' => 
  array (
    'label' => 'Описание',
    'type' => 'rich',
  ),
  'city' => 
  array (
    'label' => 'Город',
    'type' => 'text',
  ),
  'location' => 
  array (
    'label' => 'Локация',
    'type' => 'text',
  ),
)),
                ]),
        ]);
    }
}
