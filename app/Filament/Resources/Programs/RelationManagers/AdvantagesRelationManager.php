<?php

namespace App\Filament\Resources\Programs\RelationManagers;

use App\Filament\Support\TranslatableTabs;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdvantagesRelationManager extends RelationManager
{
    protected static string $relationship = 'advantages';
    protected static ?string $title = 'Преимущества программы';
    protected static ?string $modelLabel = 'Преимущество';
    protected static ?string $pluralModelLabel = 'Преимущества программы';
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Параметры')
                ->schema([
                    \App\Filament\Support\IconPicker::make('icon'),
                    Toggle::make('is_published')->label('Опубликовано')->default(true),
                    TextInput::make('sort')->label('Порядок')->numeric()->default(0),
                ])
                ->columns(3),

            Section::make('Текст (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make([
                        'title'       => ['label' => 'Заголовок', 'type' => 'text', 'required' => true],
                        'description' => ['label' => 'Описание', 'type' => 'textarea', 'rows' => 3],
                    ]),
                ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('sort')->label('#')->sortable(),
                TextColumn::make('icon')->label('Иконка'),
                TextColumn::make('title')->label('Заголовок')->limit(80),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
            ])
            ->defaultSort('sort')
            ->headerActions([
                CreateAction::make()->label('Добавить преимущество'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
