<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                Select::make('status')->options(\App\Models\ContactMessage::STATUSES)->default('new')->required(),
                TextInput::make('name')->label('Имя')->required(),
                TextInput::make('company')->label('Компания'),
                TextInput::make('email')->email()->required(),
                TextInput::make('phone')->label('Телефон'),
                TextInput::make('topic')->label('Тема'),
                Textarea::make('message')->label('Сообщение')->rows(5)->required()->columnSpanFull(),
        
            ])->columns(2),
        ]);
    }
}
