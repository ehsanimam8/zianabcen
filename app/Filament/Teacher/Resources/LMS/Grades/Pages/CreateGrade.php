<?php

namespace App\Filament\Teacher\Resources\LMS\Grades\Pages;

use App\Filament\Teacher\Resources\LMS\Grades\GradeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGrade extends CreateRecord
{
    protected static string $resource = GradeResource::class;
}
