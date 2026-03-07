<?php

namespace App\Filament\Resources\CRM\FamilyLinks\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;

class FamilyLinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('parent.name')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('student.name')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('relationship'),
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
