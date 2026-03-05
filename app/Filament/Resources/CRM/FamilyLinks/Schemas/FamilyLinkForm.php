<?php

namespace App\Filament\Resources\CRM\FamilyLinks\Schemas;

use Filament\Schemas\Schema;

class FamilyLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('parent_user_id')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')->required(),
                        \Filament\Forms\Components\TextInput::make('email')->email()->required()->unique('users', 'email'),
                        \Filament\Forms\Components\TextInput::make('password')->password()->required(),
                    ]),
                \Filament\Forms\Components\Select::make('student_user_id')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')->required(),
                        \Filament\Forms\Components\TextInput::make('email')->email()->required()->unique('users', 'email'),
                        \Filament\Forms\Components\TextInput::make('password')->password()->required(),
                    ]),
                \Filament\Forms\Components\Select::make('relationship')
                    ->options([
                        'father' => 'Father',
                        'mother' => 'Mother',
                        'guardian' => 'Guardian',
                        'other' => 'Other',
                    ])
                    ->required(),
            ]);
    }
}
