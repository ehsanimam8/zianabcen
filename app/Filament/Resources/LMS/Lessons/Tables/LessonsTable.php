<?php

namespace App\Filament\Resources\LMS\Lessons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class LessonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('module.title')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('type')->badge(),
                \Filament\Tables\Columns\TextColumn::make('sequence')->sortable(),
                \Filament\Tables\Columns\IconColumn::make('is_published')->boolean(),
                \Filament\Tables\Columns\IconColumn::make('is_required')->boolean(),
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
