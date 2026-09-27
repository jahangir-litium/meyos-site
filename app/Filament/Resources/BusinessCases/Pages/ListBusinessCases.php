<?php

namespace App\Filament\Resources\BusinessCases\Pages;

use App\Filament\Resources\BusinessCases\BusinessCaseResource;
use App\Filament\Concerns\HasJsonBackup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBusinessCases extends ListRecords
{
    use HasJsonBackup;
    protected function backupStrategy(): string { return 'replace'; }
    protected static string $resource = BusinessCaseResource::class;

    protected function getHeaderActions(): array
    {
        return array_merge([
            CreateAction::make(),
        ], $this->getBackupActions());
    }
}
