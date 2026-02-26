<?php

namespace App\Filament\Resources\CRM\FamilyLinks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
