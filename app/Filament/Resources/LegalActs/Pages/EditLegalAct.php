<?php

namespace App\Filament\Resources\LegalActs\Pages;

use App\Filament\Resources\LegalActs\LegalActResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLegalAct extends EditRecord
{
    protected static string $resource = LegalActResource::class;

    public function getFooterWidgetsColumns(): int|array { return 1; }

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
