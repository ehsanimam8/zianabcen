<?php

namespace App\Filament\Resources\CRM\Sponsorships\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;

class SponsorshipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('user.name')->searchable()->sortable()->label('Sponsor'),
                \Filament\Tables\Columns\TextColumn::make('student.name')->searchable()->sortable()->label('Sponsored Student'),
                \Filament\Tables\Columns\TextColumn::make('amount')->money('usd')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('status')->badge(),
                \Filament\Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    \pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction::make(),
                ]),
            ]);
    }
}
