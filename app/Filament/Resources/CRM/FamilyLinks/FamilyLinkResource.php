<?php

namespace App\Filament\Resources\CRM\FamilyLinks;

use App\Filament\Resources\CRM\FamilyLinks\Pages\CreateFamilyLink;
use App\Filament\Resources\CRM\FamilyLinks\Pages\EditFamilyLink;
use App\Filament\Resources\CRM\FamilyLinks\Pages\ListFamilyLinks;
use App\Filament\Resources\CRM\FamilyLinks\Schemas\FamilyLinkForm;
use App\Filament\Resources\CRM\FamilyLinks\Tables\FamilyLinksTable;
use App\Models\CRM\FamilyLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FamilyLinkResource extends Resource
{
    protected static ?string $model = FamilyLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FamilyLinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FamilyLinksTable::configure($table);
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
            'index' => ListFamilyLinks::route('/'),
            'create' => CreateFamilyLink::route('/create'),
            'edit' => EditFamilyLink::route('/{record}/edit'),
        ];
    }
}
