<?php

namespace App\Filament\Resources\LMS\Lessons\Pages;

use App\Filament\Resources\LMS\Lessons\LessonResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLesson extends CreateRecord
{
    protected static string $resource = LessonResource::class;
}
