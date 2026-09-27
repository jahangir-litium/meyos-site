<?php

namespace App\Filament\Resources\Certifications\Pages;

use App\Filament\Resources\Certifications\CertificationResource;
use App\Filament\Concerns\HasJsonBackup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCertifications extends ListRecords
{
    use HasJsonBackup;
    protected function backupStrategy(): string { return 'replace'; }
    protected static string $resource = CertificationResource::class;

    protected function getHeaderActions(): array
    {
        return array_merge([
            CreateAction::make(),
        ], $this->getBackupActions());
    }
}
