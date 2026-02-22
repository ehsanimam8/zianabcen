<?php

namespace App\Filament\Resources\LMS\Attendances\Schemas;

use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Attendance Info')->schema([
                    \Filament\Forms\Components\Select::make('course_session_id')->relationship('session', 'id')->required(),
                    \Filament\Forms\Components\Select::make('student_user_id')->relationship('student', 'name')->required(),
                    \Filament\Forms\Components\Select::make('status')->options([
                        'Present' => 'Present',
                        'Absent' => 'Absent',
                        'Excused' => 'Excused',
                        'Late' => 'Late',
                    ])->required()->default('Present'),
                    \Filament\Forms\Components\Textarea::make('notes')->columnSpanFull(),
                    \Filament\Forms\Components\DateTimePicker::make('marked_at')->default(now())->required(),
                    \Filament\Forms\Components\Select::make('marked_by_user_id')->relationship('marker', 'name')->required(),
                ])->columns(2),
            ]);
    }
}
