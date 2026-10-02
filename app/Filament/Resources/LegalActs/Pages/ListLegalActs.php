<?php

namespace App\Filament\Resources\LegalActs\Pages;

use App\Filament\Concerns\HasJsonBackup;
use App\Filament\Resources\LegalActs\LegalActResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLegalActs extends ListRecords
{
    use HasJsonBackup;

    protected static string $resource = LegalActResource::class;

    protected function getHeaderActions(): array
    {
        return array_merge([
            CreateAction::make(),
        ], $this->getBackupActions());
    }
}
