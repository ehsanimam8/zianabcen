<?php

namespace App\Filament\Resources\SIS\CourseAccesses\Schemas;

use Filament\Schemas\Schema;

class CourseAccessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('enrollment_id')->relationship('enrollment', 'id')->required()->label('Enrollment ID'),
                \Filament\Forms\Components\Select::make('course_id')->relationship('course', 'name')->required()->label('Course'),
                \Filament\Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }
}
