<?php

namespace App\Filament\Resources\SIS\CourseAccesses\Pages;

use App\Filament\Resources\SIS\CourseAccesses\CourseAccessResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCourseAccess extends EditRecord
{
    protected static string $resource = CourseAccessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
