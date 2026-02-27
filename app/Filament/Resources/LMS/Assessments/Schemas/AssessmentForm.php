<?php

namespace App\Filament\Resources\LMS\Assessments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('course_id')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('type')
                    ->required(),
                DateTimePicker::make('due_date'),
                TextInput::make('time_limit_minutes')
                    ->numeric(),
                TextInput::make('max_attempts')
                    ->required()
                    ->numeric()
                    ->default(1),
                Toggle::make('is_published')
                    ->required(),
            ]);
    }
}
