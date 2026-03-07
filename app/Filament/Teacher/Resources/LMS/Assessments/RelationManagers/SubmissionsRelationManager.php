<?php

namespace App\Filament\Teacher\Resources\LMS\Assessments\RelationManagers;

use App\Filament\Teacher\Resources\LMS\Assessments\AssessmentResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    public function form(Schema $schema): Schema
    {
        // Form not used — submissions are reviewed on a dedicated page.
        return $schema->components([]);
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
                        'grading'   => 'warning',
                        'graded'    => 'success',
                        default     => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_score')
                    ->label('Score')
                    ->sortable(),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Action::make('review')
                    ->label('Review & Grade')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn ($record) => AssessmentResource::getUrl('view-submission', [
                        'record'     => $record->assessment_id,
                        'submission' => $record->id,
                    ])),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
