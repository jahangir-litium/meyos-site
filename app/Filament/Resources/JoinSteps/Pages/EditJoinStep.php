<?php

namespace App\Filament\Resources\JoinSteps\Pages;

use App\Filament\Resources\JoinSteps\JoinStepResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJoinStep extends EditRecord
{
    protected static string $resource = JoinStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
