<?php

namespace App\Filament\Resources\SIS\Enrollments\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;

class EnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('user.name')->searchable()->sortable()->label('Student'),
                \Filament\Tables\Columns\TextColumn::make('program.name')->searchable()->sortable(),
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
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                    \pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction::make(),
                ]),
            ]);
    }
}
