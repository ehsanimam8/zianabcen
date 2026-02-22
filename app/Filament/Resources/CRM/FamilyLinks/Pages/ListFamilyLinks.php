<?php

namespace App\Filament\Resources\CRM\FamilyLinks\Pages;

use App\Filament\Resources\CRM\FamilyLinks\FamilyLinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFamilyLinks extends ListRecords
{
    protected static string $resource = FamilyLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
