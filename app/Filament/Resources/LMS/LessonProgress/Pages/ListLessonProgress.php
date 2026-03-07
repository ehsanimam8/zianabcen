<?php

namespace App\Filament\Resources\LMS\LessonProgress\Pages;

use App\Filament\Resources\LMS\LessonProgress\LessonProgressResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListLessonProgress extends ListRecords
{
    protected static string $resource = LessonProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
