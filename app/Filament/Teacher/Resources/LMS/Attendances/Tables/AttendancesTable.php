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
                \Filament\Tables\Columns\TextColumn::make('session.course.name')
                    ->label('Course')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('session.session_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'absent' => 'danger',
                        'late' => 'warning',
                        'excused' => 'gray',
                        default => 'gray',
                    }),
                \Filament\Tables\Columns\TextColumn::make('notes')->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
