<?php

namespace App\Filament\Resources\TeamMembers\Pages;

use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Filament\Concerns\HasJsonBackup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeamMembers extends ListRecords
{
    use HasJsonBackup;
    protected function backupStrategy(): string { return 'replace'; }
    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return array_merge([
            CreateAction::make(),
        ], $this->getBackupActions());
    }
}
