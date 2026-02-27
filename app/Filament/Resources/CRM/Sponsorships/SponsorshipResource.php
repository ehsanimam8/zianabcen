<?php

namespace App\Filament\Resources\CRM\Sponsorships;

use App\Filament\Resources\CRM\Sponsorships\Pages\CreateSponsorship;
use App\Filament\Resources\CRM\Sponsorships\Pages\EditSponsorship;
use App\Filament\Resources\CRM\Sponsorships\Pages\ListSponsorships;
use App\Filament\Resources\CRM\Sponsorships\Schemas\SponsorshipForm;
use App\Filament\Resources\CRM\Sponsorships\Tables\SponsorshipsTable;
use App\Models\CRM\Sponsorship;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SponsorshipResource extends Resource
{
    protected static ?string $model = Sponsorship::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-heart';

    public static function getNavigationGroup(): ?string
    {
        return 'CRM';
    }

        public static function form(Schema $schema): Schema
    {
        return SponsorshipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SponsorshipsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSponsorships::route('/'),
            'create' => CreateSponsorship::route('/create'),
            'edit' => EditSponsorship::route('/{record}/edit'),
        ];
    }
}
