<?php

namespace App\Filament\Resources\TaxRows\Pages;

use App\Filament\Resources\TaxRows\TaxRowResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTaxRow extends EditRecord
{
    protected static string $resource = TaxRowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
