<?php

namespace App\Filament\Resources\LMS\CourseSessions\Pages;

use App\Filament\Resources\LMS\CourseSessions\CourseSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCourseSession extends EditRecord
{
    protected static string $resource = CourseSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
