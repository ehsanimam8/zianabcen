<?php

namespace App\Filament\Resources\CMS\Events\Pages;

use App\Filament\Resources\CMS\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
}
