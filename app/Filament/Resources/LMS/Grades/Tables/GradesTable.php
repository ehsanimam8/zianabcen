<?php

namespace App\Filament\Resources\LMS\Grades\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
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

            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                    \pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction::make(),
                ]),
            ]);
    }
}
