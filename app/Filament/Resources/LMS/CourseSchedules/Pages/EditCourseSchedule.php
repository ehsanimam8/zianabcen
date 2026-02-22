<?php

namespace App\Filament\Resources\LMS\CourseSchedules\Pages;

use App\Filament\Resources\LMS\CourseSchedules\CourseScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCourseSchedule extends EditRecord
{
    protected static string $resource = CourseScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
