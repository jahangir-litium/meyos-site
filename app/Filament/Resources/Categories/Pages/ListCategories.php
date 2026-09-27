<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Concerns\HasJsonBackup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    use HasJsonBackup;
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Создать категорию')
                ->modalHeading('Новая категория')
                ->modalDescription('Используйте для добавления новых разделов в новости / партнёры / мероприятия.'),
        ];
    }
}
