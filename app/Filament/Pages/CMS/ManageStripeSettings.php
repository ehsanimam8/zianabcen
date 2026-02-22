<?php

namespace App\Filament\Pages\CMS;

use BackedEnum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class ManageStripeSettings extends Page
{

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    public static function getNavigationGroup(): ?string
    {
        return 'System Settings';
    }

    protected static ?string $title = 'Stripe Settings';

    protected string $view = 'filament.pages.c-m-s.manage-stripe-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'stripe_public_key' => \App\Models\CMS\Setting::where('key', 'stripe_public_key')->value('value'),
            'stripe_secret_key' => \App\Models\CMS\Setting::where('key', 'stripe_secret_key')->value('value'),
            'stripe_webhook_secret' => \App\Models\CMS\Setting::where('key', 'stripe_webhook_secret')->value('value'),
            'stripe_currency' => \App\Models\CMS\Setting::where('key', 'stripe_currency')->value('value') ?? 'USD',
        ]);
    }

    public function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Stripe API Credentials')
                    ->description('These credentials are required to process live and test payments.')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('stripe_public_key')
                            ->label('Publishable Key')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('stripe_secret_key')
                            ->label('Secret Key')
                            ->password()
                            ->revealable()
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('stripe_webhook_secret')
                            ->label('Webhook Secret')
                            ->password()
                            ->revealable()
                            ->maxLength(255),
                        \Filament\Forms\Components\Select::make('stripe_currency')
                            ->label('Default Currency')
                            ->options([
                                'USD' => 'US Dollar (USD)',
                                'CAD' => 'Canadian Dollar (CAD)',
                                'GBP' => 'British Pound (GBP)',
                                'EUR' => 'Euro (EUR)',
                            ])
                            ->required()
                            ->default('USD'),
                    ])->columns(1),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            \App\Models\CMS\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        \Filament\Notifications\Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}
