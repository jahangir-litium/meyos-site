<?php

namespace App\Filament\Resources\BusinessCases;

use App\Filament\Resources\BusinessCases\Pages\CreateBusinessCase;
use App\Filament\Resources\BusinessCases\Pages\EditBusinessCase;
use App\Filament\Resources\BusinessCases\Pages\ListBusinessCases;
use App\Filament\Resources\BusinessCases\Schemas\BusinessCaseForm;
use App\Filament\Resources\BusinessCases\Tables\BusinessCasesTable;
use App\Models\BusinessCase;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BusinessCaseResource extends Resource
{
    protected static ?string $model = BusinessCase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static string|UnitEnum|null $navigationGroup = 'Контент';

    protected static ?string $navigationLabel = 'Бизнес-кейсы';

    protected static ?string $modelLabel = 'Кейс';

    protected static ?string $pluralModelLabel = 'Бизнес-кейсы';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return BusinessCaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BusinessCasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBusinessCases::route('/'),
            'create' => CreateBusinessCase::route('/create'),
            'edit' => EditBusinessCase::route('/{record}/edit'),
        ];
    }
}
