<?php

namespace App\Filament\Resources\LMS\CourseAnnouncements\Pages;

use App\Filament\Resources\LMS\CourseAnnouncements\CourseAnnouncementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourseAnnouncements extends ListRecords
{
    protected static string $resource = CourseAnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
