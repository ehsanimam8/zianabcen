<?php

namespace App\Filament\Resources\CMS\EventRegistrations;

use App\Filament\Resources\CMS\EventRegistrations\Pages\CreateEventRegistration;
use App\Filament\Resources\CMS\EventRegistrations\Pages\EditEventRegistration;
use App\Filament\Resources\CMS\EventRegistrations\Pages\ListEventRegistrations;
use App\Filament\Resources\CMS\EventRegistrations\Schemas\EventRegistrationForm;
use App\Filament\Resources\CMS\EventRegistrations\Tables\EventRegistrationsTable;
use App\Models\CMS\EventRegistration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EventRegistrationResource extends Resource
{
    protected static ?string $model = EventRegistration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return 'Content Management (CMS)';
    }


    

    public static function form(Schema $schema): Schema
    {
        return EventRegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventRegistrationsTable::configure($table);
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
            'index' => ListEventRegistrations::route('/'),
            'create' => CreateEventRegistration::route('/create'),
            'edit' => EditEventRegistration::route('/{record}/edit'),
        ];
    }
}
