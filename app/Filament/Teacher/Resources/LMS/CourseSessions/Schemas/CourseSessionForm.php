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
                    ->label('Course')
                    ->options(fn () => 
                        \App\Models\SIS\Course::whereIn('id', \App\Models\LMS\CourseSession::where('instructor_user_id', auth()->id())->pluck('course_id'))
                        ->pluck('name', 'id')
                    )
                    ->required()
                    ->searchable(),
                \Filament\Forms\Components\TextInput::make('topic')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Hidden::make('instructor_user_id')
                    ->default(fn() => auth()->id()),
                \Filament\Forms\Components\DatePicker::make('session_date')
                    ->required(),
                \Filament\Forms\Components\TimePicker::make('session_start_time'),
                \Filament\Forms\Components\TimePicker::make('session_end_time'),
                \Filament\Forms\Components\TextInput::make('timezone')
                    ->default(auth()->user()->timezone ?? 'UTC'),
                \Filament\Forms\Components\TextInput::make('platform')
                    ->default('Zoom'),
                \Filament\Forms\Components\TextInput::make('meeting_url')->url(),
                \Filament\Forms\Components\Toggle::make('is_cancelled')->default(false),
            ]);
    }
}
