<?php

namespace App\Filament\Resources\SIS\Courses\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('code')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('term.name')->label('Term')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('programs.name')->badge()->label('Programs'),
                \Filament\Tables\Columns\TextColumn::make('credits')->numeric(),
                \Filament\Tables\Columns\TextColumn::make('capacity')->numeric(),
                \Filament\Tables\Columns\TextColumn::make('price')->money('USD')->sortable(),
                \Filament\Tables\Columns\IconColumn::make('is_active')->boolean(),
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
