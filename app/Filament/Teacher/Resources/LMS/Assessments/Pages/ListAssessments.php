<?php

namespace App\Filament\Teacher\Resources\LMS\Assessments\Pages;

use App\Filament\Teacher\Resources\LMS\Assessments\AssessmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssessments extends ListRecords
{
    protected static string $resource = AssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
