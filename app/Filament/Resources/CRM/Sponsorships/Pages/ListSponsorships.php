<?php

namespace App\Filament\Resources\CRM\Sponsorships\Pages;

use App\Filament\Resources\CRM\Sponsorships\SponsorshipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSponsorships extends ListRecords
{
    protected static string $resource = SponsorshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
