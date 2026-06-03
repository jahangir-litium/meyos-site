<?php

namespace App\Filament\Resources\Partners\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                TextInput::make('slug')->unique(\App\Models\Partner::class, 'slug', ignoreRecord: true),
                Select::make('category')->label('Категория')->options(\App\Models\Partner::CATEGORIES)->required(),
                TextInput::make('logo_text')->label('Текст логотипа')->maxLength(30),
                TextInput::make('website_url')->label('Сайт')->url(),
                TextInput::make('registry_id')->label('Реестровый номер'),
                Toggle::make('show_on_home')->label('Показывать на главной')->default(true),
                Toggle::make('is_published')->label('Опубликован')->default(true),
                FileUpload::make('logo_image')->disk('public')->directory('partners')->visibility('public')->image()->maxSize(5120)->columnSpanFull(),
        
            ])->columns(2),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make(array (
  'name' => 
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
