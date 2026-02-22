<?php

namespace App\Filament\Resources\LMS\Assessments\Schemas;

use Filament\Schemas\Schema;

class AssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Assessment Info')->schema([
                    \Filament\Forms\Components\Select::make('course_id')
                        ->relationship('course', 'name')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\Select::make('type')
                        ->options([
                            'quiz' => 'Quiz',
                            'assignment' => 'Assignment',
                            'exam' => 'Exam',
                        ])->required(),
                    \Filament\Forms\Components\DateTimePicker::make('due_date'),
                    \Filament\Forms\Components\TextInput::make('time_limit_minutes')
                        ->numeric()
                        ->label('Time Limit (Minutes)'),
                    \Filament\Forms\Components\TextInput::make('max_attempts')
                        ->numeric()
                        ->default(1)
                        ->required(),
                    \Filament\Forms\Components\Toggle::make('is_published')
                        ->default(false),
                    \Filament\Forms\Components\Textarea::make('description')
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
