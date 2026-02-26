<?php

namespace App\Filament\Teacher\Resources\LMS\CourseSessions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class CourseSessionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('course.name')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('instructor.name')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('session_date')->date()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('session_start_time')->time(),
                \Filament\Tables\Columns\TextColumn::make('platform'),
                \Filament\Tables\Columns\IconColumn::make('is_cancelled')->boolean(),
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
