<?php

namespace App\Filament\Resources\CRM\Sponsorships\Schemas;

use Filament\Schemas\Schema;

class SponsorshipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make()->schema([
                    \Filament\Forms\Components\Select::make('sponsor_id')
                        ->relationship('sponsor', 'name')
                        ->required()
                        ->searchable()
                        ->label('Sponsor'),
                    \Filament\Forms\Components\Select::make('student_id')
                        ->relationship('student', 'name')
                        ->required()
                        ->searchable()
                        ->label('Student'),
                    \Filament\Forms\Components\TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->prefix('$'),
                    \Filament\Forms\Components\DatePicker::make('start_date')
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('end_date')
                        ->nullable(),
                    \Filament\Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'expired' => 'Expired',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->default('active'),
                    \Filament\Forms\Components\Textarea::make('notes')
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
