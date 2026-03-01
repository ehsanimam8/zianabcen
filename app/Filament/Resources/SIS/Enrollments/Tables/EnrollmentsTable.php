<?php

namespace App\Filament\Resources\SIS\Enrollments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class EnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('user.name')->searchable()->sortable()->label('Student'),
                \Filament\Tables\Columns\TextColumn::make('course.name')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('term.name')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('status')->badge(),
                \Filament\Tables\Columns\TextColumn::make('enrolled_at')->date()->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])

            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction::make(),
                ]),
            ]);
    }
}
