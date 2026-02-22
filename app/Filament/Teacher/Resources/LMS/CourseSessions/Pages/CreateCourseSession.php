<?php

namespace App\Filament\Teacher\Resources\LMS\CourseSessions\Pages;

use App\Filament\Teacher\Resources\LMS\CourseSessions\CourseSessionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseSession extends CreateRecord
{
    protected static string $resource = CourseSessionResource::class;
}
