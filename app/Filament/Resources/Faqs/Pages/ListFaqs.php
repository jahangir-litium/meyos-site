<?php

namespace App\Filament\Resources\Faqs\Pages;

use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Concerns\HasJsonBackup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFaqs extends ListRecords
{
    use HasJsonBackup;
    protected function backupStrategy(): string { return 'replace'; }
    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return array_merge([
            CreateAction::make(),
        ], $this->getBackupActions());
    }
}
