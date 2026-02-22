<?php

namespace App\Filament\Resources\CMS\Events\Schemas;

use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Event Details')->schema([
                    \Filament\Forms\Components\Select::make('post_id')
                        ->relationship('post', 'title')
                        ->required()
                        ->label('Associated Content/Post'),
                    \Filament\Forms\Components\DateTimePicker::make('event_start')
                        ->required(),
                    \Filament\Forms\Components\DateTimePicker::make('event_end')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('location')
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('meeting_url')
                        ->url()
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('capacity')
                        ->numeric(),
                    \Filament\Forms\Components\Select::make('status')
                        ->options([
                            'upcoming' => 'Upcoming',
                            'ongoing' => 'Ongoing',
                            'past' => 'Past',
                        ])
                        ->required()
                        ->default('upcoming'),
                ])->columns(2),
            ]);
    }
}
