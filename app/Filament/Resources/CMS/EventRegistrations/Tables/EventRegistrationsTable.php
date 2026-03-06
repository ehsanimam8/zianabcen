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
                \Filament\Tables\Columns\TextColumn::make('event.post.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('contact.name')
                    ->label('Registrant')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'registered',
                        'success' => 'attended',
                        'danger' => 'cancelled',
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
