<?php

namespace App\Filament\Resources\CRM\Sponsorships\Pages;

use App\Filament\Resources\CRM\Sponsorships\SponsorshipResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSponsorship extends EditRecord
{
    protected static string $resource = SponsorshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
