<?php

namespace App\Filament\Teacher\Resources\LMS\Grades;

use App\Filament\Teacher\Resources\LMS\Grades\Pages\CreateGrade;
use App\Filament\Teacher\Resources\LMS\Grades\Pages\EditGrade;
use App\Filament\Teacher\Resources\LMS\Grades\Pages\ListGrades;
use App\Filament\Teacher\Resources\LMS\Grades\Schemas\GradeForm;
use App\Filament\Teacher\Resources\LMS\Grades\Tables\GradesTable;
use App\Models\LMS\Grade;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GradeResource extends Resource
{
    protected static ?string $model = Grade::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return GradeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GradesTable::configure($table);
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
            'index' => ListGrades::route('/'),
            'create' => CreateGrade::route('/create'),
            'edit' => EditGrade::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('recorded_by_user_id', auth()->id());
    }
}
