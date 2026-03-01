<?php

namespace App\Filament\Resources\SIS\Enrollments\Schemas;

use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Enrollment Details')->schema([
                    \Filament\Forms\Components\Select::make('user_id')->relationship('user', 'name')->required()->label('Student'),
                    \Filament\Forms\Components\Select::make('course_id')->relationship('course', 'name')->required(),
                    \Filament\Forms\Components\Select::make('term_id')->relationship('term', 'name')->required(),
                    \Filament\Forms\Components\Select::make('status')->options([
                        'Pending' => 'Pending',
                        'Enrolled' => 'Enrolled',
                        'Completed' => 'Completed',
                        'Dropped' => 'Dropped',
                        'Suspended' => 'Suspended',
                        'Refunded' => 'Refunded',
                    ])->required()->default('Pending'),
                ])->columns(2),
                \Filament\Schemas\Components\Section::make('Dates & Finances')->schema([
                    \Filament\Forms\Components\DatePicker::make('enrolled_at')->required()->default(now()),
                    \Filament\Forms\Components\DatePicker::make('expires_at'),
                    \Filament\Forms\Components\TextInput::make('amount_paid')->numeric()->prefix('$')->default(0),
                    \Filament\Forms\Components\Select::make('payment_method')->options([
                        'stripe_online' => 'Online (Stripe)',
                        'offline_check' => 'Offline (Check)',
                        'offline_cash' => 'Offline (Cash)',
                        'offline_transfer' => 'Offline (Bank Transfer)',
                    ])->default('offline_cash'),
                ])->columns(2),
            ]);
    }
}
