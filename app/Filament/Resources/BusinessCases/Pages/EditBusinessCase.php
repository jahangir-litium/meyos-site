<?php

namespace App\Filament\Resources\BusinessCases\Pages;

use App\Filament\Resources\BusinessCases\BusinessCaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBusinessCase extends EditRecord
{
    protected static string $resource = BusinessCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
