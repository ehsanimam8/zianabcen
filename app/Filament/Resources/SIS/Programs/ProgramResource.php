<?php

namespace App\Filament\Resources\SIS\Programs;

use App\Filament\Resources\SIS\Programs\Pages\CreateProgram;
use App\Filament\Resources\SIS\Programs\Pages\EditProgram;
use App\Filament\Resources\SIS\Programs\Pages\ListPrograms;
use App\Filament\Resources\SIS\Programs\Schemas\ProgramForm;
use App\Filament\Resources\SIS\Programs\Tables\ProgramsTable;
use App\Models\SIS\Program;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return 'Student Information (SIS)';
    }


    

    public static function form(Schema $schema): Schema
    {
        return ProgramForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramsTable::configure($table);
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
            'index' => ListPrograms::route('/'),
            'create' => CreateProgram::route('/create'),
            'edit' => EditProgram::route('/{record}/edit'),
        ];
    }
}
