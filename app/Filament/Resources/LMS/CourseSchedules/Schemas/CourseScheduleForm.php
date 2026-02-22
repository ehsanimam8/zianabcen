<?php

namespace App\Filament\Resources\LMS\CourseSchedules\Schemas;

use Filament\Schemas\Schema;

class CourseScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Schedule Info')->schema([
                    \Filament\Forms\Components\Select::make('course_id')
                        ->relationship('course', 'name')
                        ->required(),
                    \Filament\Forms\Components\Select::make('pattern_type')->options([
                        'weekly_recurring' => 'Weekly Recurring',
                        'one_time' => 'One Time',
                    ])->required()->live(),
                    \Filament\Forms\Components\Select::make('timezone')
                        ->searchable()
                        ->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list()))
                        ->required()
                        ->default('UTC'),
                    \Filament\Forms\Components\DatePicker::make('schedule_start_date')
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('schedule_end_date')
                        ->required(),
                ])->columns(2),

                \Filament\Schemas\Components\Section::make('Weekly Recurring Configuration')
                    ->schema([
                        \Filament\Forms\Components\Select::make('pattern_config.days_of_week')
                            ->label('Days of the Week')
                            ->multiple()
                            ->options([
                                '1' => 'Monday',
                                '2' => 'Tuesday',
                                '3' => 'Wednesday',
                                '4' => 'Thursday',
                                '5' => 'Friday',
                                '6' => 'Saturday',
                                '0' => 'Sunday',
                            ])->required(),
                        \Filament\Forms\Components\TimePicker::make('pattern_config.start_time')
                            ->label('Session Start Time')
                            ->required(),
                        \Filament\Forms\Components\TimePicker::make('pattern_config.end_time')
                            ->label('Session End Time')
                            ->required(),
                        \Filament\Forms\Components\Select::make('pattern_config.instructor_user_id')
                            ->label('Instructor')
                            ->options(\App\Models\User::pluck('name', 'id'))
                            ->searchable(),
                    ])
                    ->visible(fn ($get) => $get('pattern_type') === 'weekly_recurring')
                    ->columns(2),
            ]);
    }
}
