<?php

namespace App\Filament\Resources\SIS\CourseAccesses;

use App\Filament\Resources\SIS\CourseAccesses\Pages\CreateCourseAccess;
use App\Filament\Resources\SIS\CourseAccesses\Pages\EditCourseAccess;
use App\Filament\Resources\SIS\CourseAccesses\Pages\ListCourseAccesses;
use App\Filament\Resources\SIS\CourseAccesses\Schemas\CourseAccessForm;
use App\Filament\Resources\SIS\CourseAccesses\Tables\CourseAccessesTable;
use App\Models\SIS\CourseAccess;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CourseAccessResource extends Resource
{
    protected static ?string $model = CourseAccess::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return 'Student Information (SIS)';
    }


    

    public static function form(Schema $schema): Schema
    {
        return CourseAccessForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseAccessesTable::configure($table);
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
            'index' => ListCourseAccesses::route('/'),
            'create' => CreateCourseAccess::route('/create'),
            'edit' => EditCourseAccess::route('/{record}/edit'),
        ];
    }
}
