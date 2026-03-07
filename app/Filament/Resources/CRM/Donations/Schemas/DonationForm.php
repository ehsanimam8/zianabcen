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
                    \Filament\Forms\Components\Placeholder::make('stripe_link')
                    ->label('Make a Donation via Stripe')
                    ->content(new \Illuminate\Support\HtmlString('If you would like to make a donation online, please visit <a href="https://donate.stripe.com/5kA8zk6iFeQo4pOfYY" target="_blank" class="text-primary-600 underline">this Stripe link</a>.')),

                \Filament\Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->label('Donor')
                        ->placeholder('Anonymous')
                        ->nullable(),
                    \Filament\Forms\Components\Select::make('sponsored_student_id')
                        ->relationship('sponsoredStudent', 'name')
                        ->searchable()
                        ->label('Sponsored Student')
                        ->placeholder('None (General Donation)')
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
