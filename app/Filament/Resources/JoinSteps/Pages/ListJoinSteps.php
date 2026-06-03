<?php

namespace App\Filament\Resources\JoinSteps\Pages;

use App\Filament\Resources\JoinSteps\JoinStepResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJoinSteps extends ListRecords
{
    protected static string $resource = JoinStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
