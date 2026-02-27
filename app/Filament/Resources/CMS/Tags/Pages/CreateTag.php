<?php

namespace App\Filament\Resources\CMS\Tags\Pages;

use App\Filament\Resources\CMS\Tags\TagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTag extends CreateRecord
{
    protected static string $resource = TagResource::class;
}
