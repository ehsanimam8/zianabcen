<?php

namespace App\Filament\Resources\SIS\Programs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class ProgramsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('code')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('level')->badge(),
                \Filament\Tables\Columns\TextColumn::make('price')->money('USD')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('duration_months')->numeric()->label('Duration (mo)'),
                \Filament\Tables\Columns\IconColumn::make('is_active')->boolean(),
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
