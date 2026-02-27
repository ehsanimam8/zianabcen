<?php

namespace App\Filament\Resources\CRM\Contacts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->nullable(),
                \Filament\Forms\Components\Select::make('contact_type')
                    ->options([
                        'General' => 'General',
                        'Student' => 'Student',
                        'Parent' => 'Parent',
                        'Event Registrant' => 'Event Registrant',
                        'Donor' => 'Donor',
                    ])
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('address')
                    ->columnSpanFull(),
                \Filament\Forms\Components\KeyValue::make('custom_fields'),
            ]);
    }
}
