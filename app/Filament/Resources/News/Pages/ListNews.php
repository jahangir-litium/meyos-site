<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\News\NewsResource;
use App\Filament\Concerns\HasJsonBackup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNews extends ListRecords
{
    use HasJsonBackup;
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return array_merge([
            CreateAction::make(),
        ], $this->getBackupActions());
    }
}
