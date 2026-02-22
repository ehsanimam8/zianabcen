<?php

namespace App\Filament\Resources\LMS\CourseAnnouncements;

use App\Filament\Resources\LMS\CourseAnnouncements\Pages\CreateCourseAnnouncement;
use App\Filament\Resources\LMS\CourseAnnouncements\Pages\EditCourseAnnouncement;
use App\Filament\Resources\LMS\CourseAnnouncements\Pages\ListCourseAnnouncements;
use App\Filament\Resources\LMS\CourseAnnouncements\Schemas\CourseAnnouncementForm;
use App\Filament\Resources\LMS\CourseAnnouncements\Tables\CourseAnnouncementsTable;
use App\Models\LMS\CourseAnnouncement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CourseAnnouncementResource extends Resource
{
    protected static ?string $model = CourseAnnouncement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return 'Learning Management (LMS)';
    }


    

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return CourseAnnouncementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseAnnouncementsTable::configure($table);
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
            'index' => ListCourseAnnouncements::route('/'),
            'create' => CreateCourseAnnouncement::route('/create'),
            'edit' => EditCourseAnnouncement::route('/{record}/edit'),
        ];
    }
}
