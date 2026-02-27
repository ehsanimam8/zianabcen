<?php

namespace App\Filament\Teacher\Resources\LMS\Assessments;

use App\Filament\Teacher\Resources\LMS\Assessments\Pages\CreateAssessment;
use App\Filament\Teacher\Resources\LMS\Assessments\Pages\EditAssessment;
use App\Filament\Teacher\Resources\LMS\Assessments\Pages\ListAssessments;
use App\Filament\Teacher\Resources\LMS\Assessments\Schemas\AssessmentForm;
use App\Filament\Teacher\Resources\LMS\Assessments\Tables\AssessmentsTable;
use App\Models\LMS\Assessment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssessments::route('/'),
            'create' => CreateAssessment::route('/create'),
            'edit' => EditAssessment::route('/{record}/edit'),
        ];
    }
}
