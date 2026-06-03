<?php

namespace App\Filament\Resources\PainSolutionRows\Pages;

use App\Filament\Resources\PainSolutionRows\PainSolutionRowResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPainSolutionRow extends EditRecord
{
    protected static string $resource = PainSolutionRowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
