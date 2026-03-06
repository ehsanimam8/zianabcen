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
                    ->label('Lesson / Session')
                    ->relationship('session', 'session_date', modifyQueryUsing: fn ($query) => 
                        $query->where('instructor_user_id', auth()->id())
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->session_date->format('M d, Y')} — {$record->topic}")
                    ->required()
                    ->live(),

                \Filament\Forms\Components\Select::make('student_user_id')
                    ->label('Student')
                    ->options(function (callable $get) {
                        $sessionId = $get('course_session_id');
                        if (!$sessionId) return [];
                        
                        $session = \App\Models\LMS\CourseSession::find($sessionId);
                        if (!$session) return [];

                        return \App\Models\User::role('Student')
                            ->whereHas('enrollments', function ($q) use ($session) {
                                $q->active()->where('course_id', $session->course_id);
                            })
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),

                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'late' => 'Late',
                        'excused' => 'Excused',
                    ])
                    ->default('present')
                    ->required(),
                \Filament\Forms\Components\Textarea::make('notes'),
            ]);
    }
}
