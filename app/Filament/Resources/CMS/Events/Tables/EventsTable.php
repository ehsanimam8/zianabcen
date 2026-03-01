<?php

namespace App\Filament\Resources\CMS\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('image')
                    ->circular(),
                \Filament\Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('event_start')
                    ->dateTime()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('event_end')
                    ->dateTime()
                    ->sortable(),
                \Filament\Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'Upcoming',
                        'success' => 'Ongoing',
                        'danger' => 'Past',
                    ]),
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
