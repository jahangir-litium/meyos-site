<?php

namespace App\Filament\Resources\MembershipApplications\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MembershipApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                Select::make('status')->options(\App\Models\MembershipApplication::STATUSES)->default('new')->required(),
                TextInput::make('company')->label('Компания')->required(),
                TextInput::make('name')->label('Имя')->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('phone')->label('Телефон')->required(),
                TextInput::make('category')->label('Категория'),
                TextInput::make('volume')->label('Объём производства'),
                Textarea::make('message')->label('Комментарий')->rows(3)->columnSpanFull(),
                TextInput::make('source_page')->label('Страница источника'),
        
            ])->columns(2),
        ]);
    }
}
