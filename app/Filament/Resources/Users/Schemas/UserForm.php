<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Basic Details')->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                    TextInput::make('phone')->tel(),
                    \Filament\Forms\Components\Select::make('gender')->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ]),
                    \Filament\Forms\Components\Textarea::make('address')->columnSpanFull(),
                    \Filament\Forms\Components\Select::make('timezone')
                        ->searchable()
                        ->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list())),
                ])->columns(2),

                \Filament\Schemas\Components\Section::make('Education Background')->schema([
                    \Filament\Forms\Components\Select::make('previous_secular_education')
                        ->options([
                            'high_school' => 'High School',
                            'bachelors' => 'Bachelors Degree',
                            'masters' => 'Masters Degree',
                            'phd' => 'Ph.D.',
                            'other' => 'Other',
                        ]),
                    \Filament\Forms\Components\Select::make('previous_religious_education')
                        ->options([
                            'none' => 'None',
                            'basic' => 'Basic Islamic Knowledge',
                            'intermediate' => 'Intermediate/Local Courses',
                            'advanced' => 'Advanced/Aalimiyyah student',
                            'scholar' => 'Graduate/Scholar',
                        ]),
                ])->columns(2),

                \Filament\Schemas\Components\Section::make('System Info')->schema([
                    TextInput::make('roll_number')->unique(ignoreRecord: true),
                    TextInput::make('stripe_customer_id')->disabled(),
                    TextInput::make('password')
                        ->password()
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create'),
                ])->columns(2),
            ]);
    }
}
