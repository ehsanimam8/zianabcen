<?php

namespace App\Filament\Resources\SIS\AcademicYears\Schemas;

use Filament\Schemas\Schema;

class AcademicYearForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Academic Year Details')->schema([
                    \Filament\Forms\Components\TextInput::make('name')->required()->maxLength(255)->placeholder('e.g. 2024-2025'),
                    \Filament\Forms\Components\DatePicker::make('start_date')->required(),
                    \Filament\Forms\Components\DatePicker::make('end_date')->required(),
                    \Filament\Forms\Components\Toggle::make('is_active')->default(true),
                ])->columns(2),
            ]);
    }
}
