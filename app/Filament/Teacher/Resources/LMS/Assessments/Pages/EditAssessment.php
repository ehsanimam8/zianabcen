<?php

namespace App\Filament\Teacher\Resources\LMS\Assessments\Pages;

use App\Filament\Teacher\Resources\LMS\Assessments\AssessmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssessment extends EditRecord
{
    protected static string $resource = AssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
