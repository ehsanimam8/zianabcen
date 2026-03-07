<?php

namespace App\Filament\Resources\LMS\Assessments;

use App\Filament\Resources\LMS\Assessments\Pages\CreateAssessment;
use App\Filament\Resources\LMS\Assessments\Pages\EditAssessment;
use App\Filament\Resources\LMS\Assessments\Pages\ListAssessments;
use App\Filament\Resources\LMS\Assessments\Schemas\AssessmentForm;
use App\Filament\Resources\LMS\Assessments\Tables\AssessmentsTable;
use App\Models\LMS\Assessment;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\LMS\Assessments\RelationManagers;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    public static function getNavigationGroup(): ?string
    {
        return 'LMS';
    }

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::OutlinedClipboardDocumentCheck;
    }

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

    public static function getPages(): array
    {
        return [
            'index' => ListAssessments::route('/'),
            'create' => CreateAssessment::route('/create'),
            'edit' => EditAssessment::route('/{record}/edit'),
        ];
    }
}
