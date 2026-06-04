<?php

namespace App\Filament\Resources\BusinessCases\Schemas;

use App\Filament\Support\ImageUpload;
use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BusinessCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                Toggle::make('is_published')->label('Опубликован')->default(true),
                TextInput::make('sort')->label('Порядок')->numeric(),
                ImageUpload::cover('cover_image', 'Обложка кейса', 'cases'),
            ])->columns(2),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make(array (
  'tag' => 
  array (
    'label' => 'Тег (Кейс 01 · ...)',
    'type' => 'text',
    'required' => true,
  ),
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
  'metric1_value' => 
  array (
    'label' => 'Метрика 1 — значение',
    'type' => 'text',
  ),
  'metric1_label' => 
  array (
    'label' => 'Метрика 1 — подпись',
    'type' => 'text',
  ),
  'metric2_value' => 
  array (
    'label' => 'Метрика 2 — значение',
    'type' => 'text',
  ),
  'metric2_label' => 
  array (
    'label' => 'Метрика 2 — подпись',
    'type' => 'text',
  ),
  'metric3_value' => 
  array (
    'label' => 'Метрика 3 — значение',
    'type' => 'text',
  ),
  'metric3_label' => 
  array (
    'label' => 'Метрика 3 — подпись',
    'type' => 'text',
  ),
)),
                ]),
        ]);
    }
}
