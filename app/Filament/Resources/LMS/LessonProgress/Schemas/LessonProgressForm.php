<?php

namespace App\Filament\Resources\LMS\LessonProgress\Schemas;

use Filament\Schemas\Schema;

class LessonProgressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Progress Info')->schema([
                    \Filament\Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->required()
                        ->searchable(),
                    \Filament\Forms\Components\Select::make('lesson_id')
                        ->relationship('lesson', 'title')
                        ->required()
                        ->searchable(),
                    \Filament\Forms\Components\Select::make('status')
                        ->options([
                            'not_started' => 'Not Started',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                        ])
                        ->required()
                        ->default('not_started'),
                    \Filament\Forms\Components\DateTimePicker::make('started_at'),
                    \Filament\Forms\Components\DateTimePicker::make('completed_at'),
                ])->columns(2),
            ]);
    }
}
