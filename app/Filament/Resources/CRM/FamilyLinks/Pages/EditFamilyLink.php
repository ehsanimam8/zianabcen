<?php

namespace App\Filament\Resources\CRM\FamilyLinks\Pages;

use App\Filament\Resources\CRM\FamilyLinks\FamilyLinkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFamilyLink extends EditRecord
{
    protected static string $resource = FamilyLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
