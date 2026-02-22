<?php

namespace App\Filament\Resources\CMS\Events;

use App\Filament\Resources\CMS\Events\Pages\CreateEvent;
use App\Filament\Resources\CMS\Events\Pages\EditEvent;
use App\Filament\Resources\CMS\Events\Pages\ListEvents;
use App\Filament\Resources\CMS\Events\Schemas\EventForm;
use App\Filament\Resources\CMS\Events\Tables\EventsTable;
use App\Models\CMS\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return 'Content Management (CMS)';
    }


    

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
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
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
