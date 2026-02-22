<?php

namespace App\Filament\Resources\CRM\Contacts\Schemas;

use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Contact Info')->schema([
                    \Filament\Forms\Components\Select::make('user_id')->relationship('user', 'name'),
                    \Filament\Forms\Components\Select::make('contact_type')->options([
                        'Student' => 'Student',
                        'Parent' => 'Parent',
                        'Donor' => 'Donor',
                        'Sponsor' => 'Sponsor',
                        'Event Registrant' => 'Event Registrant',
                        'General' => 'General',
                    ])->required()->default('General'),
                    \Filament\Forms\Components\TextInput::make('name')->required()->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('email')->email()->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('phone')->tel()->maxLength(255),
                    \Filament\Forms\Components\Textarea::make('address')->columnSpanFull(),
                    \Filament\Forms\Components\KeyValue::make('custom_fields')->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
