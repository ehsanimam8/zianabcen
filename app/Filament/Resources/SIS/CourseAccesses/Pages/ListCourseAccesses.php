<?php

namespace App\Filament\Resources\SIS\CourseAccesses\Pages;

use App\Filament\Resources\SIS\CourseAccesses\CourseAccessResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourseAccesses extends ListRecords
{
    protected static string $resource = CourseAccessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
