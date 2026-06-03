<?php

namespace App\Filament\Resources\TimelineItems;

use App\Filament\Resources\TimelineItems\Pages\CreateTimelineItem;
use App\Filament\Resources\TimelineItems\Pages\EditTimelineItem;
use App\Filament\Resources\TimelineItems\Pages\ListTimelineItems;
use App\Filament\Resources\TimelineItems\Schemas\TimelineItemForm;
use App\Filament\Resources\TimelineItems\Tables\TimelineItemsTable;
use App\Models\TimelineItem;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TimelineItemResource extends Resource
{
    protected static ?string $model = TimelineItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'Контент';

    protected static ?string $navigationLabel = 'История ассоциации';

    protected static ?string $modelLabel = 'Точка истории';

    protected static ?string $pluralModelLabel = 'История ассоциации';

    protected static ?int $navigationSort = 70;

    public static function form(Schema $schema): Schema
    {
        return TimelineItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimelineItemsTable::configure($table);
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
            'index' => ListTimelineItems::route('/'),
            'create' => CreateTimelineItem::route('/create'),
            'edit' => EditTimelineItem::route('/{record}/edit'),
        ];
    }
}
