<?php

namespace App\Filament\Resources\SIS\AcademicYears;

use App\Filament\Resources\SIS\AcademicYears\Pages\CreateAcademicYear;
use App\Filament\Resources\SIS\AcademicYears\Pages\EditAcademicYear;
use App\Filament\Resources\SIS\AcademicYears\Pages\ListAcademicYears;
use App\Filament\Resources\SIS\AcademicYears\Schemas\AcademicYearForm;
use App\Filament\Resources\SIS\AcademicYears\Tables\AcademicYearsTable;
use App\Models\SIS\AcademicYear;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AcademicYearResource extends Resource
{
    protected static ?string $model = AcademicYear::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return 'Student Information (SIS)';
    }


    

    public static function form(Schema $schema): Schema
    {
        return AcademicYearForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcademicYearsTable::configure($table);
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
            'index' => ListAcademicYears::route('/'),
            'create' => CreateAcademicYear::route('/create'),
            'edit' => EditAcademicYear::route('/{record}/edit'),
        ];
    }
}
