<?php

namespace App\Filament\Resources\LMS\CourseSchedules\Pages;

use App\Filament\Resources\LMS\CourseSchedules\CourseScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourseSchedules extends ListRecords
{
    protected static string $resource = CourseScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
