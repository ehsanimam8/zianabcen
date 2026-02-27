<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('log_name')
                    ->badge()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->sortable(),
                TextColumn::make('causer.name')
                    ->label('Caused By')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
