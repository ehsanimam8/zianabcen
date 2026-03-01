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
                    \Filament\Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, \Filament\Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                    \Filament\Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    \Filament\Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('events')
                        ->columnSpanFull(),
                    \Filament\Forms\Components\RichEditor::make('description')
                        ->columnSpanFull(),
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
