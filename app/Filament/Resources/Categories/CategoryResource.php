<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Support\TranslatableTabs;
use App\Models\Category;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;
    protected static string|UnitEnum|null $navigationGroup = 'Настройки';
    protected static ?string $navigationLabel = 'Категории';
    protected static ?string $modelLabel = 'Категория';
    protected static ?string $pluralModelLabel = 'Категории';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')
                ->schema([
                    Select::make('type')
                        ->label('Раздел')
                        ->options(Category::TYPES)
                        ->required()
                        ->native(false)
                        ->helperText('К какому списку относится категория'),
                    TextInput::make('slug')
                        ->label('Код (slug)')
                        ->required()
                        ->maxLength(100)
                        ->regex('/^[a-z0-9-]+$/')
                        ->helperText('Только латиница, цифры и дефис. Используется в БД и URL — НЕ менять после создания'),
                    Toggle::make('is_published')->label('Опубликована')->default(true),
                    TextInput::make('sort')->label('Порядок')->numeric()->default(0),
                ])->columns(2),

            Section::make('Название (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make([
                        'name' => ['label' => 'Название', 'type' => 'text', 'required' => true],
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Раздел')
                    ->badge()
                    ->formatStateUsing(fn ($s) => Category::TYPES[$s] ?? $s),
                TextColumn::make('slug')->label('Код')->copyable(),
                TextColumn::make('name')
                    ->label('Название')
                    ->getStateUsing(fn (Category $r) => $r->getTranslation('name', 'ru', false)),
                IconColumn::make('is_published')->label('Опубл.')->boolean(),
                TextColumn::make('sort')->sortable(),
            ])
            ->reorderable('sort')
            ->defaultGroup('type')
            ->filters([
                SelectFilter::make('type')->options(Category::TYPES),
                TrashedFilter::make()->label('Корзина'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            \Illuminate\Database\Eloquent\SoftDeletingScope::class,
        ]);
    }
}
