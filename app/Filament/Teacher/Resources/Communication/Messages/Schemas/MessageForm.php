<?php

namespace App\Filament\Teacher\Resources\Communication\Messages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Hidden::make('sender_id')
                    ->default(fn() => auth()->id()),
                \Filament\Forms\Components\Select::make('recipient_id')
                    ->relationship('recipient', 'name')
                    ->searchable()
                    ->required()
                    ->label('Recipient'),
                TextInput::make('subject')
                    ->maxLength(255)
                    ->required(),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
