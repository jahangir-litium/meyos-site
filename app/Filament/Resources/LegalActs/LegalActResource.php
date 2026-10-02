<?php

namespace App\Filament\Resources\LegalActs;

use App\Filament\Resources\LegalActs\Pages\CreateLegalAct;
use App\Filament\Resources\LegalActs\Pages\EditLegalAct;
use App\Filament\Resources\LegalActs\Pages\ListLegalActs;
use App\Filament\Resources\LegalActs\Schemas\LegalActForm;
use App\Filament\Resources\LegalActs\Tables\LegalActsTable;
use App\Models\LegalAct;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LegalActResource extends Resource
{
    protected static ?string $model = LegalAct::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;
    protected static string|UnitEnum|null  $navigationGroup = 'Каталоги';
    protected static ?string               $navigationLabel = 'Законодательство';
    protected static ?string               $modelLabel      = 'Законодательный акт';
    protected static ?string               $pluralModelLabel = 'Законодательство';
    protected static ?int                  $navigationSort  = 15;

    public static function form(Schema $schema): Schema
    {
        return LegalActForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LegalActsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLegalActs::route('/'),
            'create' => CreateLegalAct::route('/create'),
            'edit'   => EditLegalAct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            \Illuminate\Database\Eloquent\SoftDeletingScope::class,
        ]);
    }
}
