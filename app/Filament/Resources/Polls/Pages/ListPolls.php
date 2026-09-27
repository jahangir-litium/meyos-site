<?php

namespace App\Filament\Resources\Polls\Pages;

use App\Filament\Resources\Polls\PollResource;
use App\Filament\Concerns\HasJsonBackup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPolls extends ListRecords
{
    use HasJsonBackup;
    protected static string $resource = PollResource::class;

    protected function getHeaderActions(): array
    {
        return array_merge([
            CreateAction::make(),
        ], $this->getBackupActions());
    }
}
