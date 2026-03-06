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
            ->actions([
                \Filament\Tables\Actions\Action::make('preview')
                    ->icon('heroicon-m-eye')
                    ->color('success')
                    ->url(fn ($record) => route($record->post_type === 'page' ? 'frontend.pages.show' : 'frontend.posts.show', $record->slug))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
