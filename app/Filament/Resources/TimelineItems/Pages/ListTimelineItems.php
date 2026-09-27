<?php

namespace App\Filament\Resources\TimelineItems\Pages;

use App\Filament\Resources\TimelineItems\TimelineItemResource;
use App\Filament\Concerns\HasJsonBackup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTimelineItems extends ListRecords
{
    use HasJsonBackup;
    protected function backupStrategy(): string { return 'replace'; }
    protected static string $resource = TimelineItemResource::class;

    protected function getHeaderActions(): array
    {
        return array_merge([
            CreateAction::make(),
        ], $this->getBackupActions());
    }
}
