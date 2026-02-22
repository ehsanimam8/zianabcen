<?php

namespace App\Filament\Teacher\Resources\LMS\Attendances;

use App\Filament\Teacher\Resources\LMS\Attendances\Pages\CreateAttendance;
use App\Filament\Teacher\Resources\LMS\Attendances\Pages\EditAttendance;
use App\Filament\Teacher\Resources\LMS\Attendances\Pages\ListAttendances;
use App\Filament\Teacher\Resources\LMS\Attendances\Schemas\AttendanceForm;
use App\Filament\Teacher\Resources\LMS\Attendances\Tables\AttendancesTable;
use App\Models\LMS\Attendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return AttendanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendancesTable::configure($table);
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
            'index' => ListAttendances::route('/'),
            'create' => CreateAttendance::route('/create'),
            'edit' => EditAttendance::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('session', function ($query) {
            $query->where('instructor_user_id', auth()->id());
        });
    }
}
