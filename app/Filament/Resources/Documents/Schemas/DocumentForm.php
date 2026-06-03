<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                Toggle::make('is_published')->default(true),
                TextInput::make('sort')->label('Порядок')->numeric(),
                SpatieMediaLibraryFileUpload::make('file')->collection('file')->label('PDF/файл')->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])->columnSpanFull(),
        
            ])->columns(2),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make(array (
  'title' => 
  array (
    'label' => 'Название документа',
    'type' => 'text',
    'required' => true,
  ),
)),
                ]),
        ]);
    }
}
