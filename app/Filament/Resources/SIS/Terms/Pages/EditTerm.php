<?php

namespace App\Filament\Resources\SIS\Terms\Pages;

use App\Filament\Resources\SIS\Terms\TermResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTerm extends EditRecord
{
    protected static string $resource = TermResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
