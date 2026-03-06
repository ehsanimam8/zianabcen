<?php

namespace App\Filament\Teacher\Resources\LMS\Grades\Schemas;

use Filament\Schemas\Schema;

class GradeForm
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
                    ->live()
                    ->searchable(),

                \Filament\Forms\Components\Select::make('enrollment_id')
                    ->label('Student')
                    ->options(function (callable $get) {
                        $courseId = $get('course_id');
                        if (!$courseId) return [];

                        return \App\Models\SIS\Enrollment::active()
                            ->where('course_id', $courseId)
                            ->with('user')
                            ->get()
                            ->pluck('user.name', 'id');
                    })
                    ->required()
                    ->searchable(),

                \Filament\Forms\Components\Select::make('assessment_id')
                    ->label('Assessment (Optional)')
                    ->relationship('assessment', 'title', modifyQueryUsing: fn ($query, callable $get) => 
                        $query->where('course_id', $get('course_id'))
                    )
                    ->searchable()
                    ->nullable(),

                \Filament\Schemas\Components\Grid::make(3)->schema([
                    \Filament\Forms\Components\TextInput::make('percentage')
                        ->numeric()
                        ->suffix('%')
                        ->minValue(0)
                        ->maxValue(100),
                    \Filament\Forms\Components\TextInput::make('letter_grade')
                        ->label('Grade (A, B, C...)')
                        ->maxLength(2),
                    \Filament\Forms\Components\DateTimePicker::make('recorded_at')
                        ->default(now()),
                ]),

                \Filament\Forms\Components\Textarea::make('comments')
                    ->columnSpanFull(),
            ]);
    }
}
