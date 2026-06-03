<?php

namespace App\Filament\Resources\TaxRows;

use App\Filament\Resources\TaxRows\Pages\CreateTaxRow;
use App\Filament\Resources\TaxRows\Pages\EditTaxRow;
use App\Filament\Resources\TaxRows\Pages\ListTaxRows;
use App\Filament\Resources\TaxRows\Schemas\TaxRowForm;
use App\Filament\Resources\TaxRows\Tables\TaxRowsTable;
use App\Models\TaxRow;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaxRowResource extends Resource
{
    protected static ?string $model = TaxRow::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;

    protected static string|UnitEnum|null $navigationGroup = 'Главная страница';

    protected static ?string $navigationLabel = 'Льготы — таблица';

    protected static ?string $modelLabel = 'Строка';

    protected static ?string $pluralModelLabel = 'Льготы — таблица';

    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return TaxRowForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaxRowsTable::configure($table);
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
            'index' => ListTaxRows::route('/'),
            'create' => CreateTaxRow::route('/create'),
            'edit' => EditTaxRow::route('/{record}/edit'),
        ];
    }
}
