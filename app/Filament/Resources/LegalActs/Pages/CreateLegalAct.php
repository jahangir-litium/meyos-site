<?php

namespace App\Filament\Resources\LegalActs\Pages;

use App\Filament\Resources\LegalActs\LegalActResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLegalAct extends CreateRecord
{
    protected static string $resource = LegalActResource::class;
}
