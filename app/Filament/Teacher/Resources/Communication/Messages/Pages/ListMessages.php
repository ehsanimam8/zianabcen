<?php

namespace App\Filament\Teacher\Resources\Communication\Messages\Pages;

use App\Filament\Teacher\Resources\Communication\Messages\MessageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMessages extends ListRecords
{
    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
