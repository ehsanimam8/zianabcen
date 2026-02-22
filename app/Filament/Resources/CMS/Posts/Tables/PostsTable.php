<?php

namespace App\Filament\Resources\CMS\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('post_type')->badge()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('author.name')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
                \Filament\Tables\Columns\IconColumn::make('is_sticky')->boolean(),
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
