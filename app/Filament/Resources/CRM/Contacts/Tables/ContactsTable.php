<?php

namespace App\Filament\Resources\CRM\Contacts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('phone')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('contact_type')->badge()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('user.name')->label('Linked User'),
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
