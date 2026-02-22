<?php

namespace App\Filament\Resources\CRM\Contacts\Pages;

use App\Filament\Resources\CRM\Contacts\ContactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;
}
