<?php

namespace App\Filament\Teacher\Resources\LMS\Assessments\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->disabled()
                        ->label('Student'),
                    Forms\Components\TextInput::make('total_score')
                        ->numeric()
                        ->label('Total Score'),
                    Forms\Components\Select::make('status')
                        ->options([
                            'submitted' => 'Submitted',
                            'grading' => 'Grading',
                            'graded' => 'Graded',
                        ])
                        ->required(),
                ]),
                Forms\Components\Textarea::make('instructor_feedback')
                    ->columnSpanFull(),

                \Filament\Schemas\Components\Section::make('Student Answers')
                    ->schema([
                        Forms\Components\Repeater::make('answers')
                            ->relationship('answers')
                            ->schema([
                                Forms\Components\Placeholder::make('question_text')
                                    ->label('Question')
                                    ->content(fn ($record) => $record?->question?->question_text),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('student_answer')
                                        ->label('Student Answer')
                                        ->disabled(),
                                    Forms\Components\Placeholder::make('reference_answer')
                                        ->label('Reference Answer')
                                        ->content(fn ($record) => $record?->question?->correct_answer ?? 'N/A'),
                                ]),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Toggle::make('is_correct')
                                        ->label('Correct'),
                                    Forms\Components\TextInput::make('points_awarded')
                                        ->numeric()
                                        ->label('Points Awarded'),
                                ]),
                                Forms\Components\Textarea::make('instructor_comment')
                                    ->label('Instructor Question Comment')
                                    ->rows(2),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columnSpanFull()
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'submitted' => 'gray',
                        'grading' => 'warning',
                        'graded' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_score')
                    ->label('Score')
                    ->sortable(),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                EditAction::make()
                    ->label('Grade'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
