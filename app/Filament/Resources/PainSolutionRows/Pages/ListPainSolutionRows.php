<?php

namespace App\Filament\Resources\PainSolutionRows\Pages;

use App\Filament\Resources\PainSolutionRows\PainSolutionRowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPainSolutionRows extends ListRecords
{
    protected static string $resource = PainSolutionRowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
