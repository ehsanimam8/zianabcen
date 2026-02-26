<?php

namespace App\Filament\Teacher\Resources\LMS\Attendances\Schemas;

use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('course_session_id')
                    ->relationship('courseSession', 'session_date')
                    ->required(),
                \Filament\Forms\Components\Select::make('student_user_id')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->required(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'Present' => 'Present',
                        'Absent' => 'Absent',
                        'Late' => 'Late',
                        'Excused' => 'Excused',
                    ])
                    ->required(),
                \Filament\Forms\Components\Textarea::make('notes'),
            ]);
    }
}
