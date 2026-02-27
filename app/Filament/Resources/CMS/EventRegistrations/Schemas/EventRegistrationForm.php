<?php

namespace App\Filament\Resources\CMS\EventRegistrations\Schemas;

use Filament\Schemas\Schema;

class EventRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Registration Details')->schema([
                    \Filament\Forms\Components\Select::make('event_id')
                        ->relationship('event', 'id')
                        ->required(),
                    \Filament\Forms\Components\Select::make('contact_id')
                        ->relationship('contact', 'name')
                        ->required(),
                    \Filament\Forms\Components\Select::make('status')
                        ->options([
                            'registered' => 'Registered',
                            'attended' => 'Attended',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->default('registered'),
                    \Filament\Forms\Components\DateTimePicker::make('registered_at')
                        ->required()
                        ->default(now()),
                    \Filament\Forms\Components\KeyValue::make('custom_fields')
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
