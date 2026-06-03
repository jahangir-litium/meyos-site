<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                TextInput::make('initials')->label('Инициалы (если нет фото)')->maxLength(5),
                Toggle::make('is_published')->default(true),
                TextInput::make('sort')->label('Порядок')->numeric(),
                SpatieMediaLibraryFileUpload::make('photo')->collection('photo')->image()->imageEditor()->avatar(),
        
            ])->columns(2),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make(array (
  'name' => 
  array (
    'label' => 'Имя',
    'type' => 'text',
    'required' => true,
  ),
  'role' => 
  array (
    'label' => 'Должность',
    'type' => 'text',
    'required' => true,
  ),
)),
                ]),
        ]);
    }
}
