<?php

namespace App\Filament\Teacher\Resources\LMS\Assessments;

use App\Filament\Teacher\Resources\LMS\Assessments\Pages\CreateAssessment;
use App\Filament\Teacher\Resources\LMS\Assessments\Pages\EditAssessment;
use App\Filament\Teacher\Resources\LMS\Assessments\Pages\ListAssessments;
use App\Filament\Teacher\Resources\LMS\Assessments\Pages\ViewSubmission;
use App\Filament\Teacher\Resources\LMS\Assessments\Schemas\AssessmentForm;
use App\Filament\Teacher\Resources\LMS\Assessments\Tables\AssessmentsTable;
use App\Models\LMS\Assessment;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Teacher\Resources\LMS\Assessments\RelationManagers;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static string|UnitEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AssessmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssessmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SubmissionsRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereIn('course_id', function ($query) {
            $query->select('course_id')
                ->from('course_sessions')
                ->where('instructor_user_id', auth()->id());
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssessments::route('/'),
            'create' => CreateAssessment::route('/create'),
            'edit' => EditAssessment::route('/{record}/edit'),
            'view-submission' => ViewSubmission::route('/{record}/submissions/{submission}'),
        ];
    }
}
