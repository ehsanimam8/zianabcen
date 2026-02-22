<?php

namespace App\Filament\Resources\LMS\CourseAnnouncements\Pages;

use App\Filament\Resources\LMS\CourseAnnouncements\CourseAnnouncementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCourseAnnouncement extends EditRecord
{
    protected static string $resource = CourseAnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
