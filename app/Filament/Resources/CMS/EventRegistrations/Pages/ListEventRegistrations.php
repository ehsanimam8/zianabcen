<?php

namespace App\Filament\Resources\CMS\EventRegistrations\Pages;

use App\Filament\Resources\CMS\EventRegistrations\EventRegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEventRegistrations extends ListRecords
{
    protected static string $resource = EventRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
