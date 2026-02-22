<?php

namespace App\Filament\Resources\LMS\CourseAnnouncements\Pages;

use App\Filament\Resources\LMS\CourseAnnouncements\CourseAnnouncementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseAnnouncement extends CreateRecord
{
    protected static string $resource = CourseAnnouncementResource::class;
}
