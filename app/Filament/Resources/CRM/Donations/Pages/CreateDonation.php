<?php

namespace App\Filament\Resources\CRM\Donations\Pages;

use App\Filament\Resources\CRM\Donations\DonationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDonation extends CreateRecord
{
    protected static string $resource = DonationResource::class;
}
