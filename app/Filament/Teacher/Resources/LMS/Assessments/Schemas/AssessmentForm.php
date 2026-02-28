<?php

namespace App\Filament\Teacher\Resources\LMS\Assessments\Schemas;

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
                \Filament\Schemas\Components\Section::make('Assessment Details')->schema([
                    \Filament\Forms\Components\Select::make('course_id')
                        ->relationship('course', 'name')
                        ->required(),
                    TextInput::make('title')
                        ->required(),
                    \Filament\Forms\Components\Select::make('type')
                        ->options([
                            'quiz' => 'Quiz',
                            'exam' => 'Exam',
                            'assignment' => 'Assignment'
                        ])
                        ->required(),
                    DateTimePicker::make('due_date'),
                    TextInput::make('time_limit_minutes')
                        ->numeric()
                        ->suffix('mins'),
                    TextInput::make('max_attempts')
                        ->required()
                        ->numeric()
                        ->default(1),
                    Textarea::make('description')
                        ->columnSpanFull(),
                    Toggle::make('is_published')
                        ->required(),
                ])->columns(2),

                \Filament\Schemas\Components\Section::make('Questions')->schema([
                    \Filament\Forms\Components\Repeater::make('questions')
                        ->relationship()
                        ->reorderableWithDragAndDrop(false)
                        ->collapsed()
                        ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? null)
                        ->schema([
                            \Filament\Forms\Components\Select::make('question_type')
                                ->options([
                                    'multiple_choice' => 'Multiple Choice',
                                    'true_false' => 'True/False',
                                    'short_answer' => 'Short Answer',
                                    'essay' => 'Essay',
                                ])
                                ->required()
                                ->live(),
                            TextInput::make('question_text')
                                ->required()
                                ->maxLength(1000)
                                ->columnSpanFull(),
                            
                            // Options builder for multiple choice
                            \Filament\Forms\Components\Repeater::make('options')
                                ->schema([
                                    TextInput::make('text')->required(),
                                    Toggle::make('is_correct')->inline(false),
                                ])
                                ->visible(fn ($get) => $get('question_type') === 'multiple_choice')
                                ->columnSpanFull(),

                            // Simple answer for True/False
                            \Filament\Forms\Components\Select::make('correct_answer')
                                ->options([
                                    'true' => 'True',
                                    'false' => 'False',
                                ])
                                ->visible(fn ($get) => $get('question_type') === 'true_false')
                                ->required(fn ($get) => $get('question_type') === 'true_false'),

                            // Text matching for Short Answer
                            TextInput::make('correct_answer')
                                ->label('Exact Correct Answer')
                                ->visible(fn ($get) => $get('question_type') === 'short_answer')
                                ->required(fn ($get) => $get('question_type') === 'short_answer'),
                                
                            TextInput::make('points')
                                ->numeric()
                                ->required()
                                ->default(1),
                        ])->columns(2)
                ])
            ]);
    }
}
