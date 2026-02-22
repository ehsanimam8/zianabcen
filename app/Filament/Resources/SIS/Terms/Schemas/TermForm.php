<?php

namespace App\Filament\Resources\SIS\Terms\Schemas;

use Filament\Schemas\Schema;

class TermForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Term Information')->schema([
                    \Filament\Forms\Components\TextInput::make('name')->required()->maxLength(255),
                    \Filament\Forms\Components\Select::make('academic_year_id')->relationship('academicYear', 'name')->required(),
                    \Filament\Forms\Components\DatePicker::make('start_date')->required(),
                    \Filament\Forms\Components\DatePicker::make('end_date')->required(),
                    \Filament\Forms\Components\Toggle::make('is_current')->default(false)->label('Current Active Term'),
                ])->columns(2),
            ]);
    }
}
