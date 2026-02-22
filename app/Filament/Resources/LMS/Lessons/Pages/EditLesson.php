<?php

namespace App\Filament\Resources\LMS\Lessons\Pages;

use App\Filament\Resources\LMS\Lessons\LessonResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLesson extends EditRecord
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
