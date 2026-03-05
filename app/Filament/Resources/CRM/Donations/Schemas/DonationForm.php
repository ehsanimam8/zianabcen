<?php

namespace App\Filament\Resources\CRM\Donations\Schemas;

use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make()->schema([
                    \Filament\Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->label('Donor')
                        ->placeholder('Anonymous')
                        ->nullable(),
                    \Filament\Forms\Components\TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->prefix('$'),
                    \Filament\Forms\Components\TextInput::make('currency')
                        ->default('USD')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\Select::make('donation_type')
                        ->options([
                            'one_time' => 'One Time',
                            'recurring' => 'Recurring',
                        ])
                        ->required()
                        ->default('one_time'),
                    \Filament\Forms\Components\TextInput::make('stripe_payment_id')
                        ->maxLength(255),
                    \Filament\Forms\Components\Select::make('status')
                        ->options([
                            'completed' => 'Completed',
                            'pending' => 'Pending',
                            'failed' => 'Failed',
                            'refunded' => 'Refunded',
                        ])
                        ->required()
                        ->default('completed'),
                    \Filament\Forms\Components\Textarea::make('notes')
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }
}
