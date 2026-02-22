<?php

namespace App\Filament\Resources\CMS\EventRegistrations\Pages;

use App\Filament\Resources\CMS\EventRegistrations\EventRegistrationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEventRegistration extends CreateRecord
{
    protected static string $resource = EventRegistrationResource::class;
}
