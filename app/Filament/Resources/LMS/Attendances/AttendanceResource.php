<?php

namespace App\Filament\Resources\LMS\Attendances;

use App\Filament\Resources\LMS\Attendances\Pages\CreateAttendance;
use App\Filament\Resources\LMS\Attendances\Pages\EditAttendance;
use App\Filament\Resources\LMS\Attendances\Pages\ListAttendances;
use App\Filament\Resources\LMS\Attendances\Schemas\AttendanceForm;
use App\Filament\Resources\LMS\Attendances\Tables\AttendancesTable;
use App\Models\LMS\Attendance;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|UnitEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return 'LMS';
    }


    

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
}
