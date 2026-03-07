<?php

namespace App\Filament\Resources\LMS\Grades\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GradeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Grade Info')->schema([
                    Select::make('enrollment_id')
                        ->relationship('enrollment', 'id')
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->user->name} - {$record->course->name}")
                        ->getSearchResultsUsing(fn (string $search): array => \App\Models\SIS\Enrollment::whereHas('user', fn ($query) => $query->where('name', 'ilike', "%{$search}%"))
                            ->orWhereHas('course', fn ($query) => $query->where('name', 'ilike', "%{$search}%"))
                            ->with(['user', 'course'])
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($record) => [$record->id => "{$record->user->name} - {$record->course->name}"])
                            ->toArray())
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn ($state, $set) => $set('course_id', \App\Models\SIS\Enrollment::find($state)?->course_id)),

                    Select::make('course_id')
                        ->relationship('course', 'name')
                        ->searchable()
                        ->required()
                        ->disabled()
                        ->dehydrated(),

                    Select::make('assessment_id')
                        ->relationship('assessment', 'title')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Optional — leave blank for manual grade'),

                    TextInput::make('raw_score')
                        ->numeric()
                        ->required(),

                    TextInput::make('max_score')
                        ->numeric()
                        ->required(),

                    TextInput::make('percentage')
                        ->numeric()
                        ->required(),

                    TextInput::make('letter_grade')
                        ->maxLength(255),

                    Textarea::make('comments')
                        ->columnSpanFull(),

                    DateTimePicker::make('recorded_at')
                        ->default(now()),

                    Select::make('recorded_by_user_id')
                        ->relationship('recorder', 'name')
                        ->searchable()
                        ->nullable(),
                ])->columns(2),
            ]);
    }
}
