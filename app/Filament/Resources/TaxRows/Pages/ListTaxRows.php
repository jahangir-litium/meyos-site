<?php

namespace App\Filament\Resources\TaxRows\Pages;

use App\Filament\Resources\TaxRows\TaxRowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTaxRows extends ListRecords
{
    protected static string $resource = TaxRowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
