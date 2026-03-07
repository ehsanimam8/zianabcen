<?php

namespace App\Filament\Resources\LMS\LessonProgress\Pages;

use App\Filament\Resources\LMS\LessonProgress\LessonProgressResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditLessonProgress extends EditRecord
{
    protected static string $resource = LessonProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
