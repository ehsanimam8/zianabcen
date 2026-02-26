<?php

namespace App\Filament\Teacher\Resources\LMS\Grades\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class GradesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('enrollment.user.name')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('course.name')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('assessment.title')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('max_score'),
                \Filament\Tables\Columns\TextColumn::make('raw_score'),
                \Filament\Tables\Columns\TextColumn::make('letter_grade'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
