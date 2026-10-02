<?php

namespace App\Filament\Resources\SavdexListings;

use App\Filament\Resources\SavdexListings\Pages\ListSavdexListings;
use App\Filament\Resources\SavdexListings\Tables\SavdexListingsTable;
use App\Models\SavdexListing;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * Объявления из savdex.uz — только просмотр, редактирование флагов
 * (hidden/featured) + кнопка «Обновить сейчас». Содержимое не правим
 * вручную — парсер перезапишет при следующем запуске.
 */
class SavdexListingResource extends Resource
{
    protected static ?string $model = SavdexListing::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;
    protected static string|UnitEnum|null  $navigationGroup = 'Каталоги';
    protected static ?string               $navigationLabel = 'Объявления SAVDEX';
    protected static ?string               $modelLabel      = 'Объявление SAVDEX';
    protected static ?string               $pluralModelLabel = 'Объявления SAVDEX';
    protected static ?int                  $navigationSort  = 25;

    public static function table(Table $table): Table
    {
        return SavdexListingsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSavdexListings::route('/'),
        ];
    }

    /** Запрет создания вручную — только парсер пишет. */
    public static function canCreate(): bool { return false; }
}
