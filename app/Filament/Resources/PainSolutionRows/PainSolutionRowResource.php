<?php

namespace App\Filament\Resources\PainSolutionRows;

use App\Filament\Resources\PainSolutionRows\Pages\CreatePainSolutionRow;
use App\Filament\Resources\PainSolutionRows\Pages\EditPainSolutionRow;
use App\Filament\Resources\PainSolutionRows\Pages\ListPainSolutionRows;
use App\Filament\Resources\PainSolutionRows\Schemas\PainSolutionRowForm;
use App\Filament\Resources\PainSolutionRows\Tables\PainSolutionRowsTable;
use App\Models\PainSolutionRow;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PainSolutionRowResource extends Resource
{
    protected static ?string $model = PainSolutionRow::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string|UnitEnum|null $navigationGroup = 'Главная страница';

    protected static ?string $navigationLabel = 'Проблема → Решение';

    protected static ?string $modelLabel = 'Пара';

    protected static ?string $pluralModelLabel = 'Проблема → Решение';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return PainSolutionRowForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PainSolutionRowsTable::configure($table);
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
            'index' => ListPainSolutionRows::route('/'),
            'create' => CreatePainSolutionRow::route('/create'),
            'edit' => EditPainSolutionRow::route('/{record}/edit'),
        ];
    }
}
