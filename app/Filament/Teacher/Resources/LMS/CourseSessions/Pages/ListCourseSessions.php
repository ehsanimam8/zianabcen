<?php

namespace App\Filament\Teacher\Resources\LMS\CourseSessions\Pages;

use App\Filament\Teacher\Resources\LMS\CourseSessions\CourseSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourseSessions extends ListRecords
{
    protected static string $resource = CourseSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
