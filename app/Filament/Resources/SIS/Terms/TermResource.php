<?php

namespace App\Filament\Resources\SIS\Terms;

use App\Filament\Resources\SIS\Terms\Pages\CreateTerm;
use App\Filament\Resources\SIS\Terms\Pages\EditTerm;
use App\Filament\Resources\SIS\Terms\Pages\ListTerms;
use App\Filament\Resources\SIS\Terms\Schemas\TermForm;
use App\Filament\Resources\SIS\Terms\Tables\TermsTable;
use App\Models\SIS\Term;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TermResource extends Resource
{
    protected static ?string $model = Term::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return 'Student Information (SIS)';
    }


    

    public static function form(Schema $schema): Schema
    {
        return TermForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TermsTable::configure($table);
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
            'index' => ListTerms::route('/'),
            'create' => CreateTerm::route('/create'),
            'edit' => EditTerm::route('/{record}/edit'),
        ];
    }
}
