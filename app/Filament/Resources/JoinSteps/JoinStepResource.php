<?php

namespace App\Filament\Resources\JoinSteps;

use App\Filament\Resources\JoinSteps\Pages\CreateJoinStep;
use App\Filament\Resources\JoinSteps\Pages\EditJoinStep;
use App\Filament\Resources\JoinSteps\Pages\ListJoinSteps;
use App\Filament\Resources\JoinSteps\Schemas\JoinStepForm;
use App\Filament\Resources\JoinSteps\Tables\JoinStepsTable;
use App\Models\JoinStep;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JoinStepResource extends Resource
{
    protected static ?string $model = JoinStep::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static string|UnitEnum|null $navigationGroup = 'Контент';

    protected static ?string $navigationLabel = 'Шаги вступления';

    protected static ?string $modelLabel = 'Шаг';

    protected static ?string $pluralModelLabel = 'Шаги вступления';

    protected static ?int $navigationSort = 110;

    public static function form(Schema $schema): Schema
    {
        return JoinStepForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JoinStepsTable::configure($table);
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
            'index' => ListJoinSteps::route('/'),
            'create' => CreateJoinStep::route('/create'),
            'edit' => EditJoinStep::route('/{record}/edit'),
        ];
    }
}
