<?php

namespace App\Filament\Resources\Payment\DiscountCodes\Schemas;

use Filament\Schemas\Schema;

class DiscountCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Discount Code Details')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        \Filament\Forms\Components\Select::make('discount_type')
                            ->options([
                                'percentage' => 'Percentage',
                                'fixed' => 'Fixed Amount',
                            ])
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('discount_amount')
                            ->required()
                            ->numeric(),
                        \Filament\Forms\Components\TextInput::make('usage_limit')
                            ->numeric(),
                        \Filament\Forms\Components\DateTimePicker::make('valid_from'),
                        \Filament\Forms\Components\DateTimePicker::make('valid_until'),
                        \Filament\Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
