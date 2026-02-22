<?php

namespace App\Filament\Resources\LMS\CourseSessions\Schemas;

use Filament\Schemas\Schema;

class CourseSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Session Info')->schema([
                    \Filament\Forms\Components\Select::make('course_id')->relationship('course', 'name')->required(),
                    \Filament\Forms\Components\Select::make('course_schedule_id')->relationship('schedule', 'id'),
                    \Filament\Forms\Components\Select::make('instructor_user_id')
                        ->relationship('instructor', 'name')
                        ->required()
                        ->rule(fn ($get, ?\Illuminate\Database\Eloquent\Model $record) => function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                            $date = $get('session_date');
                            $startTime = $get('session_start_time');
                            $endTime = $get('session_end_time');

                            if ($date && $startTime && $endTime) {
                                $conflict = \App\Models\LMS\CourseSession::where('instructor_user_id', $value)
                                    ->where('session_date', $date)
                                    ->where(function ($query) use ($startTime, $endTime) {
                                        $query->where(function ($q) use ($startTime, $endTime) {
                                            $q->whereTime('session_start_time', '<', $endTime)
                                              ->whereTime('session_end_time', '>', $startTime);
                                        });
                                    });

                                if ($record) {
                                    $conflict->where('id', '!=', $record->id);
                                }

                                if ($conflict->exists()) {
                                    $fail('Scheduling conflict: This instructor is already teaching another class during this time block.');
                                }
                            }
                        }),
                    \Filament\Forms\Components\DatePicker::make('session_date')->required(),
                    \Filament\Forms\Components\TimePicker::make('session_start_time')->required(),
                    \Filament\Forms\Components\TimePicker::make('session_end_time')->required(),
                    \Filament\Forms\Components\Select::make('timezone')
                        ->searchable()
                        ->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list()))
                        ->required(),
                    \Filament\Forms\Components\Select::make('platform')
                        ->options([
                            'zoom' => 'Zoom',
                            'start_meeting' => 'StartMeeting',
                            'other' => 'Other',
                        ])->required(),
                    \Filament\Forms\Components\TextInput::make('meeting_url')->url()->maxLength(255)->columnSpanFull(),
                    \Filament\Forms\Components\Toggle::make('is_cancelled')->default(false),
                ])->columns(2),
            ]);
    }
}
