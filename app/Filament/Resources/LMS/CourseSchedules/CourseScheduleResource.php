<?php

namespace App\Filament\Resources\LMS\CourseSchedules;

use App\Filament\Resources\LMS\CourseSchedules\Pages\CreateCourseSchedule;
use App\Filament\Resources\LMS\CourseSchedules\Pages\EditCourseSchedule;
use App\Filament\Resources\LMS\CourseSchedules\Pages\ListCourseSchedules;
use App\Filament\Resources\LMS\CourseSchedules\Schemas\CourseScheduleForm;
use App\Filament\Resources\LMS\CourseSchedules\Tables\CourseSchedulesTable;
use App\Models\LMS\CourseSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CourseScheduleResource extends Resource
{
    protected static ?string $model = CourseSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return 'Learning Management (LMS)';
    }


    

    public static function form(Schema $schema): Schema
    {
        return CourseScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseSchedulesTable::configure($table);
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
            'index' => ListCourseSchedules::route('/'),
            'create' => CreateCourseSchedule::route('/create'),
            'edit' => EditCourseSchedule::route('/{record}/edit'),
        ];
    }
}
