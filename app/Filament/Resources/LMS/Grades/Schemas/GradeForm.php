<?php

namespace App\Filament\Resources\LMS\Grades\Schemas;

use Filament\Schemas\Schema;

class GradeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Grade Info')->schema([
                    \Filament\Forms\Components\Select::make('enrollment_id')->relationship('enrollment', 'id')->required(),
                    \Filament\Forms\Components\Select::make('course_id')->relationship('course', 'name')->required(),
                    \Filament\Forms\Components\TextInput::make('assessment_id'),
                    \Filament\Forms\Components\TextInput::make('raw_score')->numeric()->required(),
                    \Filament\Forms\Components\TextInput::make('max_score')->numeric()->required(),
                    \Filament\Forms\Components\TextInput::make('percentage')->numeric()->required(),
                    \Filament\Forms\Components\TextInput::make('letter_grade')->maxLength(255),
                    \Filament\Forms\Components\Textarea::make('comments')->columnSpanFull(),
                    \Filament\Forms\Components\DateTimePicker::make('recorded_at')->default(now()),
                    \Filament\Forms\Components\Select::make('recorded_by_user_id')->relationship('recorder', 'name')->required(),
                ])->columns(2),
            ]);
    }
}
