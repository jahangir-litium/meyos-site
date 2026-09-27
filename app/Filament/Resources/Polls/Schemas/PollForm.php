<?php

namespace App\Filament\Resources\Polls\Schemas;

use App\Filament\Support\TranslatableTabs;
use App\Models\Poll;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                TextInput::make('slug')->label('Адрес (slug)')
                    ->unique(Poll::class, 'slug', ignoreRecord: true)
                    ->maxLength(150)
                    ->helperText('Уникальный идентификатор для URL'),
                Toggle::make('is_active')->label('Активен')->default(true),
                DateTimePicker::make('starts_at')->label('Дата начала')->helperText('Пусто — начнётся сразу'),
                DateTimePicker::make('ends_at')->label('Дата окончания')->helperText('Пусто — без ограничений'),
                Toggle::make('is_anonymous')->label('Анонимный')->default(true)->helperText('Голоса не привязываются к юзеру'),
                Toggle::make('show_results_after_vote')->label('Показывать результаты после голосования')->default(true),
            ])->columns(2),

            Section::make('Вопрос (RU / UZ / EN)')->schema([
                TranslatableTabs::make([
                    'question' => ['label' => 'Вопрос', 'type' => 'text', 'required' => true],
                ]),
            ]),

            Section::make('Варианты ответа')->schema([
                Repeater::make('options')
                    ->label('Варианты')
                    ->schema([
                        TextInput::make('ru')->label('Русский')->required(),
                        TextInput::make('uz')->label('Oʻzbekcha'),
                        TextInput::make('en')->label('English'),
                    ])
                    ->columns(3)
                    ->minItems(2)
                    ->maxItems(10)
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['ru'] ?? null)
                    ->addActionLabel('Добавить вариант'),
            ]),
        ]);
    }
}
