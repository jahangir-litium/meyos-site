<?php

namespace App\Filament\Resources\EventRegistrations\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                Select::make('event_id')->label('Мероприятие')->relationship('event', 'slug'),
                TextInput::make('event_name')->label('Название (если без связи)'),
                Select::make('status')->options(\App\Models\EventRegistration::STATUSES)->default('new')->required(),
                TextInput::make('company')->label('Компания')->required(),
                TextInput::make('name')->label('Имя')->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('phone')->label('Телефон')->required(),
                TextInput::make('attendees_count')->label('Кол-во участников')->numeric()->default(1),
        
            ])->columns(2),
        ]);
    }
}
