<?php

namespace App\Filament\Teacher\Resources\LMS\Grades\Schemas;

use Filament\Schemas\Schema;

class GradeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('enrollment_id')
                    ->relationship('enrollment', 'id')
                    ->required()
                    ->searchable(),
                \Filament\Forms\Components\Select::make('course_id')
                    ->relationship('course', 'name')
                    ->required()
                    ->searchable(),
                \Filament\Forms\Components\Select::make('assessment_id')
                    ->relationship('assessment', 'title')
                    ->searchable()
                    ->nullable(),
                \Filament\Forms\Components\TextInput::make('raw_score')
                    ->numeric(),
                \Filament\Forms\Components\TextInput::make('max_score')
                    ->numeric(),
                \Filament\Forms\Components\TextInput::make('percentage')
                    ->numeric(),
                \Filament\Forms\Components\TextInput::make('letter_grade')
                    ->maxLength(255),
                \Filament\Forms\Components\Textarea::make('comments'),
            ]);
    }
}
