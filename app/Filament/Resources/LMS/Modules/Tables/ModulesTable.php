<?php

namespace App\Filament\Resources\LMS\Modules\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;

class ModulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('course.name')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('sequence')->sortable(),
                \Filament\Tables\Columns\IconColumn::make('is_published')->boolean(),
                \Filament\Tables\Columns\TextColumn::make('start_date')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
