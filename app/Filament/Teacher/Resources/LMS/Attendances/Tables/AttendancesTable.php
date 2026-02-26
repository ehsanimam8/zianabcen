<?php

namespace App\Filament\Teacher\Resources\LMS\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('courseSession.session_date')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('student.name')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('status'),
                \Filament\Tables\Columns\TextColumn::make('notes')->limit(50),
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
