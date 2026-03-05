<?php

namespace App\Filament\Resources\Payment\DiscountCodes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class DiscountCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('discount_type')
                    ->badge()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('discount_amount')
                    ->numeric()
                    ->sortable(),
                \Filament\Tables\Columns\ToggleColumn::make('is_active')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('usage_limit')
                    ->numeric()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('valid_from')
                    ->dateTime()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('valid_until')
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
