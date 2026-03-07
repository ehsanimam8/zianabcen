<?php

namespace App\Filament\Resources\CMS\EventRegistrations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class EventRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('event.displayName')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                \Filament\Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                \Filament\Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'registered',
                        'success' => 'attended',
                        'danger'  => 'cancelled',
                    ])
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('registered_at')
                    ->dateTime()
                    ->sortable(),
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
