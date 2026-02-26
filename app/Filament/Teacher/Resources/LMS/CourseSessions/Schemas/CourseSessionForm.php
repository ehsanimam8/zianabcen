<?php

namespace App\Filament\Teacher\Resources\LMS\CourseSessions\Schemas;

use Filament\Schemas\Schema;

class CourseSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('course_id')
                    ->relationship('course', 'name')
                    ->required()
                    ->searchable(),
                \Filament\Forms\Components\Select::make('instructor_user_id')
                    ->relationship('instructor', 'name')
                    ->default(fn() => auth()->id())
                    ->searchable(),
                \Filament\Forms\Components\DatePicker::make('session_date')
                    ->required(),
                \Filament\Forms\Components\TimePicker::make('session_start_time'),
                \Filament\Forms\Components\TimePicker::make('session_end_time'),
                \Filament\Forms\Components\TextInput::make('timezone')->default('UTC'),
                \Filament\Forms\Components\TextInput::make('platform'),
                \Filament\Forms\Components\TextInput::make('meeting_url')->url(),
                \Filament\Forms\Components\Toggle::make('is_cancelled')->default(false),
            ]);
    }
}
