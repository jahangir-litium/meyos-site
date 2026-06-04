<?php

namespace App\Filament\Resources\Programs\RelationManagers;

use App\Filament\Support\TranslatableTabs;
use App\Models\ProgramBlock;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use App\Filament\Support\ImageUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BlocksRelationManager extends RelationManager
{
    protected static string $relationship = 'blocks';
    protected static ?string $title = 'Блоки описания';
    protected static ?string $modelLabel = 'Блок';
    protected static ?string $pluralModelLabel = 'Блоки описания';
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Параметры блока')
                ->schema([
                    Select::make('type')
                        ->label('Тип блока')
                        ->options(ProgramBlock::TYPES)
                        ->default('feature')
                        ->required()
                        ->native(false)
                        ->helperText('Карточка-фича / Учебный модуль / Метрика / Призыв'),
                    \App\Filament\Support\IconPicker::make('icon'),
                    Toggle::make('is_published')->label('Опубликован')->default(true),
                    TextInput::make('sort')->label('Порядок')->numeric()->default(0),
                    ImageUpload::cover('image', 'Картинка (опционально)', 'program-blocks'),
                ])
                ->columns(2),

            Section::make('Содержание блока (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make([
                        'title'       => ['label' => 'Заголовок', 'type' => 'text', 'required' => true],
                        'description' => ['label' => 'Описание', 'type' => 'rich'],
                        'meta'        => ['label' => 'Доп. текст (длительность, цена и т.п.)', 'type' => 'text'],
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
                TextColumn::make('title')->label('Заголовок')->limit(60),
                TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->formatStateUsing(fn ($s) => ProgramBlock::TYPES[$s] ?? $s),
                TextColumn::make('icon')->label('Иконка'),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
            ])
            ->defaultSort('sort')
            ->filters([
                SelectFilter::make('type')->options(ProgramBlock::TYPES),
            ])
            ->headerActions([
                CreateAction::make()->label('Добавить блок'),
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
