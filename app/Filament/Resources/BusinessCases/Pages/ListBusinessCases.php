<?php

namespace App\Filament\Resources\BusinessCases\Pages;

use App\Filament\Resources\BusinessCases\BusinessCaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBusinessCases extends ListRecords
{
    protected static string $resource = BusinessCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
