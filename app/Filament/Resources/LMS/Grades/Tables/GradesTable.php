<?php

namespace App\Filament\Resources\LMS\Grades\Tables;

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
                \Filament\Tables\Columns\TextColumn::make('enrollment.user.name')->searchable()->sortable()->label('Student'),
                \Filament\Tables\Columns\TextColumn::make('course.name')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('percentage')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('letter_grade')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('recorded_at')->dateTime(),
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
